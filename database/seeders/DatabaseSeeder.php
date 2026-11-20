<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Заполняем таблицу ролей
        $moderatorRole = Role::firstOrCreate(
            ['name' => 'moderator'],
            ['title' => 'Модератор']
        );

        Role::firstOrCreate(
            ['name' => 'reader'],
            ['title' => 'Читатель']
        );

        // 2. Сохраняем в таблице пользователей модератора
        User::firstOrCreate(
            ['email' => 'moderator@example.ru'],
            [
                'role_id'  => $moderatorRole->id,
                'name'     => 'Модератор',
                'password' => Hash::make('moderator123'),
            ]
        );

        // 3. Генерируем новости
        Article::factory(6)->create();
    }
}
