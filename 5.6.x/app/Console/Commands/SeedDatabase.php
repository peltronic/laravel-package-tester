<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

use PsgcLaravelPackages\DataSeeder\SeedManager;
use PsgcLaravelPackages\DataSeeder\Seedtable;

// See:
// ~ https://stackoverflow.com/questions/25148662/how-do-i-write-to-the-console-from-a-laravel-controller
class SeedDatabase extends Command implements SeedConfigurable // %FIXME: somone else should implement these no? Basically how we get the configuration....
{
    protected $signature = 'database:seed {--seedpath=} {--truncate} {--debug=} {seeds*}';
    protected $description = 'Seed the database with listed seed modules';

    protected $_seedMgr = null;

    protected $_whitelistedServers = [];
    protected $_seedConfigs = [];
    protected $_seedTables = [];

    public function __construct()
    {
        parent::__construct();

        // for truncate
        $this->_whitelistedServers = [
            'peter-localhost',
            'nl-web1-stage',
        ];

        $this->_seedConfigs = [
            'widgets'=> [
                'astate'=>['resolved_by'=>'\App\Models\Enum\Application\AstateEnum::findKeyBySlug'],
                'fielder'=>['belongs_to'=>['table'=>'users','keyed_by'=>'username','fkid'=>'fielder_id']],
                'account'=>['belongs_to'=>['table'=>'accounts','keyed_by'=>'accountnumber','fkid'=>'account_id']],
                // keyed_by is how we lookup the record in the related table
                // fkid is how we store the record as FKID in our table
            ],
        ];

        // %FIXME: This should also include the filepath to the seedfile (?)
        $this->_seedTables = [
            new Seedtable('roles',10,, ['role_user'] );
            new Seedtable('accounts',30, ['widgets'] ); // is required_by redundant due to seedConfigs above?? %FIXME
            // %TODO: deletenig accounts => must delete widgets too, we can derive this from seedConfigs with some code...
            // %TODO: instead of automatically deleting required_bys, require the user to manually type it on command line as option!
            new Seedtable('widgets',50);
            new Seedtable('gadgets',50);
            new Seedtable('users',80,['role_user']);
        ];

    }

    public function handle()
    {
        $this->info( 'DB Connection: '.env('DB_CONNECTION','mysql') );

        $debug       = $this->option('debug');
        $truncate    = $this->option('truncate');
        //$this->_seedTables     = $this->argument('seeds');
        //$seedSubpath = $this->option('seedpath');

        $meta = [
            'whitelisted_servers' => $this->_whitelistedServers,
        ];

        $this->_seedMgr = new SeedManager($this->_seedTables,$this->_seedConfigs,$seedSubpath, $truncate,$debug,$meta);

        $this->_seedMgr->seed();
    }
}

interface SeedConfigurable {
     public function getWhitelistedServers();
     public function getSeedconfigs();
     public function getSeedtables();
} 
