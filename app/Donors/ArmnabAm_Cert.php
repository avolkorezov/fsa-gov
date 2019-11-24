<?php

namespace App\Donors;

use App\Http\Controllers\LoggerController;
use App\Models\ProxyList;
use ParseIt\_String;
use ParseIt\nokogiri;
use App\Donors\ParseIt\simpleParser;
use ParseIt\ParseItHelpers;

Class ArmnabAm_Cert extends simpleParser {

    public $data = [];
    public $reload = [];
    public $project = 'armnab.am';
    public $project_link = 'http://armnab.am/';
    public $source = 'http://armnab.am/CertificationBodyListRU#';
    public $cache = false;
    public $proxy = false;
    public $cookieFile = '';
    public $version_id = 1;
    public $donor = 'ArmnabAm_Cert';
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
//            'Accept-Encoding: gzip, deflate',
            'Accept-Language:en-US,en;q=0.9,ru;q=0.8',
//            'Content-Type: application/json; charset=UTF-8',
        ];
        $opt['host'] = 'armnab.am';

        $content = $this->loadUrl($this->source, $opt);

//        $opt['returnHeader'] = 1;

        $opt['post'] = "{'Number':'ALL'}";
        $opt['ajax'] = true;
        $opt['json'] = true;
        $opt['origin'] = 'http://armnab.am';
        $opt['referer'] = 'http://armnab.am/CertificationBodyListRU';
        $opt['headers'] = [
            'Accept: application/json, text/javascript; q=0.01',
            'Accept-Encoding: gzip, deflate',
            'Accept-Language:en-US,en;q=0.9,ru;q=0.8',
            'Content-Type: application/json; charset=UTF-8',
            'X-Requested-With: XMLHttpRequest'
        ];

        $content = $this->loadUrl('http://armnab.am/CertificationBodyRUService.asmx/GetObjects', $opt);

        if (!isset($content->d))
        {
            return [];
        }

        $items = json_decode($content->d);

        foreach ($items as $k => $item)
        {
            $href = "http://armnab.am/AP01T01RU_view?APNumber={$item->AP_NUMBER}";
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

//        $opt['returnHeader'] = 1;

        $source['post'] = "{'Number':'{$number}'}";
        $source['ajax'] = true;
        $source['json'] = true;
        $source['origin'] = 'http://armnab.am';
        $source['referer'] = 'http://armnab.am/CertificationBodyListRU';
        $source['headers'] = [
            'Accept: application/json, text/javascript; q=0.01',
            'Accept-Encoding: gzip, deflate',
            'Accept-Language:en-US,en;q=0.9,ru;q=0.8',
            'Content-Type: application/json; charset=UTF-8',
            'X-Requested-With: XMLHttpRequest'
        ];

        $content = $this->loadUrl('http://armnab.am/CertificationBodyRUService.asmx/GetObjects', $source);

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
            'SCOPE_EXTEND_DATE' => $item->SCOPE_EXTEND_DATE,
            'SCOPE_EXTEND_CHANGES' => $item->SCOPE_EXTEND_CHANGES,
            'SCOPE_EXTEND_MMATGAA' => $item->SCOPE_EXTEND_MMATGAA,
            'SCOPE_REDUCTION_DATE' => $item->SCOPE_REDUCTION_DATE,
            'SCOPE_REDUCTION_CHANGES' => $item->SCOPE_REDUCTION_CHANGES,
            'SCOPE_REDUCTION_MMATGAA' => $item->SCOPE_REDUCTION_MMATGAA,
            'AC_NUMBER' => $item->AC_NUMBER,
            'AC_BLANKNUMBER' => $item->AC_BLANKNUMBER,
            'AC_DECISIONNUMBER' => $item->AC_DECISIONNUMBER,
            'AC_DECISIONDATE' => $item->AC_DECISIONDATE,
            'AC_STARTDATE' => $item->AC_STARTDATE,
            'AC_EXPIRATIONDATE' => $item->AC_EXPIRATIONDATE,
            'SCOPE_SUSPENSION_DATE' => $item->SCOPE_SUSPENSION_DATE,
            'SCOPE_SUSPENSION_CHANGES' => $item->SCOPE_SUSPENSION_CHANGES,
            'SCOPE_STOPAGE_DATE' => $item->SCOPE_STOPAGE_DATE,
            'SCOPE_STOPAGE_CHANGES' => $item->SCOPE_STOPAGE_CHANGES,
            'AC_CHANGES' => $item->AC_CHANGES,
        ];
//        print_r($data);die();

        return $data;
    }
}