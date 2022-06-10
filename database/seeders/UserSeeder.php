<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->forceDelete();
        $data = array(
            array('id' => '1',  'name' => 'kalpesh', 'email'=> 'kalpesh@1234gmail.com' ,'password'=> bcrypt('kalpesh@1234') ,'created_at' => '2022-05-18 15:15:47', 'updated_at' => '2022-05-18 15:15:47'),
            array('id' => '2',  'name' => 'mehul', 'email'=> 'mehul@1234gmail.com' ,'password'=> bcrypt('mehul@1234') ,'created_at' => '2022-05-18 15:15:47', 'updated_at' => '2022-05-18 15:15:47'),
            array('id' => '3',  'name' => 'sanjay', 'email'=> 'sanjay@1234gmail.com' ,'password'=> bcrypt('sanjay@1234') ,'created_at' => '2022-05-18 15:15:47', 'updated_at' => '2022-05-18 15:15:47'),
            array('id' => '4',  'name' => 'vivek', 'email'=> 'vivek@1234gmail.com' ,'password'=> bcrypt('vivek@1234') ,'created_at' => '2022-05-18 15:15:47', 'updated_at' => '2022-05-18 15:15:47'),                                    
        );
        User::insert($data);
    }
}
