<?php

namespace Database\Seeders;

use App\Models\Reaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ReactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Reaction::count() > 0)Reaction::truncate();
        Reaction::create([
            'label' => 'Vibe',
            'external_link' => 'https://fonts.gstatic.com/s/e/notoemoji/latest/1f60e/512.webp',
            'link' => env('APP_URL') . Storage::url('Vibe.webp'),
        ]);
        Reaction::create([
            'label' => 'Funny',
            'external_link' => 'https://fonts.gstatic.com/s/e/notoemoji/latest/1f602/512.webp',
            'link' => env('APP_URL') . Storage::url('Funny.webp'),
        ]);
        Reaction::create([
            'label' => 'Happy-cry',
            'external_link' => 'https://fonts.gstatic.com/s/e/notoemoji/latest/1f972/512.webp',
            'link' => env('APP_URL') . Storage::url('Happy-cry.webp'),
        ]);
        Reaction::create([
            'label' => 'Fire',
            'external_link' => 'https://fonts.gstatic.com/s/e/notoemoji/latest/1f525/512.webp',
            'link' => env('APP_URL') . Storage::url('Fire.webp'),
        ]);
    }
}
