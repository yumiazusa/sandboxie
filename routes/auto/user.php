<?php

Route::get('/users', 'UserController@index')->name('user.index');
Route::get('/users/list', 'UserController@list')->name('user.list');
Route::get('/users/create', 'UserController@create')->name('user.create');
Route::get('/users/excel', 'UserController@excel')->name('user.excel');
Route::any('/users/addex', 'UserController@addex')->name('user.addex');
Route::post('/users', 'UserController@save')->name('user.save');
Route::get('/users/{id}/edit', 'UserController@edit')->name('user.edit');
Route::put('/users/{id}', 'UserController@update')->name('user.update');
Route::delete('/users/{id}', 'UserController@delete')->name('user.delete');
