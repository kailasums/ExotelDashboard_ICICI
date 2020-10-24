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
    Route::get('/import-users', 'UserRegisterController@index');
    Route::post('/admin/upload-file', 'UserRegisterController@uploadFile');
    Route::get('/admin/export-log', 'UserRegisterController@exportLog');
    Route::get('/admin/export-password', 'UserRegisterController@exportPassword');
});

Route::group(['middleware' => ['user']], function() {
    Route::get('/home', function () {
        return redirect('dashboard');
      })->name('home');
    Route::get('dashboard', 'CallRecordingController@pieChart');
    Route::get('drop-down', 'CallRecordingController@dropDownOption');
    Route::get('call-record-data','CallRecordingController@showData');
    Route::get('user-call-detail','CallRecordingController@detailList');
});

Route::get('call-recording', 'CallRecordingController@index');

Route::get('reset-password', 'HomeController@resetPassword');
Route::post('reset-password', 'HomeController@updatepassword');
Route::get('call-logs', 'UserRegisterController@backCallLogs');


Route::get('email-test', function(){
  
    $details['email'] = 'batekrushna@gmail.com';
  
    dispatch(new App\Jobs\SendEmailJob($details));
  
    dd('done');
});
