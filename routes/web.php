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


Auth::routes();
Route::get('/login', 'Auth\LoginController@showLoginForm' );
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout');

Route::get('/', 'ParseitController@index');

Route::post('/parser/save-source', 'ParserController@saveSourcePost');
Route::get('/parseit/data', 'ParseitController@getData');
Route::get('/parseit/categories', 'ParseitController@categories');
Route::get('/parseit/products', 'ParseitController@products');
Route::get('/parseit/clean-sources', 'ParseitController@cleanSourcesTable');

Route::get('/parseit/get_cert_num_ss_ts', 'ParseitController@get_cert_num_ss_ts');
Route::get('/parseit/get_cert_num_ds_ts', 'ParseitController@get_cert_num_ds_ts');

Route::post('/logs/search', 'LoggerController@ajaxFilterLogs');
Route::get('/logs/{filename]', 'LoggerController@view');
Route::get('/process-log', 'LoggerController@processLog');
Route::resource('/logs', 'LoggerController');

Route::get('/proxy/check-next-proxy', 'ProxyController@checkNextProxy');
Route::resource('/proxy', 'ProxyController');

Route::resource('/useragent', 'UserAgentController');

Route::get('/sources/main', 'ParseitController@sources');
Route::post('/parseit-off-on', 'ParseitController@parseitOffOn');
Route::get('/test', 'ParserController@test');

Route::get('/parseit/sources', 'ParseitController@getSources');

Route::get('/parseit/rao_rf_pub', 'ParseitController@rao_rf_pub');
Route::get('/parseit/rss_rf_pub', 'ParseitController@rss_rf_pub');
Route::get('/parseit/rss_ts_pub', 'ParseitController@rss_ts_pub');
Route::get('/parseit/rss_pub_gost_r', 'ParseitController@rss_pub_gost_r');
Route::get('/parseit/rds_rf_pub', 'ParseitController@rds_rf_pub');
Route::get('/parseit/rds_ts_pub', 'ParseitController@rds_ts_pub');
Route::get('/parseit/rds_pub_gost_r', 'ParseitController@rds_pub_gost_r');

Route::get('/log-to-mail/off', function(){
    $environmentName = 'APP_LOGTOEMAIL';
    $configKey = 'parser.log_to_mail';
    file_put_contents(App::environmentFilePath(), str_replace(
        $environmentName . '=' . 'true',
        $environmentName . '=' . 'false',
        file_get_contents(App::environmentFilePath())
    ));

    Config::set($configKey, false);

    // Reload the cached config
    if (file_exists(App::getCachedConfigPath())) {
        Artisan::call("config:cache");
    }

    return 'Оповещения на почту отключены';
});

Route::get('/log-to-mail/on', function(){
    $environmentName = 'APP_LOGTOEMAIL';
    $configKey = 'parser.log_to_mail';
    file_put_contents(App::environmentFilePath(), str_replace(
        $environmentName . '=' . 'false',
        $environmentName . '=' . 'true',
        file_get_contents(App::environmentFilePath())
    ));

    Config::set($configKey, true);

    // Reload the cached config
    if (file_exists(App::getCachedConfigPath())) {
        Artisan::call("config:cache");
    }

    return 'Оповещения на почту включены';
});
