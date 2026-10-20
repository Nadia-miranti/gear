<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('gears')->insert([
            [
                'name' => 'Tenda Eagle 2P',
                'category' => 'Tenda',
                'rental_price' => 200000,
                'description' => 'Tenda Eagle 2P is a lightweight and durable tent designed for two people. It features a spacious interior with ample headroom and a rainfly to protect against the elements. The tent is easy to set up and pack down, making it a great choice for camping trips and backpacking adventures.',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kompor Portable',
                'category' => 'Alat Masak',
                'rental_price' => 300000,
                'description' => 'Kompor Portable is a compact and portable camping stove that allows you to cook delicious meals on the go. It is easy to use and can be set up in minutes. The stove is made of high-quality materials and is designed to withstand harsh weather conditions.',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Head Lamp',
                'category' => 'Penerangan',
                'rental_price' => 300000,
                'description' => 'Head Lamp is a powerful and versatile camping light that provides bright and clear visibility in the dark. It is lightweight and easy to carry, making it perfect for camping trips and hiking adventures. The lamp is rechargeable and can be used for a long time before needing to recharge.',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sleeping Bag',
                'category' => 'Sleeping Gear',
                'rental_price' => 2000000,
                'description' => 'Sleeping Bag is a comfortable and cozy sleeping gear that provides a good night\'s sleep for campers. It is made of high-quality materials and is designed to keep you warm and dry during camping trips. The bag is easy to set up and can be used in various weather conditions.',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kompas',
                'category' => 'Aksesoris',
                'rental_price' => 20000,
                'description' => 'Kompas is a navigation tool that helps campers find their way during camping trips. It is a simple and effective tool that can be used in various situations. The compass is easy to use and can be used in conjunction with other navigation tools to ensure a safe and successful camping trip.',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
