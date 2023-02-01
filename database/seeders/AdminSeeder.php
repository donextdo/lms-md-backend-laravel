<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\Enums\Roles;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@donext.lk',
            'password' => '$2y$10$HPptfB5dIB4kgUQFPAZxL.xBMHEdTFRhfmGHRv4gsFS6IggYzTAsO', // admin@123
            'role_id' => Roles::admin()->value,
            'contact_no' => null,
            'date_of_birth' => null,
        ]);
    }
}
