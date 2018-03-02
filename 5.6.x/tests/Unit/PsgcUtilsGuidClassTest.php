<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use PsgcLaravelPackages\Utils\Guid;

class PsgcUtilsGuidClassTest extends TestCase
{
    public function test_create()
    {
        $str = Guid::create(false);
        $this->assertRegExp('/^[\w\d]+$/', $str);

        $str = Guid::create(true);
        $this->assertRegExp('/^[\w\d\-]+$/', $str);

    }
}
