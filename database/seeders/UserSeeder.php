<?php

namespace Database\Seeders;

use App\Models\InstructorProfile;
use App\Models\LearnerProfile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🔑 Admin
        User::create([
            'lastname' => 'Admin',
            'firstname' => 'Super',
            'email' => 'admin@smoni.fr',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '0600000000',
        ]);

        // 👨‍🎓 Élève
        $learner = User::create([
            'lastname' => 'Dupont',
            'firstname' => 'Jean',
            'email' => 'jean.dupont@smoni.fr',
            'password' => Hash::make('password'),
            'role' => 'learner',
            'phone' => '0611111111',
        ]);

        LearnerProfile::create([
            'user_id' => $learner->id,
            'birthdate' => '2000-05-15',
            'city' => 'Lyon',
            'address' => '12 rue Victor Hugo',
            'postal_code' => '69000',
            'cin_number' => '123456789',
            'cin_issue_date' => '2018-06-01',
            'cin_issue_place' => 'Lyon',
            'permit_number' => 'PERM987654',
            'permit_issue_date' => '2020-09-15',
            'permit_category' => 'B',
        ]);

        // 🧑‍🏫 Moniteur
        $instructor = User::create([
            'lastname' => 'Martin',
            'firstname' => 'Claire',
            'email' => 'claire.martin@smoni.fr',
            'password' => Hash::make('password'),
            'role' => 'instructor',
            'phone' => '0622222222',
        ]);

        InstructorProfile::create([
            'user_id' => $instructor->id,
            'specialty' => 'Permis B - conduite accompagnée',
            'bio' => 'Monitrice depuis 10 ans, passionnée par la pédagogie.',
            'address' => '24 rue Nationale',
            'city' => 'Marseille',
            'postal_code' => '13000',
            'certification_number' => 'CERT456789',
            'certification_issue_date' => '2015-04-10',
        ]);
    }
}
