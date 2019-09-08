<?php

namespace App\Donors;

use App\Http\Controllers\LoggerController;
use App\Models\ProxyList;
use ParseIt\_String;
use ParseIt\nokogiri;
use App\Donors\ParseIt\simpleParser;
use ParseIt\ParseItHelpers;

Class Template extends simpleParser {

    public $data = [];
    public $reload = [];
    public $project = 'alloshow.ru';
    public $project_link = 'alloshow.ru';
    public $source = 'http://alloshow.ru/afisha';
    public $cache = false;
    public $proxy = false;
    public $cookieFile = '';
    public $version_id = 2;
    public $donor = 'AlloshowRu';

    function __construct()
    {
        $this->cookieFile = __DIR__.'/cookie/'.class_basename(get_class($this)).'/'.class_basename(get_class($this)).'.txt';
    }

    public function getSources($source = [])
    {
        $source['useContentCacheOnDate'] = $this->cache;
        $source['saveContentCache'] = $this->cache;
        $events = false;
        foreach ( [
                      $this->source,
                  ] as $url )
        {
            $last_page = 1;
            $page = 1;
            do
            {
//                $url = 'https://spb.ticketland.ru/musical/';
                $content = $this->loadUrl($url."?page=$page", $source);
                print_r($content);die();
                if ( !$content )
                {
                    return $events;
                }
                $content = ParseItHelpers::fixEncoding($content);
                $content = ParseItHelpers::fixHeader($content);

                $nokogiri = new nokogiri($content);
                $page++;
                $links = @$nokogiri->get("div.event .box a.image")->toArray();
                $images = @$nokogiri->get("div.event .box a.image img")->toArray();
                $titles = @$nokogiri->get("div.event .title")->toArray();
                $locations = @$nokogiri->get("div.event a.venue")->toArray();
                foreach ( $links as $k => $link )
                {
                    $link_event = $this->fixUrl($link['href']);
                    $event_name = trim(@$titles[$k]['__ref']->nodeValue);
                    $location = trim(@$locations[$k]['__ref']->nodeValue);
                    $date_event = '';
                    $image = $this->fixUrl(@$images[$k]['src']);
                    $hash = md5($link_event.$event_name);
                    $events[$hash]= [
                        'event_hash' => $hash,
                        'event' => $event_name,
                        'location' => $location,
                        'date_event' => $date_event,
                        'image' => $image,
                        'source' => $link_event,
                        'donor_class_name' => $this->donor,
                        'city' => $this->version_id,
                    ];
                }

                if ( $pager = $nokogiri->get("div.pager a")->toArray() )
                {
                    $last_page = _String::parseNumber($pager[count($pager)-1]['__ref']->nodeValue);
                }
                unset( $nokogiri, $content, $pager );
            }
            while( $page <= $last_page );
        }

        return $events;
    }

    public function getData($url, $source = [])
    {
        $data = false;
        $source['useContentCacheOnDate'] = $this->cache;
        $source['saveContentCache'] = $this->cache;
        $source['referer'] = $url;
        $content = $this->loadUrl($url, $source);
        if ( !$content )
        {
            return false;
        }
        $content = ParseItHelpers::fixEncoding($content);
        $content = ParseItHelpers::fixHeader($content);
        $nikogiri = new nokogiri($content);
        $event_name = trim($nikogiri->get(".content h1")->toArray()[0]['__ref']->nodeValue);
        $date_event = @$nikogiri->get("#event .info li.dte")->toArray()[0]['#text'];
        $date_event = $this->getDateTime(explode(',', $date_event[0])[0], preg_replace("%[^\d\:]*%uis", '', $date_event[1]));
        $location = trim(@$nikogiri->get("#event .info li.vne")->toArray()[0]['__ref']->nodeValue);
        $hash = md5($url.$event_name);
        $data[$hash]= [
            'event_hash' => $hash,
            'event' => @$event_name,
            'location' => @$location,
            'date_event' => @$date_event,
            'source' => @$url,
            'donor_class_name' => $this->donor,
            'city' => $this->version_id,
            'tickets' => [],
        ];
        $sectors = @$nikogiri->get("#sectors .sector")->toArray();
        $event_id = _String::parseNumber(trim(@$nikogiri->get("#order")->toArray()[0]['data-event']));
        if ( !$event_id )
        {
            return $data;
        }
        $sectors_name = $nikogiri->get("#sectors .sector .name")->toArray();
        $tickets = [];
        if ( !empty( $sectors ) )
        {
            foreach ( $sectors as $k => $row )
            {
                $sector_id = trim($sectors[$k]['data-id']);
                $mapload = "http://{$this->project}/ajax?get=map.load&event_id=$event_id&sector_id=$sector_id";
                $content = $this->loadUrl($mapload, $source);
                if ( !$content )
                {
                    return false;
                }
                $sector_name = trim($sectors_name[$k]['__ref']->nodeValue);
                $content = ParseItHelpers::fixEncoding($content);
                $content = ParseItHelpers::fixHeader($content);
                $map = new nokogiri($content);
                $seats = @$map->get(".places .ticket span")->toArray();
                if ( !empty($seats) )
                {
                    foreach ( $seats as $seat )
                    {
                        $str = explode(',', trim($seat['#text'][1]));
                        if ( count($str) == 2 )
                        {
                            $line = _String::parseNumber($str[0]);
                            $place = _String::parseNumber($str[1]);
                        }
                        else
                        {
                            $place = _String::parseNumber($str[0]);
                        }
                        if ( empty($line) && empty($place) )
                        {
                            $line = 1;
                            $place = 1;
                        }
                        $price = _String::parseNumber($seat['#text'][2]);
                        $tickets[] = [
                            'sector' => @$sector_name,
                            'line' => @$line,
                            'place' => @$place,
                            'price' => @$price,
                        ];
                    }
                }
                $name = @$map->get(".admission .sector .name")->toArray();
                $prices = @$map->get(".admission .sector .price")->toArray();
                $button = @$map->get(".admission .sector .cart button")->toArray();
                if (!empty($name))
                {
                    foreach ( $name as $l => $val )
                    {
                        if ( isset($button[$l]['__ref']->nodeValue) && $button[$l]['__ref']->nodeValue === "Добавить" )
                        {
                            $sector_name = trim(@$name[$l]['__ref']->nodeValue);
                            $price = _String::parseNumber(@$prices[$l]['__ref']->nodeValue);
                            $line = 1;
                            $place = 1;
                            $tickets[] = [
                                'sector' => @$sector_name,
                                'line' => @$line,
                                'place' => @$place,
                                'price' => @$price,
                            ];
                        }
                    }
                }
            }
        }
        else
        {
            $sector_id = 0;
            $mapload = "http://{$this->project}/ajax?get=map.load&event_id={$event_id}&sector_id={$sector_id}";
            $content = $this->loadUrl($mapload, $source);
            if ( !$content )
            {
                return false;
            }
            $content = ParseItHelpers::fixEncoding($content);
            $content = ParseItHelpers::fixHeader($content);
            $map = new nokogiri($content);
            $seats = @$map->get(".places .ticket span")->toArray();
            if ( !empty($seats) )
            {
                foreach ( $seats as $k => $seat )
                {
                    $sector_name = trim($seat['strong'][0]['#text']);
                    $price = _String::parseNumber($seat['#text'][2]);
                    $str = explode(',', trim($seat['#text'][1]));
                    if ( count($str) == 2 )
                    {
                        $line = _String::parseNumber($str[0]);
                        $place = _String::parseNumber($str[1]);
                    }
                    else
                    {
                        $place = _String::parseNumber($str[0]);
                    }
                    if ( empty($line) && empty($place) )
                    {
                        $line = 1;
                        $place = 1;
                    }
                    $tickets[] = [
                        'sector' => @$sector_name,
                        'line' => @$line,
                        'place' => @$place,
                        'price' => @$price,
                    ];
                }
            }
            $name = @$map->get(".admission .sector .name")->toArray();
            $prices = @$map->get(".admission .sector .price")->toArray();
            $button = @$map->get(".admission .sector .cart button")->toArray();
            if (!empty($name))
            {
                foreach ( $name as $k => $val )
                {
                    if ( isset($button[$k]['__ref']->nodeValue) && $button[$k]['__ref']->nodeValue === "Добавить" )
                    {
                        $sector_name = trim(@$name[$k]['__ref']->nodeValue);
                        $price = _String::parseNumber(@$prices[$k]['__ref']->nodeValue);
                        $line = 1;
                        $place = 1;
                        $tickets[] = [
                            'sector' => @$sector_name,
                            'line' => @$line,
                            'place' => @$place,
                            'price' => @$price,
                        ];
                    }
                }
            }
        }
        $data[$hash]['tickets'] = $tickets;

        return $data;
    }
}
