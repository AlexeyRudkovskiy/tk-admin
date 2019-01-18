<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 20.07.18
 * Time: 21:47
 */

Route::middleware('web')
    ->namespace('ARudkovskiy\\Admin\\Http\\Controllers')
    ->group(function () {
        Route::get('admin/signin', 'DashboardController@signIn')
            ->name('admin.signin');
        Route::post('admin/signin', 'DashboardController@signInCheck');
    });

Route::middleware([
    'web',
    \ARudkovskiy\Admin\Http\Middleware\AdminMiddleware::class
])
    ->prefix('admin')
    ->namespace('ARudkovskiy\\Admin\\Http\\Controllers')
    ->name('admin.')
    ->group(function() {
        Route::get('/', 'SimpleController@indexAction')->name('dashboard');
        Route::get('/config', 'ConfigController@index')->name('config.index');
        Route::post('/config', 'ConfigController@save')->name('config.save');

        Route::get('/sign-out', 'DashboardController@signOut')->name('sign_out');
        Route::get('/select2', 'DashboardController@select2')->name('select2');

        // CRUD stuff
        Route::prefix('entity')
            ->group(function () {
                Route::get('/', 'CrudController@index')->name('crud.index');
                Route::get('/create','CrudController@create')->name('crud.create');
                Route::get('/edit', 'CrudController@edit')->name('crud.edit');
                Route::post('/edit', 'CrudController@update')->name('crud.update');
                Route::post('/create', 'CrudController@save')->name('crud.save');
                Route::get('/toggle_boolean', 'CrudController@toggleBoolean')->name('crud.toggle_boolean');

                Route::get('/delete', 'CrudController@delete')->name('crud.delete');
            });

        Route::prefix('media')
            ->group(function () {
                Route::get('/', 'MediaController@index')->name('media.index');
                Route::post('/', 'MediaController@upload')->name('media.upload');
                Route::get('/{file}', 'MediaController@delete')->name('media.delete');
            });

        Route::prefix('files')
            ->group(function () {
                Route::get('/', 'FileManagerController@index')->name('files.index');
                Route::get('/directory', 'FileManagerController@getDirectoryInfo')->name('files.directory');
                Route::post('/delete', 'FileManagerController@delete')->name('files.delete');
                Route::post('/delete-folder', 'FileManagerController@deleteFolder')->name('files.delete_folder');
                Route::post('/create-folder', 'FileManagerController@createFolder')->name('files.create_folder');
                Route::post('/rename', 'FileManagerController@rename')->name('files.rename');
                Route::post('/upload', 'FileManagerController@upload')->name('files.upload');
            });

        Route::prefix('api')
            ->namespace('API')
            ->name('api.')
            ->group(function () {
                Route::get('/translation', 'TranslationController@translation')->name('translation');
                Route::post('search', 'SearchController@index')->name('search');
            });

    });
