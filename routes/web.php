<?php

use Illuminate\Support\Facades\Route;

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
    return redirect('login');
});

Auth::routes(['register' => false]);

Route::get('/admin/login', 'Auth\LoginController@showAdminLoginForm');
Route::post('/login/admin', 'Auth\LoginController@login')->name('admin-login');

Route::group(['middleware' => [ 'superadmin']], function() {
    Route::get('/admin/register-user', 'UserRegisterController@index');
    Route::post('/admin/uploadFile', 'UserRegisterController@uploadFile');
});

Route::group(['middleware' => ['user']], function() {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('pie-chart', 'CallRecordingController@pieChart');
});

Route::get('call-recording', 'CallRecordingController@index');


