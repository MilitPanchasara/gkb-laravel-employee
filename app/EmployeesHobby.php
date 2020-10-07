<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeesHobby extends Model
{
    protected $fillable = [
        'employee_id','hobby'
    ];

    public $primaryKey = 'id';//primary key

    public $timestamps = true;//time stamps

    public function employee(){
        return $this->belongsTo('App\Employee');
    }
}
