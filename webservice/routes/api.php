<?php

Route::group([
    'middleware' => ['cors', 'api'],
], function () {
    Route::post('/register', 'UserController@register');
    Route::post('/login', 'AuthController@login');
    Route::post('/refresh', 'AuthController@refreshToken');

    Route::group([
        'middleware' => 'jwt.auth',
    ], function () {
        Route::post('/auth/token/revoke', 'AuthController@revokeToken');

        Route::get('/categories/full-list', 'CategoriesController@fullList');

        Route::resource('/categories', 'CategoriesController', [
            'except' => ['create', 'edit'],
        ]);

        Route::resource('/notes', 'NotesController', [
            'except' => ['create', 'edit'],
        ]);

        Route::get('/me', 'UserController@show');
    });
});
