<?php

use App\Employee;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(); 

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/employees/import','Employee\EmployeeController@importCSV')->middleware('auth');
Route::post('/employees/import/save','Employee\EmployeeController@saveCSVData')->name('employees.saveCSVData')->middleware('auth');
Route::post('/employees/dataTable','Employee\EmployeeController@dataTable')->name('employees.data');
Route::resource('employees','Employee\EmployeeController')->middleware('auth');

