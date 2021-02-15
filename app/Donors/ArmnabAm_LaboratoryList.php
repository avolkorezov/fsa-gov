<?php

namespace App\Donors;

use App\Http\Controllers\LoggerController;
use App\Models\ProxyList;
use ParseIt\_String;
use ParseIt\nokogiri;
use App\Donors\ParseIt\simpleParser;
use ParseIt\ParseItHelpers;

Class ArmnabAm_LaboratoryList extends simpleParser {

    public $data = [];
    public $reload = [];
    public $project = 'armnab.am';
    public $project_link = 'https://armnab.am/';
    public $source = 'https://armnab.am/LaboratoryListRU';
    public $cache = false;
    public $proxy = false;
    public $cookieFile = '';
    public $version_id = 1;
    public $donor = 'ArmnabAm_LaboratoryList';
    protected $token = '';
    protected $session = '';

    function __construct()
    {
        $this->cookieFile = __DIR__.'/cookie/'.class_basename(get_class($this)).'/'.class_basename(get_class($this)).'.txt';
    }

    public function getSources($opt = [])
    {
        $sources = [];

        $opt['cookieFile'] = $this->cookieFile;

        $opt['headers'] = [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
            'Accept-Encoding: gzip, deflate',
            'Accept-Language:en-US,en;q=0.9,ru;q=0.8',
            'Content-Type: application/x-www-form-urlencoded'
        ];
        $opt['host'] = 'armnab.am';

        $content = $this->loadUrl($this->source, $opt);

//        $opt['returnHeader'] = 1;

        $opt['post'] = "{'Number':'ALL'}";
        $opt['ajax'] = true;
        $opt['json'] = true;
        $opt['origin'] = 'https://armnab.am';
        $opt['referer'] = 'https://armnab.am/LaboratoryListRU';
        $opt['timeout'] = 120;
        $opt['headers'] = [
            'Accept: application/json, text/javascript; q=0.01',
            'Accept-Encoding: gzip, deflate',
            'Accept-Language:en-US,en;q=0.9,ru;q=0.8',
            'Content-Type: application/json; charset=UTF-8',
            'X-Requested-With: XMLHttpRequest'
        ];

        $content = $this->loadUrl('https://armnab.am/LaboratoryRUService.asmx/GetObjects', $opt);

        if (!isset($content->d))
        {
            return [];
        }

        $items = json_decode($content->d);

        foreach ($items as $k => $item)
        {
            $href = "http://armnab.am/AP02T01RU_view?APNumber={$item->AP_NUMBER}";
            $hash = md5($href);
            $sources[$hash]= [
                'hash' => $hash,
                'name' => '',
                'source' => $href,
                'donor_class_name' => $this->donor,
                'version' => 2,
                'param' => [
                    'AP_NUMBER' => $item->AP_NUMBER,
                ]
            ];
        }

        return $sources;
    }


    public function getData($url, $source = [])
    {
        $data = false;

        $number = $source['param']['AP_NUMBER'];

        $source['cookieFile'] = $this->cookieFile;

        $source['headers'] = [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
//            'Accept-Encoding: gzip, deflate',
            'Accept-Language:en-US,en;q=0.9,ru;q=0.8',
//            'Content-Type: application/json; charset=UTF-8',
        ];
        $source['host'] = 'armnab.am';

        $content = $this->loadUrl($this->source, $source);

//        $source['returnHeader'] = 1;

        $source['post'] = "{'Number':'{$number}'}";
        $source['ajax'] = true;
        $source['json'] = true;
        $source['origin'] = 'https://armnab.am';
        $source['referer'] = 'http:s//armnab.am/LaboratoryListRU';
        $source['timeout'] = 120;
        $source['headers'] = [
            'Accept: application/json, text/javascript; q=0.01',
            'Accept-Encoding: gzip, deflate',
            'Accept-Language:en-US,en;q=0.9,ru;q=0.8',
            'Content-Type: application/json; charset=UTF-8',
            'X-Requested-With: XMLHttpRequest'
        ];

        $content = $this->loadUrl('https://armnab.am/LaboratoryRUService.asmx/GetObjects', $source);

        if (!isset($content->d))
        {
            return [];
        }

        $items = json_decode($content->d);
        if (!isset($items[0]))
        {
            return [];
        }
        $item = $items[0];
//        print_r($item);die('da');

        $data[] = [
            'AP_NUMBER' => $item->AP_NUMBER,
            'STATUS' => $item->Status,
            'HGM_NAME' => $item->HGM_NAME,
            'Addresses' => serialize($item->Addresses),
            'PHONE' => $item->PHONE,
            'FAX' => $item->FAX,
            'HGM_LEADER_NAME' => $item->HGM_LEADER_NAME,
            'HGM_LEADER_LASTNAME' => $item->HGM_LEADER_LASTNAME,
            'HGM_LEADER_FATHERNAME' => $item->HGM_LEADER_FATHERNAME,
            'HGMSCOPE_DETAILS' => $item->HGMSCOPE_DETAILS,
            'MMATGAA' => $item->MMATGAA,
            'SCOPE_EXTEND_DATE' => !empty($item->SCOPE_EXTEND_DATE) ? date('Y-m-d', strtotime($item->SCOPE_EXTEND_DATE)) : null,
            'SCOPE_EXTEND_CHANGES' => $item->SCOPE_EXTEND_CHANGES,
            'SCOPE_EXTEND_MMATGAA' => $item->SCOPE_EXTEND_MMATGAA,
            'SCOPE_REDUCTION_DATE' => !empty($item->SCOPE_REDUCTION_DATE) ? date('Y-m-d', strtotime($item->SCOPE_REDUCTION_DATE)) : null,
            'SCOPE_REDUCTION_CHANGES' => $item->SCOPE_REDUCTION_CHANGES,
            'SCOPE_REDUCTION_MMATGAA' => $item->SCOPE_REDUCTION_MMATGAA,
            'AC_NUMBER' => $item->AC_NUMBER,
            'AC_BLANKNUMBER' => $item->AC_BLANKNUMBER,
            'AC_DECISIONNUMBER' => $item->AC_DECISIONNUMBER,
            'AC_DECISIONDATE' => !empty($item->AC_DECISIONDATE) ? date('Y-m-d', strtotime($item->AC_DECISIONDATE)) : null,
            'AC_STARTDATE' => !empty($item->AC_STARTDATE) ? date('Y-m-d', strtotime($item->AC_STARTDATE)) : null,
            'AC_EXPIRATIONDATE' => !empty($item->AC_EXPIRATIONDATE) ? date('Y-m-d', strtotime($item->AC_EXPIRATIONDATE)) : null,
            'SCOPE_SUSPENSION_DATE' => !empty($item->SCOPE_SUSPENSION_DATE) ? date('Y-m-d', strtotime($item->SCOPE_SUSPENSION_DATE)) : null,
            'SCOPE_SUSPENSION_CHANGES' => $item->SCOPE_SUSPENSION_CHANGES,
            'SCOPE_STOPAGE_DATE' => !empty($item->SCOPE_STOPAGE_DATE) ? date('Y-m-d', strtotime($item->SCOPE_STOPAGE_DATE)) : null,
            'SCOPE_STOPAGE_CHANGES' => $item->SCOPE_STOPAGE_CHANGES,
            'AC_CHANGES' => $item->AC_CHANGES,
        ];
//        print_r($data);die();

        return $data;
    }
}