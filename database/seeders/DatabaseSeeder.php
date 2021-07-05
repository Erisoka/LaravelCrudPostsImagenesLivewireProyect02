<?php

namespace Database\Seeders;

use Stringable;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // Post::factory(30)->create();
        $user = User::create([
            'id'             => 1,
            'name'           => 'Leonidas',
            'email'          => 'leonidas@test.com',
            'password'       => Hash::make('12345678'),
            'remember_token' => Str::random(10),
        ]);
        $user->save();

        // Crear manualmente primero la carpeta posts en public/storage,
        // Luego ejecutar las migraciones con seed.
        $folder = public_path('storage/posts');
        File::deleteDirectory($folder);
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0755, true, true);
        }
        Post::factory(30)->create();
    }
}
