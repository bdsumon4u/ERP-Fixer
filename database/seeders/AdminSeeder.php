<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::query()->firstOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'aDmiN',
            'password' => bcrypt('password'),
        ]);
    }
}
