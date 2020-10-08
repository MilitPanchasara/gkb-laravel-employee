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

//Employees Routes
Route::middleware('auth')->namespace('Employee')->group(function () {
    Route::get('/employees/import','EmployeeController@importCSV');
    Route::post('/employees/import/save','EmployeeController@saveCSVData')->name('employees.saveCSVData');
    Route::post('/employees/dataTable','EmployeeController@dataTable')->name('employees.data');
    Route::resource('employees','EmployeeController');
});

