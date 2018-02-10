<?php

namespace App\Donors;

Class Template_Msk extends Template
{
    function __construct()
    {
        $this->donor = class_basename(get_class());
        $this->project = 'alloshow.ru';
        $this->project_link = 'alloshow.ru';
        $this->source = 'http://alloshow.ru/afisha/moscow';
        $this->version_id = 3;
        $this->cookieFile = __DIR__ . '/cookie/' . class_basename(get_class($this)) . '/' . class_basename(get_class($this)) . '.txt';
    }
}