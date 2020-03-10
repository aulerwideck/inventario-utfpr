<?php
use Illuminate\Support\Facades\Input;
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
    return redirect('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/roles', 'HomeController@roles')->name('roles');

Route::group(['prefix' => 'inventory'], function(){
    Route::get('/new', 'InventoryController@create')->name('inventory.create')->middleware('permission:create inventories');
    Route::get('/{inventory}', 'InventoryController@show')->name('inventory.show')->middleware('permission:read inventories');
    Route::get('/{inventory}/edit', 'InventoryController@edit')->name('inventory.edit')->middleware('permission:update inventories');
    Route::get('/{inventory}/destroy', 'InventoryController@destroy')->name('inventory.destroy')->middleware('permission:delete inventories');
    Route::get('/{inventory}/relatories', 'InventoryController@relatories')->name('inventory.relatories')->middleware('permission:see relatories');
    Route::group(['prefix' => '{inventory}/relatory'], function() {
        Route::get('/geral', 'InventoryController@relatoryGeral')->name('inventory.relatory.geral');
        Route::get('/final', 'InventoryController@relatoryFinal')->name('inventory.relatory.final');
        Route::get('/duplicado', 'InventoryController@relatoryDuplicado')->name('inventory.relatory.duplicado');
        Route::get('/perdido', 'InventoryController@relatoryPerdido')->name('inventory.relatory.perdido');
        Route::get('/avariado', 'InventoryController@relatoryAvariado')->name('inventory.relatory.avariado');
        Route::get('/localizacao', 'InventoryController@relatoryLocalizacao')->name('inventory.relatory.localizacao');
        Route::get('/observacao', 'InventoryController@relatoryObservacao')->name('inventory.relatory.observacao');
        Route::get('/proep', 'InventoryController@relatoryProep')->name('inventory.relatory.proep');
        Route::get('/semPatrimonio', 'InventoryController@relatorySemPatrimonio')->name('inventory.relatory.semPatrimonio');
        Route::get('/listFinal', 'InventoryController@listFinal')->name('inventory.relatory.listFinal');
        Route::get('/listDuplicado', 'InventoryController@listDuplicado')->name('inventory.relatory.listDuplicado');
        Route::get('/listPerdido', 'InventoryController@listPerdido')->name('inventory.relatory.listPerdido');
        Route::get('/listAvariado', 'InventoryController@listAvariado')->name('inventory.relatory.listAvariado');
        Route::get('/listLocalizacao', 'InventoryController@listLocalizacao')->name('inventory.relatory.listLocalizacao');
        Route::get('/listObservacao', 'InventoryController@listObservacao')->name('inventory.relatory.listObservacao');
        Route::get('/listProep', 'InventoryController@listProep')->name('inventory.relatory.listProep');
        Route::get('/listSemPatrimonio', 'InventoryController@listSemPatrimonio')->name('inventory.relatory.listSemPatrimonio');

        Route::get('/search', 'InventoryController@search')->name('inventory.relatory.search');
    });

    Route::post('/', 'InventoryController@store')->name('inventory.store');
    Route::post('/{inventory}', 'InventoryController@update')->name('inventory.update');
});

Route::group(['prefix' => 'collect'], function(){
    Route::get('/ajax', 'CollectController@ajax');
    Route::get('/ajaxDualCollect', 'CollectController@ajaxDualCollect');
    Route::get('/{local}', 'CollectController@index')->name('collect.home');
    Route::get('/{inventory}/archive', 'CollectController@archive')->name('collect.archive');

    Route::post('/', 'CollectController@store')->name('collect.store');
    Route::post('/{inventory}', 'CollectController@storeArchive')->name('collect.store.archive');
    Route::post('/{collect}', 'CollectController@update')->name('collect.update');
});

Route::group(['prefix' => 'local'], function(){

    Route::get('/new/{inventory}', 'LocalController@create')->name('local.create')->middleware('permission:create locals');
    Route::get('/{local}/', 'LocalController@show')->name('local.show')->middleware('permission:read locals');
    Route::get('/{local}/edit', 'LocalController@edit')->name('local.edit')->middleware('permission:update locals');
    Route::get('/{local}/destroy', 'LocalController@destroy')->name('local.destroy')->middleware('permission:remove locals');
    Route::get('/{local}/collect', 'LocalController@collect')->name('local.collect')->middleware('permission:collect locals');
    Route::get('/{id_local}/listPatrimonies', 'LocalController@listPatrimonies')->name('local.listPatrimonies');
    Route::get('/{id_local}/listPatrimoniesCollecteds', 'LocalController@listPatrimoniesCollecteds')->name('local.listPatrimoniesCollecteds');

    Route::post('/', 'LocalController@store')->name('local.store');
    Route::post('/{local}', 'LocalController@update')->name('local.update');
});

Route::middleware(['auth', 'verified'])->group( function () {
    Route::get('user/search','UserController@search');
    Route::resource('user', 'UserController');

    Route::put('role/{role}/connect', ['as' => 'role.connect', 'uses' => 'RoleController@connect']);
    Route::resource('role', 'RoleController');

    Route::resource('permission', 'PermissionController');
});
