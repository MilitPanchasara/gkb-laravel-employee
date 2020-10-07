<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        factory(App\Employee::class, 10)->create()->each(
            function($user) {
                factory(App\EmployeesHobby::class)->create(['employee_id' => $user->id]);
            }
        );
    }
}
