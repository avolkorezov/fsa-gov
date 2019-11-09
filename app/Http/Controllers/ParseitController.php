<?php

namespace App\Http\Controllers;

use App\Donors\Rao_rf_pub_new;
use App\Donors\Rds_pub_gost_r;
use App\Donors\Rds_rf_pub;
use App\Donors\Rds_ts_pub;
use App\Donors\Rds_ts_pub_new;
use App\Donors\Rss_pub_gost_r;
use App\Donors\Rss_rf_pub;
use App\Donors\Rss_rf_ts_gost_pub;
use App\Donors\Rss_ts_pub;
use App\Models\Datum;
use App\Models\RaoRfPub;
use App\Models\RdsPubGostR;
use App\Models\RdsRfPub;
use App\Models\RdsTsPub;
use App\Models\RssPubGostR;
use App\Models\RssRfPub;
use App\Models\RssTsPub;
use App\Models\Source;
use App\ParseIt;
use Illuminate\Http\Request;
use Activity;
use ParseIt\nokogiri;
Use Validator;
use \Curl\MultiCurl;

class ParseitController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only([
            'sources'
        ]);
    }

    /**
     * Показываем панель управления сбором данных.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ( \Auth::guest() )
        {
            return redirect('/login');
        }

        return view('parseit.index', [
            'parsing_info' => []
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return IlluminateHttpResponse
     */
    public function sources(Request $request)
    {
        $query = Source::query();
        if ( isset($request->donor_class_name) && !empty($request->donor_class_name) )
        {
            $query = $query->where(['donor_class_name' => $request->donor_class_name]);
        }
        if ( isset($request->source) && !empty($request->source) )
        {
            $query = $query->where(['source' => $request->source]);
        }
//        $query = $query->where(['available' => 1]);
        $versions = explode(',', env('DONOR_VERSION', ''));
        $version_list = config('parser.version');
        $version_id = [];
        foreach ( $versions as $version )
        {
            if ( isset( $version_list[$version] ) )
            {
                $version_id[$version] = $version_list[$version];
            }
        }
        $uri = preg_replace("%\?.*?$%uis", '', $request->getRequestUri());
        $version_name = explode('/', $uri);
        $version_name = $version_name[count($version_name)-1];
        $query = $query->where(['version' => @$version_id[$version_name]]);
        $donor_list = [];
        $donors = @config('parser.donors')[$version_name];
        if ( is_array($donors) )
        {
            foreach ( $donors as $donor => $class )
            {
                $donor_list[] = $donor;
            }
        }
        $sources = $query->paginate(20);

        return view('sources.view',[
            'version_name' => $version_name,
            'sources' => $sources,
            'donor_list' => $donor_list,
        ]);
    }

    public function getSources(Request $request)
    {
        $exec_time = env('RUN_TIME', 0);
        $start = time();
        @set_time_limit($exec_time);
        if (isset($request->reestr) && !empty($request->reestr))
        {
            if ( $source = Source::where('donor_class_name', 'like', $request->reestr)->first() )
            {
                $class = "App\\Donors\\{$source->donor_class_name}";
                $donor = new $class();
                $donor->cookieFile = ParserController::getCookieFileName($source->donor_class_name);
                $opt['cookieFile'] = $donor->cookieFile;
                $opt = $request->toArray();
                $sources = $donor->getSources($opt);
                print_r(count($sources));
                foreach ( $sources as $s )
                {
                    $validator = Validator::make($s, Source::rules());
                    if ($validator->fails())
                    {
                        $message = $validator->errors()->first();
                        LoggerController::logToFile($message, 'info', $s, true);
                    }
                    else
                    {
                        Source::saveOrUpdate($s);
                    }
                }
            }
            else
            {
                die($request->reestr . " - донор не найден");
            }
        }
        else
        {
            die("Неправелные параметры запроса");
        }
    }

    public function rao_rf_pub(Request $request)
    {
        $exec_time = env('RUN_TIME', 0);
        $start = time();
        @set_time_limit($exec_time);
        $donorClassName = 'Rao_rf_pub_new';
        $donor = new Rao_rf_pub_new();
        $donor->cookieFile = ParserController::getCookieFileName($donorClassName);
        $opt['cookieFile'] = $donor->cookieFile;
        // isset($request->only_new) ? Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2, 'parseit' => 1])->update(['available' => 0]) : '' ;
        do
        {
            $find = Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2, 'updated_at' => NULL])->first(); // в первую очередь новые
            if ( !$find )
            {
                $find = Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2])->first(); // если нет новых, обновляем старое
            }
            if ($find)
            {
                $find->update(['parseit' => 1, 'available' => 0]);
                $opt['param'] = unserialize($find->param);
                if ($rows = $donor->getData($find->source, $opt))
                {
                    foreach ($rows as $row)
                    {
                        $validator = Validator::make($row, RaoRfPub::rules());
                        if ($validator->fails())
                        {
                            $message = $validator->errors()->first();
                            LoggerController::logToFile($message, 'info', $row, true);
                        }
                        else
                        {
                            try
                            {
                                if ($model = RaoRfPub::where(['CERT_NUM' => $row['CERT_NUM']])->get()->first())
                                {
                                    $model->update($row);
                                }
                                else
                                {
                                    RaoRfPub::create($row);
                                }
                            }
                            catch (\Exception $e)
                            {

                            }
                        }
                    }
                }
            }
            else
            {
                die('Done');
            }
            if ($start < time() - $exec_time)
            {
                die('End exec time');
            }
        }
        while( true );
    }

    public function rss_rf_pub(Request $request)
    {
        $exec_time = env('RUN_TIME', 0);
        $start = time();
        @set_time_limit($exec_time);
        $donorClassName = 'Rss_rf_pub';
        $donor = new Rss_rf_pub();
        $donor->cookieFile = ParserController::getCookieFileName($donorClassName);
        $opt['cookieFile'] = $donor->cookieFile;
        // isset($request->only_new) ? Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2, 'parseit' => 1])->update(['available' => 0]) : '' ;
        do
        {
        	$find = Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2, 'updated_at' => NULL])->first(); // в первую очередь новые
        	if ( !$find ) 
        	{
        		$find = Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2])->first(); // если нет новых, обновляем старое
        	}
            if ($find)
            {
                $find->update(['parseit' => 1, 'available' => 0]);
                $opt['param'] = unserialize($find->param);
                if ($rows = $donor->getData($find->source, $opt))
                {
                    foreach ($rows as $row)
                    {
                        $validator = Validator::make($row, RssRfPub::rules());
                        if ($validator->fails())
                        {
                            $message = $validator->errors()->first();
                            LoggerController::logToFile($message, 'info', $row, true);
                        }
                        else
                        {
                            if ($model = RssRfPub::where(['CERT_NUM' => $row['CERT_NUM']])->get()->first())
                            {
                                $model->update($row);
                            }
                            else
                            {
                                RssRfPub::create($row);
                            }
                        }
                    }
                }
            }
            else
            {
                die('Done');
            }
            if ($start < time() - $exec_time)
            {
                die('End exec time');
            }
        }
        while( true );
    }

    public function rss_ts_pub(Request $request)
    {
        $exec_time = env('RUN_TIME', 0);
        $start = time();
        @set_time_limit($exec_time);
        $donorClassName = 'Rss_ts_pub';
        $donor = new Rss_ts_pub();
        $donor->cookieFile = ParserController::getCookieFileName($donorClassName);
        $opt['cookieFile'] = $donor->cookieFile;
        // isset($request->only_new) ? Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2, 'parseit' => 1])->update(['available' => 0]) : '' ;
        do
        {
        	$find = Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2, 'updated_at' => NULL])->first(); // в первую очередь новые
        	if ( !$find ) 
        	{
        		$find = Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2])->first(); // если нет новых, обновляем старое
        	}
            if ($find)
            {
                $find->update(['parseit' => 1, 'available' => 0]);
                $opt['param'] = unserialize($find->param);
                if ($rows = $donor->getData($find->source, $opt))
                {
                    foreach ($rows as $row)
                    {
                        $validator = Validator::make($row, RssTsPub::rules());
                        if ($validator->fails())
                        {
                            $message = $validator->errors()->first();
                            LoggerController::logToFile($message, 'info', $row, true);
                        }
                        else
                        {
                            // print_r($row);
                            if(empty(trim($row['cert_doc_issued-testing_lab-0-reg_number'])))
                            {
                                $row['cert_doc_issued-testing_lab-0-reg_number'] = RssTsPub::parse_cert_doc_issued_reg_number($row['cert_doc_issued-testing_lab-0-basis_for_certificate']);
                            }
                            // print_r($row);
                            if ($model = RssTsPub::where(['CERT_NUM' => $row['CERT_NUM']])->get()->first())
                            {
                                $model->update($row);
                            }
                            else
                            {
                                RssTsPub::create($row);
                            }
                        }
                    }
                }
            }
            else
            {
                die('Done');
            }
            if ($start < time() - $exec_time)
            {
                die('End exec time');
            }
        }
        while( true );
    }

    public function rss_pub_gost_r(Request $request)
    {
        $exec_time = env('RUN_TIME', 0);
        $start = time();
        @set_time_limit($exec_time);
        $donorClassName = 'Rss_pub_gost_r';
        $donor = new Rss_pub_gost_r();
        $donor->cookieFile = ParserController::getCookieFileName($donorClassName);
        $opt['cookieFile'] = $donor->cookieFile;
        // isset($request->only_new) ? Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2, 'parseit' => 1])->update(['available' => 0]) : '' ;
        do
        {
        	$find = Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2, 'updated_at' => NULL])->first(); // в первую очередь новые
        	if ( !$find ) 
        	{
        		$find = Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2])->first(); // если нет новых, обновляем старое
        	}
            if ($find)
            {
                $find->update(['parseit' => 1, 'available' => 0]);
                $opt['param'] = unserialize($find->param);
                if ($rows = $donor->getData($find->source, $opt))
                {
                    foreach ($rows as $row)
                    {
                        $validator = Validator::make($row, RssPubGostR::rules());
                        if ($validator->fails())
                        {
                            $message = $validator->errors()->first();
                            LoggerController::logToFile($message, 'info', $row, true);
                        }
                        else
                        {
                            if ($model = RssPubGostR::where(['CERT_NUM' => $row['CERT_NUM']])->get()->first())
                            {
                                $model->update($row);
                            }
                            else
                            {
                                RssPubGostR::create($row);
                            }
                        }
                    }
                }
            }
            else
            {
                die('Done');
            }
            if ($start < time() - $exec_time)
            {
                die('End exec time');
            }
        }
        while( true );
    }

    public function rss_rf_ts_gost_pub(Request $request)
    {
        $exec_time = env('RUN_TIME', 0);
        $start = time();
        @set_time_limit($exec_time);
        $donorClassName = 'Rss_rf_ts_gost_pub';
        $donor = new Rss_rf_ts_gost_pub();
        $donor->cookieFile = ParserController::getCookieFileName($donorClassName);
        $opt['cookieFile'] = $donor->cookieFile;
        // isset($request->only_new) ? Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2, 'parseit' => 1])->update(['available' => 0]) : '' ;
        do
        {
            $find = Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2, 'updated_at' => NULL])->first(); // в первую очередь новые
            if ( !$find )
            {
                $find = Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2])->first(); // если нет новых, обновляем старое
            }
            if ($find)
            {
                $opt['param'] = unserialize($find->param);
                if ($rows = $donor->getData($find->source, $opt))
                {
                    foreach ($rows as $row)
                    {
//                        print_r($row);
                        $validator = Validator::make($row, RssTsPub::rules());
                        if ($validator->fails())
                        {
                            $message = $validator->errors()->first();
                            LoggerController::logToFile($message, 'info', $row, true);
                        }
                        else
                        {
                            // print_r($row);
                            if(empty(trim($row['cert_doc_issued-testing_lab-0-reg_number'])))
                            {
                                $row['cert_doc_issued-testing_lab-0-reg_number'] = RssTsPub::parse_cert_doc_issued_reg_number($row['cert_doc_issued-testing_lab-0-basis_for_certificate']);
                            }
                            // print_r($row);
                            if ($model = RssTsPub::where(['CERT_NUM' => $row['CERT_NUM']])->get()->first())
                            {
                                $model->update($row);
                            }
                            else
                            {
                                RssTsPub::create($row);
                            }
                        }
                    }
                }
                $find->update(['parseit' => 1, 'available' => 0]);
//                break;
            }
            else
            {
                die('Done');
            }
            if ($start < time() - $exec_time)
            {
                die('End exec time');
            }
        }
        while( true );
    }

    public function rds_rf_pub(Request $request)
    {
        $exec_time = env('RUN_TIME', 0);
        $start = time();
        @set_time_limit($exec_time);
        $donorClassName = 'Rds_rf_pub';
        $donor = new Rds_rf_pub();
        $donor->cookieFile = ParserController::getCookieFileName($donorClassName);
        $opt['cookieFile'] = $donor->cookieFile;
        // isset($request->only_new) ? Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2, 'parseit' => 1])->update(['available' => 0]) : '' ;
        do
        {
        	$find = Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2, 'updated_at' => NULL])->first(); // в первую очередь новые
        	if ( !$find ) 
        	{
        		$find = Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2])->first(); // если нет новых, обновляем старое
        	}
            if ($find)
            {
                $find->update(['parseit' => 1, 'available' => 0]);
                $opt['param'] = unserialize($find->param);
                if ($rows = $donor->getData($find->source, $opt))
                {
                    foreach ($rows as $row)
                    {
                        $validator = Validator::make($row, RdsRfPub::rules());
                        if ($validator->fails())
                        {
                            $message = $validator->errors()->first();
                            LoggerController::logToFile($message, 'info', $row, true);
                        }
                        else
                        {
                            if ($model = RdsRfPub::where(['DECL_NUM' => $row['DECL_NUM']])->get()->first())
                            {
                                $model->update($row);
                            }
                            else
                            {
                                RdsRfPub::create($row);
                            }
                        }
                    }
                }
            }
            else
            {
                die('Done');
            }
            if ($start < time() - $exec_time)
            {
                die('End exec time');
            }
        }
        while( true );
    }

    public function rds_ts_pub(Request $request)
    {
        $exec_time = env('RUN_TIME', 0);
        $start = time();
        @set_time_limit($exec_time);
        $donorClassName = 'Rds_ts_pub';
        $donor = new Rds_ts_pub();
        $donor->cookieFile = ParserController::getCookieFileName($donorClassName);
        $opt['cookieFile'] = $donor->cookieFile;
        // isset($request->only_new) ? Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2, 'parseit' => 1])->update(['available' => 0]) : '' ;
        do
        {
        	$find = Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2, 'updated_at' => NULL])->first(); // в первую очередь новые
        	if ( !$find ) 
        	{
        		$find = Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2])->first(); // если нет новых, обновляем старое
        	}
            if ($find)
            {
                $find->update(['parseit' => 1, 'available' => 0]);
                $opt['param'] = unserialize($find->param);
                if ($rows = $donor->getData($find->source, $opt))
                {
                    foreach ($rows as $row)
                    {
                        $validator = Validator::make($row, RdsTsPub::rules());
                        if ($validator->fails())
                        {
                            $message = $validator->errors()->first();
                            LoggerController::logToFile($message, 'info', $row, true);
                        }
                        else
                        {
                            $row['cert_doc_issued-testing_lab-0-reg_number'] = RdsTsPub::parse_cert_doc_issued_reg_number($row['cert_doc_issued-testing_lab-0-basis_for_certificate']);
                            if ($model = RdsTsPub::where(['DECL_NUM' => $row['DECL_NUM']])->get()->first())
                            {
                                $model->update($row);
                            }
                            else
                            {
                                RdsTsPub::create($row);
                            }
                        }
                    }
                }
            }
            else
            {
                die('Done');
            }
            if ($start < time() - $exec_time)
            {
                die('End exec time');
            }
        }
        while( true );
    }

    public function rds_ts_pub_new(Request $request)
    {
        $exec_time = env('RUN_TIME', 0);
        $start = time();
        @set_time_limit($exec_time);
        $donorClassName = 'Rds_ts_pub_new';
        $donor = new Rds_ts_pub_new();
        $donor->cookieFile = ParserController::getCookieFileName($donorClassName);
        $opt['cookieFile'] = $donor->cookieFile;
        // isset($request->only_new) ? Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2, 'parseit' => 1])->update(['available' => 0]) : '' ;
        do
        {
            $find = Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2, 'updated_at' => NULL])->first(); // в первую очередь новые
            if ( !$find )
            {
                $find = Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2])->first(); // если нет новых, обновляем старое
            }
            if ($find)
            {
                $opt['param'] = unserialize($find->param);
                try
                {
                    \sleep(1);
                    $rows = $donor->getData($find->source, $opt);
                }
                catch (\Exception $exception)
                {
                    print_r($exception->getMessage());die();
                }
                if (!empty($rows))
                {
                    foreach ($rows as $row)
                    {
//                        print_r($row);
                        $validator = Validator::make($row, RdsTsPub::rules());
                        if ($validator->fails())
                        {
                            $message = $validator->errors()->first();
                            LoggerController::logToFile($message, 'info', $row, true);
                        }
                        else
                        {
                            // $row['cert_doc_issued-testing_lab-0-reg_number'] = RdsTsPub::parse_cert_doc_issued_reg_number($row['cert_doc_issued-testing_lab-0-basis_for_certificate']);
                            if ($model = RdsTsPub::where(['DECL_NUM' => $row['DECL_NUM']])->get()->first())
                            {
                                $model->update($row);
                            }
                            else
                            {
                                RdsTsPub::create($row);
                            }
                        }
                    }
                }
                $find->update(['parseit' => 1, 'available' => 0]);
//                break;
            }
            else
            {
                die('Done');
            }
            if ($start < time() - $exec_time)
            {
                die('End exec time');
            }
        }
        while( true );
    }

    public function rds_pub_gost_r(Request $request)
    {
        $exec_time = env('RUN_TIME', 0);
        $start = time();
        @set_time_limit($exec_time);
        $donorClassName = 'Rds_pub_gost_r';
        $donor = new Rds_pub_gost_r();
        $donor->cookieFile = ParserController::getCookieFileName($donorClassName);
        $opt['cookieFile'] = $donor->cookieFile;
        // isset($request->only_new) ? Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2, 'parseit' => 1])->update(['available' => 0]) : '' ;
        do
        {
        	$find = Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2, 'updated_at' => NULL])->first(); // в первую очередь новые
        	if ( !$find ) 
        	{
        		$find = Source::where(['donor_class_name' => $donorClassName, 'available' => 1, 'version' => 2])->first(); // если нет новых, обновляем старое
        	}
            if ($find)
            {
                $find->update(['parseit' => 1, 'available' => 0]);
                $opt['param'] = unserialize($find->param);
                if ($rows = $donor->getData($find->source, $opt))
                {
                    foreach ($rows as $row)
                    {
                        $validator = Validator::make($row, RdsPubGostR::rules());
                        if ($validator->fails())
                        {
                            $message = $validator->errors()->first();
                            LoggerController::logToFile($message, 'info', $row, true);
                        }
                        else
                        {
                            if ($model = RdsPubGostR::where(['DECL_NUM' => $row['DECL_NUM']])->get()->first())
                            {
                                $model->update($row);
                            }
                            else
                            {
                                RdsPubGostR::create($row);
                            }
                        }
                    }
                }
            }
            else
            {
                die('Done');
            }
            if ($start < time() - $exec_time)
            {
                die('End exec time');
            }
        }
        while( true );
    }

    public function get_cert_num_ss_ts(Request $request)
    {
        foreach (RssTsPub::whereNotNull('cert_doc_issued-testing_lab-0-basis_for_certificate')->whereNotNull('cert_doc_issued-testing_lab-0-reg_number')->first()->get()  as $row )
        {
            $reg_number = RssTsPub::parse_cert_doc_issued_reg_number($row['cert_doc_issued-testing_lab-0-basis_for_certificate']);
            if (!empty( $reg_number ))
            {
                RssTsPub::where(['id' => $row->id])->update(['cert_doc_issued-testing_lab-0-reg_number' => $reg_number]);
            }
        }
    }

    public function get_cert_num_ds_ts(Request $request)
    {
        foreach (RdsTsPub::whereNotNull('cert_doc_issued-testing_lab-0-basis_for_certificate')->whereNotNull('cert_doc_issued-testing_lab-0-reg_number')->first()->get()  as $row )
        {
            $reg_number = RdsTsPub::parse_cert_doc_issued_reg_number($row['cert_doc_issued-testing_lab-0-basis_for_certificate']);
            if (!empty( $reg_number ))
            {
                RdsTsPub::where(['id' => $row->id])->update(['cert_doc_issued-testing_lab-0-reg_number' => $reg_number]);
            }
        }
    }

    public function cleanSourcesTable()
    {
        $time_range = time()-(24*60*60);
        print_r( ['вчера' => date('Y-m-d H:i:s', $time_range), 'сегодня' => date('Y-m-d H:i:s')]);
        Source::where(['updated_at' => NULL, 'created_at' => NULL])->delete();
        Source::whereNotNull('created_at')->where(['updated_at' => NULL])->where('created_at', '<' , date('Y-m-d H:i:s', $time_range))->delete();
        Source::whereNotNull('updated_at')->where('updated_at', '<' , date('Y-m-d H:i:s', $time_range))->delete();
        Source::where('date_event', '<' , date('Y-m-d H:i:s', $time_range))->delete();
    }

    public function parseitOffOn(Request $request)
    {
        @set_time_limit(0);
        $this->validate($request, [
            'hash' => 'required',
        ]);
        $results = ['error' => 'Источника нет'];
        if ( $model = Source::where(['hash' => $request->hash])->get()->first() )
        {
            Activity::log("Изменил статус parseit = '{$request->parseit}' у hash = '{$request->hash}'");
            $results = ['ok' => 'changed', 'change' =>$request->parseit];
            Source::where(['hash' => $request->hash])->update(['parseit' => isset($request->parseit) && $request->parseit === 'true' ? 1 : 0]);
            if ( $request->parseit != 'true' )
            {
                Datum::where(['hash' => $request->hash])->delete();
            }
        }

        $response = \Response::make($results, isset($results['error']) ? 500 : 200 );
        $response->header('Content-Type', 'text/json');

        return $response;
    }
}