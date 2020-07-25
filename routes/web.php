<?php

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

// Route::get('/', function () {
//     return view('ownvideo');
// });
Route::group(['middleware'=>'auth'],function(){//不登入無法進入
  //Route::get('/videopage','VideoController@showvidepage');
  Route::get('/videopage', function () {
      return view('showvideo');
  });
  Route::get('/', function () {
      return view('ownvideo');
  });
});
Route::get('event',function(){
      return view('chat');
});
Route::get('/sender',function(){
      return view('sender');
});


Route::get('/logout','VideoController@logout');
Route::post('/videoinfor','VideoController@videodat');
Route::post('/upload','VideoController@create')->name('upload');
Route::post('/delet','VideoController@deletevideo')->name('delet');

Route::get('/ownvideodat','VideoController@ownvideo');

Route::post('/msgsubmit','MsgsubController@submsg');
Route::post('/videomsg','MsgsubController@searchmsg');
Route::post('/sender','MsgsubController@msgwebsocket');
Route::post('/deletemsg','MsgsubController@deletemsg');
Route::post('/updat','MsgsubController@showupdatmodel');
Route::put('/updatemsg','MsgsubController@updatemsg');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
