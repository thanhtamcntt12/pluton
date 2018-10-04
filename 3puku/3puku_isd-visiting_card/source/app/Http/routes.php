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
Route::group(['prefix' => 'api'], function () {
	Route::group(['prefix' => 'admin'], function () {
		Route::post('login', ['uses'=>'AdminController@login']);
		Route::post('send_email', ['uses'=>'AdminController@send_email']);
		Route::post('reset_password', ['uses'=>'AdminController@reset_password']);

		
		Route::group(['middleware' => 'auth:admin-api'], function () {
			Route::get('search', ['uses'=>'AdminController@search']);
			Route::get('detail', ['uses'=>'AdminController@detail']);
			Route::post('add', ['uses'=>'AdminController@add']);
			Route::post('edit', ['uses'=>'AdminController@edit']);
			Route::post('delete', ['uses'=>'AdminController@delete']);
			Route::post('logout', ['uses'=>'AdminController@logout']);

			Route::group(['prefix' => 'store'], function () {
				Route::get('list', ['uses'=>'StoreController@index']);
				Route::get('search', ['uses'=>'StoreController@search']);
				Route::get('detail', ['uses'=>'StoreController@detail']);
				Route::post('add', ['uses'=>'StoreController@add']);
				Route::post('edit', ['uses'=>'StoreController@edit']);
				Route::post('delete', ['uses'=>'StoreController@delete']);
			});
			Route::group(['prefix' => 'staff'], function () {
				Route::get('list', ['uses'=>'StaffController@index']);
				Route::get('search', ['uses'=>'StaffController@search']);
				Route::get('detail', ['uses'=>'StaffController@detail']);
				Route::post('add', ['uses'=>'StaffController@add']);
				Route::post('edit', ['uses'=>'StaffController@edit']);
				Route::post('delete', ['uses'=>'StaffController@delete']);
			});	

			Route::group(['prefix' => 'customer'], function () {
	            Route::get('search',['uses'=>'CustomerController@search_by_admin']);
	            Route::get('detail',['uses'=>'CustomerController@detail_by_admin']);
	            Route::get('detail_list',['uses'=>'CustomerController@detail_list']);
	            Route::post('update',['uses'=>'CustomerController@update']);
	            Route::put('del',['uses'=>'CustomerController@del']);
	            Route::put('status',['uses'=>'CustomerController@status']);
            	Route::put('staff_id',['uses'=>'CustomerController@staff_id']);
			});			
		});
	});
	
	Route::group(['prefix' => 'staff'], function () {
		Route::post('login', ['uses'=>'StaffController@login']);
        Route::post('send_email',['uses'=>'StaffController@send_reset_password_email']);
        Route::post('reset_password',['uses'=>'StaffController@reset_password']);
		
		Route::group(['middleware' => 'auth:staff-api'], function () {
			Route::post('logout', ['uses'=>'StaffController@logout']);
            Route::get('customer/search',['uses'=>'CustomerController@search_by_store']);
            Route::get('customer/detail_list',['uses'=>'CustomerController@detail_list']);
            Route::get('customer/detail',['uses'=>'CustomerController@detail']);
            Route::post('customer/add',['uses'=>'CustomerController@add']);
            Route::post('customer/update',['uses'=>'CustomerController@update']);
            Route::put('customer/del',['uses'=>'CustomerController@del']);
            Route::put('customer/status',['uses'=>'CustomerController@status']);
            Route::put('customer/staff_id',['uses'=>'CustomerController@staff_id']);
		});
		Route::post('visitingcard/add',['uses'=>'VisitingCardController@add']);
		Route::post('visitingcard/addCardInfo',['uses'=>'VisitingCardController@addCardInfo']);
	});

	Route::group(['prefix' => 'advisor'], function () {
		Route::post('login', ['uses'=>'AdvisorController@login']);
        Route::post('send_email',['uses'=>'AdvisorController@send_reset_password_email']);
        Route::post('reset_password',['uses'=>'AdvisorController@reset_password']);
		
		Route::group(['middleware' => 'auth:advisor-api'], function () {
			Route::post('logout', ['uses'=>'AdvisorController@logout']);
			Route::group(['prefix' => 'step1'], function () {
				Route::get('search', ['uses'=>'StepOneController@search']);
				Route::get('detail', ['uses'=>'StepOneController@detail']);
				Route::post('edit', ['uses'=>'StepOneController@edit']);
			});	
			Route::group(['prefix' => 'step2'], function () {
				Route::get('search', ['uses'=>'StepTwoController@search']);
				Route::get('detail', ['uses'=>'StepTwoController@detail']);
				Route::post('edit', ['uses'=>'StepTwoController@edit']);
			});	
			Route::group(['prefix' => 'step3'], function () {
				Route::get('search', ['uses'=>'StepThreeController@search']);
				Route::get('detail', ['uses'=>'StepThreeController@detail']);
				Route::post('edit', ['uses'=>'StepThreeController@edit']);
			});	
			Route::group(['prefix' => 'step4'], function () {
				Route::get('search', ['uses'=>'StepFourController@search']);
				Route::get('detail', ['uses'=>'StepFourController@detail']);
				Route::post('edit', ['uses'=>'StepFourController@edit']);
			});	
			Route::group(['prefix' => 'step5'], function () {
				Route::get('search', ['uses'=>'StepFiveController@search']);
				Route::get('detail', ['uses'=>'StepFiveController@detail']);
				Route::post('edit', ['uses'=>'StepFiveController@edit']);
			});	

			Route::get('type3', ['uses'=>'TypeDiagnosesController@type3']);
			Route::get('type12', ['uses'=>'TypeDiagnosesController@type12']);
			Route::get('type12surface', ['uses'=>'TypeDiagnosesController@type12surface']);
			Route::get('type12intent', ['uses'=>'TypeDiagnosesController@type12intent']);
			Route::get('type60', ['uses'=>'TypeDiagnosesController@type60']);

		});
	});
});
