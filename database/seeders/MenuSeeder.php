<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $home = \App\Models\Menu::create(['name' => 'Home', 'url' => '/#hero', 'order' => 1]);
        $about = \App\Models\Menu::create(['name' => 'About', 'url' => '/#about', 'order' => 2]);
        
        $services = \App\Models\Menu::create(['name' => 'Services', 'url' => '/#services', 'order' => 3]);
        \App\Models\Menu::create(['name' => 'Sub Menu 1', 'url' => '#', 'parent_id' => $services->id, 'order' => 1]);
        \App\Models\Menu::create(['name' => 'Sub Menu 2', 'url' => '#', 'parent_id' => $services->id, 'order' => 2]);
        
        $contact = \App\Models\Menu::create(['name' => 'Contact', 'url' => '/#contact', 'order' => 4]);
    }
}
