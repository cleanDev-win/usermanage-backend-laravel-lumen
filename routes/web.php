<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */

$api = app( 'Dingo\Api\Routing\Router' );

$api->version( 'v1', ['namespace' => 'App\Http\Controllers'], function ( $api )
{

    $api->group( ['middleware' => 'api.auth'], function ( $api )
    {
        $api->get( '/auth/user', [
            'uses' => 'Auth\AuthController@getUser',
        ] );

        $api->post( 'uplaod-funds', [
            'uses' => 'FundController@uploadFunds',
        ] );

        $api->post( 'all-daily-funds', [
            'uses' => 'FundController@allDailyFunds',
        ] );

        $api->post( 'all-funds', [
            'uses' => 'FundController@allFunds',
        ] );
        
        $api->post( 'update-fund', [
            'uses' => 'FundController@updateFund',
        ] );
        
    } );

    $api->get( 'daily-funds', [
        'uses' => 'FundController@getDailyFunds',
    ] );

    $api->get( 'funds-list', [
        'uses' => 'FundController@getFundsList',
    ] );

    $api->get( 'fund-details/{fund_id}', [
        'uses' => 'FundController@getFundDetails',
    ] );

    $api->post( 'chart-data', [
        'uses' => 'FundController@getChartData',
    ] );

    $api->post( '/auth/login', [
        'uses' => 'Auth\AuthController@postLogin',
    ] );

    /**
     * user management
     */

     $api->get( 'user/getusers', ['uses' => 'UserManageController@getUsers']);
     $api->post( '/user/addnewuser', ['uses' => 'UserManageController@addNewUser']);
     $api->post( '/user/updateuser', ['uses' => 'UserManageController@updateUser']);
     $api->post( '/user/deleteuser', ['uses' => 'UserManageController@deleteUser']);
     $api->post('update-price', ['uses' => 'FundController@updatePrice']);
     $api->post('auth/update', ['uses' => 'UserManageController@updatePassword']);
     $api->post('/user/activeuser', ['uses' => 'UserManageController@activeUser']);
} );