<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\WebsiteSettings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([ 'email' => 'admin@indigenouslifestyle.com',
            'password' => Hash::make('12341234'),
            'user_name'=>'admin']);
        WebsiteSettings::create();
    }

}
