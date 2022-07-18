<?php

namespace Database\Seeders;

use App\Models\Seller;
use Illuminate\Database\Seeder;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Seller::query()->firstOrCreate([
            'email' => 'seller@seller.com',
        ], [
            'name' => 'Seller',
            'password' => bcrypt('password'),
        ]);
    }
}
