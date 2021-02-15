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
Route::get('/parseit/rss_rf_ts_gost_pub', 'ParseitController@rss_rf_ts_gost_pub');
Route::get('/parseit/rss_pub_gost_r', 'ParseitController@rss_pub_gost_r');
Route::get('/parseit/rds_rf_pub', 'ParseitController@rds_rf_pub');
Route::get('/parseit/rds_ts_pub', 'ParseitController@rds_ts_pub');
Route::get('/parseit/rds_ts_pub_new', 'ParseitController@rds_ts_pub_new');
Route::get('/parseit/FgisGostRu', 'ParseitController@FgisGostRu');

Route::get('/parseit/TsouzBelgissBy', 'ParseitController@parseit_TsouzBelgissBy');
Route::get('/export-to-file/TsouzBelgissBy', 'ParseitController@exportToFileTsouzBelgissBy');
Route::get('/export-to-file/RdsTsPub', 'ParseitController@exportFromRdsTsPubToCSV');
Route::get('/export-to-file/RssTsPub', 'ParseitController@exportFromRssTsPubToCSV');

Route::get('/parseit/armnabAm_cert', 'ParseitController@armnabAm_cert');
Route::get('/parseit/armnabAm_certList', 'ParseitController@armnabAm_certList');
Route::get('/parseit/armnabAm_certListMode10', 'ParseitController@armnabAm_certListMode10');
Route::get('/parseit/armnabAm_laboratoryList', 'ParseitController@armnabAm_LaboratoryList');
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



Route::get('/import/sources', function(){
    \App\Models\Source::firstOrCreate([
        'donor_class_name' => 'Rao_rf_pub_new',
        'name' => 'Реестр аккредитованных лиц',
        'hash' => md5('https://pub.fsa.gov.ru/api/v1/ral/common/showcases/get'),
        'source' => 'https://pub.fsa.gov.ru/api/v1/ral/common/showcases/get',
        'version' => '1',
    ]);
    \App\Models\Source::firstOrCreate([
        'donor_class_name' => 'Rss_rf_pub',
        'name' => 'Единый реестр сертификатов соответствия',
        'hash' => md5('http://public.fsa.gov.ru/table_rss_pub_rf/'),
        'source' => 'http://public.fsa.gov.ru/table_rss_pub_rf/',
        'version' => '1',
    ]);
    \App\Models\Source::firstOrCreate([
        'donor_class_name' => 'Rss_ts_pub',
        'name' => 'Национальная часть единого реестра выданных сертификатов соответствия, оформленных по единой форме',
        'hash' => md5('http://public.fsa.gov.ru/table_rss_pub_ts/'),
        'source' => 'http://public.fsa.gov.ru/table_rss_pub_ts/',
        'version' => '1',
    ]);
    \App\Models\Source::firstOrCreate([
        'donor_class_name' => 'Rss_pub_gost_r',
        'name' => 'Реестр сертификатов соответствия продукции, включенной в Единый перечень продукции РФ',
        'hash' => md5('http://public.fsa.gov.ru/table_rss_pub_gost_r/'),
        'source' => 'http://public.fsa.gov.ru/table_rss_pub_gost_r/',
        'version' => '1',
    ]);
    \App\Models\Source::firstOrCreate([
        'donor_class_name' => 'Rds_rf_pub',
        'name' => 'Единый реестр деклараций о соответствии',
        'hash' => md5('http://public.fsa.gov.ru/table_rds_pub_rf/'),
        'source' => 'http://public.fsa.gov.ru/table_rds_pub_rf/',
        'version' => '1',
    ]);
    \App\Models\Source::firstOrCreate([
        'donor_class_name' => 'Rds_ts_pub_new',
        'name' => 'Национальная часть единого реестра зарегистрированных деклараций о соответствии, оформленных по единой форме',
        'hash' => md5('https://pub.fsa.gov.ru/api/v1/rds/common/declarations/get'),
        'source' => 'https://pub.fsa.gov.ru/api/v1/rds/common/declarations/get',
        'version' => '1',
    ]);
    \App\Models\Source::firstOrCreate([
        'donor_class_name' => 'Rss_rf_ts_gost_pub',
        'name' => 'Сертификаты соответствия',
        'hash' => md5('https://pub.fsa.gov.ru/api/v1/rss/common/certificates/get'),
        'source' => 'https://pub.fsa.gov.ru/api/v1/rss/common/certificates/get',
        'version' => '1',
    ]);
    \App\Models\Source::firstOrCreate([
        'donor_class_name' => 'Rds_pub_gost_r',
        'name' => 'Реестр деклараций о соответствии продукции, включенной в Единый перечень продукции РФ',
        'hash' => md5('http://public.fsa.gov.ru/table_rds_pub_gost_r/'),
        'source' => 'http://public.fsa.gov.ru/table_rds_pub_gost_r/',
        'version' => '1',
    ]);
    \App\Models\Source::firstOrCreate([
        'donor_class_name' => 'ArmnabAm_Cert',
        'name' => 'Национальный орган по аккредитации Армении',
        'hash' => md5('http://armnab.am/CertificationBodyListRU'),
        'source' => 'http://armnab.am/CertificationBodyListRU',
        'version' => '1',
    ]);
    \App\Models\Source::firstOrCreate([
        'donor_class_name' => 'ArmnabAm_CertList',
        'name' => 'Сертификаты соответствия',
        'hash' => md5('http://armnab.am/CertlistRU?mode=5'),
        'source' => 'http://armnab.am/CertlistRU?mode=5',
        'version' => '1',
    ]);
    \App\Models\Source::firstOrCreate([
        'donor_class_name' => 'ArmnabAm_CertListMode10',
        'name' => 'Декларации о соответствии',
        'hash' => md5('http://armnab.am/CertlistRU?mode=10'),
        'source' => 'http://armnab.am/CertlistRU?mode=10',
        'version' => '1',
    ]);
    \App\Models\Source::firstOrCreate([
        'donor_class_name' => 'ArmnabAm_LaboratoryList',
        'name' => 'Испытательные лаборатории',
        'hash' => md5('http://armnab.am/LaboratoryListRU'),
        'source' => 'http://armnab.am/LaboratoryListRU',
        'version' => '1',
    ]);
    \App\Models\Source::firstOrCreate([
        'donor_class_name' => 'TsouzBelgissBy',
        'name' => 'Сертификаты соответствия и декларации о соответствии',
        'hash' => md5('https://tsouz.belgiss.by/#!/tsouz/certifs'),
        'source' => 'https://tsouz.belgiss.by/#!/tsouz/certifs',
        'version' => '1',
    ]);
});
