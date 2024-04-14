<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\FoldersModel;
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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $folderPadrao = FoldersModel::where('name','=','local')->first();
        if (is_null($folderPadrao)){
            $folderPadrao = new FoldersModel();
            $folderPadrao->name = 'local';
            $folderPadrao->parent_folder_id = 1;
            $folderPadrao->save();
        }
    }
}
