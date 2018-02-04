<?php

namespace App\Http\Controllers;

use App\Donors\Rao_rf_pub;
use App\Donors\Rds_pub_gost_r;
use App\Donors\Rds_rf_pub;
use App\Donors\Rds_ts_pub;
use App\Donors\Rss_pub_gost_r;
use App\Donors\Rss_rf_pub;
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

    public function rao_rf_pub(Request $request)
    {
        set_time_limit(0);
        $donorClassName = 'Rao_rf_pub';
        $donor = new Rao_rf_pub();
        $opt['cookieFile'] = $donor->cookieFile;
        $donor->cookieFile = ParserController::getCookieFileName($donorClassName);
        $currPage = 0;
        do
        {
            $nextPage = $currPage + 1;
            $opt['post'] = [
                'ajax' => 'main',
                'action' => 'search',
                'input_2_begin' => date('d.m.Y', time()-(31*24*60*60)),
//                'input_2_begin' => date('d.m.Y', 0),
                'input_2_end' => date('d.m.Y'),
                'html_id' => 'innertube',
                'ajaxId' => '2194787380',
                'page_noid_' => $currPage,
                'pageGoid_' => $nextPage,
                'sortid_' => 'DESC',
                'page_byid_' => 50
            ];
            $opt['referer'] = $donor->source;
            $opt['origin'] = 'http://188.254.71.82';
            $opt['host'] = '188.254.71.82';
            $content = $donor->loadUrl($donor->source, $opt);
            $content = preg_replace( "%windows-1251%is", 'UTF-8', $content );
            $content = iconv('windows-1251', 'UTF-8', $content);
            if ( preg_match('%checkedVal\((\d+)\)\;%uis', $content, $match) )
            {
                $countPage = $match[1];
                if ( $countPage-1 > $currPage )
                {
                    $currPage++;
                }
            }
            if ( $rows = $donor->getData($content, $donor->source, $opt) )
            {
//                print_r($rows);
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
//                break;
            }
        }
        while( $nextPage === $currPage );
    }

    public function rss_rf_pub(Request $request)
    {
        set_time_limit(0);
        $donorClassName = 'Rss_rf_pub';
        $donor = new Rss_rf_pub();
        $opt['cookieFile'] = $donor->cookieFile;
        $donor->cookieFile = ParserController::getCookieFileName($donorClassName);
        $currPage = 0;
        do
        {
            $nextPage = $currPage + 1;
            $opt['post'] = [
                'ajax' => 'main',
                'action' => 'search',
                'input_2_begin' => date('d.m.Y', time()-(31*24*60*60)),
//                'input_2_begin' => '01.01.2017',
                'input_2_end' => date('d.m.Y'),
                'ajaxId' => '8372942386',
                'page_noid_' => $currPage,
                'pageGoid_' => $nextPage,
                'sortid_' => 'DESC',
                'page_byid_' => 50
            ];
            $opt['referer'] = $donor->source;
            $opt['origin'] = 'http://188.254.71.82';
            $opt['host'] = '188.254.71.82';
            $content = $donor->loadUrl($donor->source, $opt);
            $content = preg_replace( "%windows-1251%is", 'UTF-8', $content );
            $content = iconv('windows-1251', 'UTF-8', $content);
            if ( preg_match('%checkedVal\((\d+)\)\;%uis', $content, $match) )
            {
                $countPage = $match[1];
                if ( $countPage-1 > $currPage )
                {
                    $currPage++;
                }
            }
            if ( $rows = $donor->getData($content, $donor->source, $opt) )
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
        while( $nextPage === $currPage );
    }

    public function rss_ts_pub(Request $request)
    {
        set_time_limit(0);
        $donorClassName = 'Rss_ts_pub';
        $donor = new Rss_ts_pub();
        $opt['cookieFile'] = $donor->cookieFile;
        $donor->cookieFile = ParserController::getCookieFileName($donorClassName);
        $currPage = 0;
        do
        {
            $nextPage = $currPage + 1;
            $opt['post'] = [
                'ajax' => 'main',
                'action' => 'search',
                'input_2_begin' => date('d.m.Y', time()-(31*24*60*60)),
//                'input_2_begin' => '01.01.2017',
                'input_2_end' => date('d.m.Y'),
                'ajaxId' => '4841306584',
                'page_noid_' => $currPage,
                'pageGoid_' => $nextPage,
                'sortid_' => 'DESC',
                'page_byid_' => 50
            ];
            $content = $donor->loadUrl($donor->source, $opt);
            $content = preg_replace( "%windows-1251%is", 'UTF-8', $content );
            $content = iconv('windows-1251', 'UTF-8', $content);
            if ( preg_match('%checkedVal\((\d+)\)\;%uis', $content, $match) )
            {
                $countPage = $match[1];
                if ( $countPage-1 > $currPage )
                {
                    $currPage++;
                }
            }
            if ( $rows = $donor->getData($content, $donor->source, $opt) )
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
                        $row['cert_doc_issued-testing_lab-0-reg_number'] = RssTsPub::parse_cert_doc_issued_reg_number($row['cert_doc_issued-testing_lab-0-basis_for_certificate']);
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
//            break;
        }
        while( $nextPage === $currPage );
    }

    public function rss_pub_gost_r(Request $request)
    {
        set_time_limit(0);
        $donorClassName = 'Rss_pub_gost_r';
        $donor = new Rss_pub_gost_r();
        $opt['cookieFile'] = $donor->cookieFile;
        $donor->cookieFile = ParserController::getCookieFileName($donorClassName);
        $currPage = 0;
        do
        {
            $nextPage = $currPage + 1;
            $opt['post'] = [
                'ajax' => 'main',
                'action' => 'search',
                'input_2_begin' => date('d.m.Y', time()-(31*24*60*60)),
//                'input_2_begin' => '01.01.2017',
                'input_2_end' => date('d.m.Y'),
                'ajaxId' => '6719478738',
                'page_noid_' => $currPage,
                'pageGoid_' => $nextPage,
                'sortid_' => 'DESC',
                'page_byid_' => 50
            ];
            $content = $donor->loadUrl($donor->source, $opt);
            $content = preg_replace( "%windows-1251%is", 'UTF-8', $content );
            $content = iconv('windows-1251', 'UTF-8', $content);
            if ( preg_match('%checkedVal\((\d+)\)\;%uis', $content, $match) )
            {
                $countPage = $match[1];
                if ( $countPage-1 > $currPage )
                {
                    $currPage++;
                }
            }
            if ( $rows = $donor->getData($content, $donor->source, $opt) )
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
//            break;
        }
        while( $nextPage === $currPage );
    }

    public function rds_rf_pub(Request $request)
    {
        set_time_limit(0);
        $donorClassName = 'Rds_rf_pub';
        $donor = new Rds_rf_pub();
        $opt['cookieFile'] = $donor->cookieFile;
        $donor->cookieFile = ParserController::getCookieFileName($donorClassName);
        $currPage = 0;
        do
        {
            $nextPage = $currPage + 1;
            $opt['post'] = [
                'ajax' => 'main',
                'action' => 'search',
                'input_2_begin' => date('d.m.Y', time()-(31*24*60*60)),
//                'input_2_begin' => '01.01.2017',
                'input_2_end' => date('d.m.Y'),
                'ajaxId' => '6719478738',
                'page_noid_' => $currPage,
                'pageGoid_' => $nextPage,
                'sortid_' => 'DESC',
                'page_byid_' => 50
            ];
            $content = $donor->loadUrl($donor->source, $opt);
            $content = preg_replace( "%windows-1251%is", 'UTF-8', $content );
            $content = iconv('windows-1251', 'UTF-8', $content);
            if ( preg_match('%checkedVal\((\d+)\)\;%uis', $content, $match) )
            {
                $countPage = $match[1];
                if ( $countPage-1 > $currPage )
                {
                    $currPage++;
                }
            }
            if ( $rows = $donor->getData($content, $donor->source, $opt) )
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
//            break;
        }
        while( $nextPage === $currPage );
    }

    public function rds_ts_pub(Request $request)
    {
        set_time_limit(0);
        $donorClassName = 'Rds_ts_pub';
        $donor = new Rds_ts_pub();
        $opt['cookieFile'] = $donor->cookieFile;
        $donor->cookieFile = ParserController::getCookieFileName($donorClassName);
        $currPage = 0;
        do
        {
            $nextPage = $currPage + 1;
            $opt['post'] = [
                'ajax' => 'main',
                'action' => 'search',
                'input_2_begin' => date('d.m.Y', time()-(31*24*60*60)),
//                'input_2_begin' => '01.01.2017',
                'input_2_end' => date('d.m.Y'),
                'ajaxId' => '3004715364',
                'page_noid_' => $currPage,
                'pageGoid_' => $nextPage,
                'sortid_' => 'DESC',
                'page_byid_' => 50
            ];
            $content = $donor->loadUrl($donor->source, $opt);
            $content = preg_replace( "%windows-1251%is", 'UTF-8', $content );
            $content = iconv('windows-1251', 'UTF-8', $content);
            if ( preg_match('%checkedVal\((\d+)\)\;%uis', $content, $match) )
            {
                $countPage = $match[1];
                if ( $countPage-1 > $currPage )
                {
                    $currPage++;
                }
            }
            if ( $rows = $donor->getData($content, $donor->source, $opt) )
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
//            break;
        }
        while( $nextPage === $currPage );
    }

    public function rds_pub_gost_r(Request $request)
    {
        set_time_limit(0);
        $donorClassName = 'Rds_pub_gost_r';
        $donor = new Rds_pub_gost_r();
        $opt['cookieFile'] = $donor->cookieFile;
        $donor->cookieFile = ParserController::getCookieFileName($donorClassName);
        $currPage = 0;
        do
        {
            $nextPage = $currPage + 1;
            $opt['post'] = [
                'ajax' => 'main',
                'action' => 'search',
                'input_2_begin' => date('d.m.Y', time()-(31*24*60*60)),
//                'input_2_begin' => '01.01.2017',
                'input_2_end' => date('d.m.Y'),
                'ajaxId' => '3004715364',
                'page_noid_' => $currPage,
                'pageGoid_' => $nextPage,
                'sortid_' => 'DESC',
                'page_byid_' => 50
            ];
            $content = $donor->loadUrl($donor->source, $opt);
            $content = preg_replace( "%windows-1251%is", 'UTF-8', $content );
            $content = iconv('windows-1251', 'UTF-8', $content);
            if ( preg_match('%checkedVal\((\d+)\)\;%uis', $content, $match) )
            {
                $countPage = $match[1];
                if ( $countPage-1 > $currPage )
                {
                    $currPage++;
                }
            }
            if ( $rows = $donor->getData($content, $donor->source, $opt) )
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
//            break;
        }
        while( $nextPage === $currPage );
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
        set_time_limit(0);
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