<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', function () {
    return view('home');
});
Route::get('/angular', function () {
    return view('boot-app');
});
Route::group(['prefix' => 'admin', 'middleware' => 'auth.checkrole:admin', 'as' => 'admin.'], function() {
    Route::get('clients', ['as' => 'clients.index', 'uses' => 'ClientController@index']);
    Route::get('clients/create', ['as' => 'clients.create', 'uses' => 'ClientController@create']);
    Route::get('clients/edit/{id}', ['as' => 'clients.edit', 'uses' => 'ClientController@edit']);
    Route::post('clients/update/{id}', ['as' => 'clients.update', 'uses' => 'ClientController@update']);
    Route::post('clients/store', ['as' => 'clients.store', 'uses' => 'ClientController@store']);

    Route::get('categories', ['as' => 'categories.index', 'uses' => 'CategoriesController@index']);
    Route::get('categories/create', ['as' => 'categories.create', 'uses' => 'CategoriesController@create']);
    Route::get('categories/edit/{id}', ['as' => 'categories.edit', 'uses' => 'CategoriesController@edit']);
    Route::post('categories/update/{id}', ['as' => 'categories.update', 'uses' => 'CategoriesController@update']);
    Route::post('categories/store', ['as' => 'categories.store', 'uses' => 'CategoriesController@store']);

    Route::get('products', ['as' => 'products.index', 'uses' => 'ProductsController@index']);
    Route::get('products/create', ['as' => 'products.create', 'uses' => 'ProductsController@create']);
    Route::get('products/edit/{id}', ['as' => 'products.edit', 'uses' => 'ProductsController@edit']);
    Route::post('products/update/{id}', ['as' => 'products.update', 'uses' => 'ProductsController@update']);
    Route::post('products/store', ['as' => 'products.store', 'uses' => 'ProductsController@store']);
    Route::get('products/destroy/{id}', ['as' => 'products.destroy', 'uses' => 'ProductsController@destroy']);

    Route::get('orders', ['as' => 'orders.index', 'uses' => 'OrdersController@index']);
    Route::get('orders/{id}', ['as' => 'orders.edit', 'uses' => 'OrdersController@edit']);
    Route::post('orders/update/{id}', ['as' => 'orders.update', 'uses' => 'OrdersController@update']);

    Route::get('cupoms', ['as' => 'cupoms.index', 'uses' => 'CupomsController@index']);
    Route::get('cupoms/create', ['as' => 'cupoms.create', 'uses' => 'CupomsController@create']);
    Route::post('cupoms/store', ['as' => 'cupoms.store', 'uses' => 'CupomsController@store']);
});

Route::group(['prefix' => 'customer', 'middleware' => 'auth.checkrole:client', 'as' => 'customer.'], function() {
    Route::get('order', ['as' => 'order.index', 'uses' => 'CheckoutController@index']);
    Route::get('order/create', ['as' => 'order.create', 'uses' => 'CheckoutController@create']);
    Route::post('order/store', ['as' => 'order.store', 'uses' => 'CheckoutController@store']);
});

Route::group(['middleware' => 'cors'], function() {
    Route::post('oauth/access_token', function() {
        return Response::json(Authorizer::issueAccessToken());
    });
    Route::group(['prefix' => 'api', 'middleware' => 'oauth', 'as' => 'api.'], function() {
        //admin
        Route::group(['prefix' => 'admin', 'middleware' => 'oauth.checkrole:admin', 'as' => 'admin.'], function() {
            Route::resource('products','Api\Admin\AdminProductController',['except' => ['create', 'edit']]);
            Route::resource('categories','Api\Admin\AdminCategoryController',['except' => ['create', 'edit', 'destroy', 'store']]);            
        });
        //client
        Route::group(['prefix' => 'client', 'middleware' => 'oauth.checkrole:client', 'as' => 'client.'], function() {
            Route::get('products', ['as' => 'api.products.index', 'uses' => 'Api\Client\ClientProductController@index']);
            Route::post('order', ['as' => 'api.order.store', 'uses' => 'Api\Client\ClientCheckoutController@store']);
        });
        //deliveryman
        Route::group(['prefix' => 'deliveryman', 'middleware' => 'oauth.checkrole:deliveryman', 'as' => 'deliveryman.'], function() {
            Route::resource('order', 'Api\Deliveryman\DeliverymanCheckoutController', ['except' => ['create', 'edit', 'destroy', 'store']]);
            Route:patch('order/{id}/update-status', ['uses' => 'Api\Deliveryman\DeliverymanCheckoutController@updateStatus', 'as' => 'orders.update_status']);
        });
        //cupom
        Route::get('cupom/{code}', ['as' => 'api.cupom.show', 'uses' => 'Api\CupomController@show']);
		//user
		Route::get('user/authenticated', ['as' => 'api.user.authenticated', 'uses' => 'Api\UserController@authenticated']);
    });
});


