<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('dashboard', 'AuthController@test');

// channel 
Route::middleware('auth:api')->get('channel', 'ChannelController@getVisibleChannels');
Route::middleware('auth:api')->get('channel/all', 'ChannelController@getAllChannels');
Route::middleware('auth:api')->get('channel/{channelId}', 'ChannelController@getChannel');
Route::middleware('auth:api', 'role:create-channel')->post('channel', 'ChannelController@createChannel');
Route::middleware('auth:api', 'role:edit-channel')->put('channel/{channelId}', 'ChannelController@editChannel');
Route::middleware('auth:api', 'role:delete-channel')->delete('channel/{channelId}', 'ChannelController@deleteChannel');
Route::middleware('auth:api', 'role:edit-channel')->get('channel/{channelId}/undelete', 'ChannelController@undeleteChannel');

//dm channel
Route::middleware('auth:api', 'role:create-dm-channel')->post('dm', 'ChannelController@createDmChannel');

//roles
Route::middleware('auth:api')->get('/roles', 'RoleController@getAllRole');
Route::middleware('auth:api', 'role:edit-roles')->post('/role', 'RoleController@createRole');
Route::middleware('auth:api', 'role:edit-roles')->post('/role/{roleId}', 'RoleController@editRole');
Route::middleware('auth:api', 'role:edit-roles')->delete('/role/{roleId}', 'RoleController@deleteRole');
Route::middleware('auth:api')->get('/permissions', 'RoleController@getAllPermissions');

// message
Route::middleware('auth:api', 'role:read-messages')->get('message/{channelId}', 'MessageController@getMessages');
Route::middleware('auth:api', 'role:read-messages')->get('message/{channelId}/{messageId}', 'MessageController@getMessage');
Route::middleware('auth:api', 'role:write-messages')->post('message/{channelId}', 'MessageController@sendMessage');
Route::middleware('auth:api', 'role:write-messages')->post('message/{channelId}/file', 'MessageController@sendMessageWithFile');
Route::middleware('auth:api', 'role:edit-messages')->put('message/{channelId}/{messageId}', 'MessageController@editMessage');
Route::middleware('auth:api', 'role:delete-messages,delete-others-messages')->delete('message/{channelId}/{messageId}', 'MessageController@deleteMessage');

// users
Route::middleware('auth:api')->get('user', 'AuthController@getUser')->name('login');
// Route::middleware('auth:api')->get('user/permissions', 'AuthController@getUser');
Route::middleware('auth:api')->put('user/email', 'AuthController@editUserEmail');
Route::middleware('auth:api')->put('user/password', 'AuthController@editUserPassword');
Route::middleware('auth:api')->post('user/image', 'AuthController@updateUserImage');
Route::middleware('auth:api')->get('user/image/reset', 'AuthController@resetUserImage');
Route::middleware('auth:api')->get('users', 'AuthController@getUsers');

Route::middleware('auth:api', 'role:edit-roles')->post('user/{userId}', 'AuthController@setUserPrivileges');


Route::post('register', 'AuthController@register');

Route::group([
    
    'middleware' => 'api',
    'prefix' => 'auth'
    
], function ($router) {
    
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    
});
