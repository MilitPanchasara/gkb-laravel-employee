<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'first_name','last_name','email','gender','profile_picture'
    ];

    public $primaryKey = 'id';//primary key

    public $timestamps = true;//time stamps

    public function hobbies(){
        return $this->hasMany('App\EmployeesHobby');
    }
}
