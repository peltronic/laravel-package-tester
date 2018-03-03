<?php

use PsgcLaravelPackages\AccessControl\AccessManager;

class RoleBasedAccessChecker extends AccessManager
{

    protected function getAccessMatrix() {
        return [

            'site.dashboard.show'=>[
                'newlogix-admin'=>'all',
                'fielder'=>'all',
                'project-manager'=>'all',
            ],

            'site.accounts.index'=>[
                'newlogix-admin'=>'all',
                'fielder'=>'all',
                'project-manager'=>'all',
            ],
        ];
    } // getAccessMatrix()

}

