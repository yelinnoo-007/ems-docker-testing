<?php

namespace Database\Seeders;

use App\Models\Common;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class RoleSeeder extends Seeder
{
    /** 
     * Run the database seeds.
     */
    public function run(): void
    {
        $common = Common::find(Config::get('variables.TWENTY_THREE'));
        $adminName = $common->abbrv;
        $roles = [
           'Partner', 'Staff', $adminName
        ];
        foreach($roles as $role)
        {
            Role::create([
                'name' => $role,
            ]);
        }
    }
}
