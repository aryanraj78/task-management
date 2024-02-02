<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                
                'id' => 1,
                'role' => 'admin',
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$ghYtiS0Sw6NoymFTytuu9uyMdyEk.kCYGj4s2eVx85fBGCGtcBLQu',
                'remember_token' => NULL,
                'created_at' => '2024-02-01 11:25:37',
                'updated_at' => '2024-02-01 11:25:37',
            ),
            
        ));
        
        
    }
}