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

Route::redirect('/', 'threads');

Auth::routes();
Route::get('login/github', 'Auth\LoginController@redirectToProvider');
Route::get('login/github/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/home', 'HomeController@index');

Route::get('/threads', 'ThreadsController@index')->name('threads');
Route::get('/threads/search', 'SearchController@show');
Route::get('/threads/create', 'ThreadsController@create')->middleware('email-confirmed');
Route::post('/threads', 'ThreadsController@store')->middleware('email-confirmed');
Route::get('/threads/{channel}', 'ThreadsController@index');
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show');
Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy');
Route::patch('/threads/{channel}/{thread}', 'ThreadsController@update');
Route::post('/locked-threads/{thread}', 'LockedThreadsController@store')->name('locked-threads')->middleware('admin');
Route::delete('/locked-threads/{thread}', 'LockedThreadsController@destroy')->middleware('admin');
Route::post('/pinned-threads/{thread}', 'PinnedThreadsController@store')->name('pinned-threads')->middleware('admin');
Route::delete('/pinned-threads/{thread}', 'PinnedThreadsController@destroy')->middleware('admin');
Route::get('/threads/{channel}/{thread}/replies', 'RepliesController@index');
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store');
Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy')->middleware('auth');
Route::delete('/replies/{reply}', 'RepliesController@destroy')->name('replies.destroy');
Route::patch('/replies/{reply}', 'RepliesController@update');
Route::post('/replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy');
Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');
Route::get('/profiles/{user}/notifications', 'UserNotificationsController@index');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy');

Route::get('/api/users', 'Api\UsersController@index');
Route::post('/api/users/{user}/avatar', 'Api\UserAvatarController@store')->middleware('auth')->name('avatar_path');

Route::get('/register/confirm', 'Auth\RegisterConfirmationController@index')->name('register.confirm');
Route::post('/replies/{reply}/best', 'BestRepliesController@store')->name('best-replies.store');
