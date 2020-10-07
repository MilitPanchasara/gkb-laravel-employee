<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Employee::class, function (Faker $faker) {

    
    $gender = $faker->randomElement(['male', 'female']);
    $profilePic = $faker->randomElement(['1.png', '2.png','3.png', '4.png','5.png']);
    $fname = $faker->firstName($gender);
    $filenameToStore = $fname.time().'.png';
    copy(public_path () . '/seeding_images/' . $profilePic,public_path () . '/storage/profile_pictures/'.$filenameToStore);
    return [
        'first_name' => $faker->firstName($gender),
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'gender' => $gender,
        'profile_picture'=>$filenameToStore
    ];
});

$factory->define(App\EmployeesHobby::class, function (Faker $faker) {
    $hobby = $faker->randomElement(['TV', 'Reading', 'Skiing', 'Coding']);
    return [
        'hobby' => $hobby
    ];
});
