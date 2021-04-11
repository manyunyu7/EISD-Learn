<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Henry Augusta Harsono',
            'role' => 'mentor',
            'contact' => '088223738709',
            'jobs' => 'Mobile Developer',
            'institute' => 'EAD Laboratory',
            'motto' => '',
            'email' => 'henryaugusta4@email.com',
            'profile_url' => '1.png',
            'password' => bcrypt('password'),
            'created_at' => Carbon::now()->timezone('Asia/Bangkok')->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->timezone('Asia/Bangkok')->format('Y-m-d H:i:s')
        ]);

        DB::table('users')->insert([
            'name' => 'Fairus Zhilvan Adhipramana',
            'role' => 'mentor',
            'contact' => '0882780780',
            'jobs' => 'Java Developer',
            'institute' => '',
            'motto' => '',
            'email' => 'zhilvanbitcoin@email.com',
            'profile_url' => 'pairus.jpg',
            'password' => bcrypt('password'),
            'created_at' => Carbon::now()->timezone('Asia/Bangkok')->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->timezone('Asia/Bangkok')->format('Y-m-d H:i:s')
        ]);

        DB::table('users')->insert([
            'name' => 'Aurora Margareta Rompas',
            'role' => 'mentor',
            'contact' => '0882780780',
            'jobs' => '',
            'institute' => 'SISJAR Laboratory',
            'motto' => '',
            'email' => 'rora@email.com',
            'profile_url' => '3.jpg',
            'password' => bcrypt('password'),
            'created_at' => Carbon::now()->timezone('Asia/Bangkok')->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->timezone('Asia/Bangkok')->format('Y-m-d H:i:s')
        ]);

        DB::table('users')->insert([
            'name' => 'Rizki Fauzan Nasrullah',
            'role' => 'mentor',
            'contact' => '0882780780',
            'jobs' => '',
            'institute' => 'SISJAR Laboratory',
            'motto' => '',
            'email' => 'rispo@email.com',
            'profile_url' => 'rispo.jpg',
            'password' => bcrypt('password'),
            'created_at' => Carbon::now()->timezone('Asia/Bangkok')->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->timezone('Asia/Bangkok')->format('Y-m-d H:i:s')
        ]);

        DB::table('users')->insert([
            'name' => 'Hanif Catrio Wicaksono',
            'role' => 'student',
            'contact' => '0899667689979',
            'jobs' => '',
            'institute' => 'SISJAR Laboratory',
            'motto' => '',
            'email' => 'hanif@email.com',
            'profile_url' => 'hanif.jpg',
            'password' => bcrypt('password'),
            'created_at' => Carbon::now()->timezone('Asia/Bangkok')->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->timezone('Asia/Bangkok')->format('Y-m-d H:i:s')
        ]);

        
    }
}
