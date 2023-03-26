<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\ChatRoom;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        ChatRoom::create(["name" => "Room 1"]);
        ChatRoom::create(["name" => "Room 2"]);
        // \App\Models\User::factory(10)->create();
        // \App\Models\User::factory(10)->create();
        Admin::create([
            "email" => "admin@admin.com",
            "password" => "123456"
        ]);
    }
}
