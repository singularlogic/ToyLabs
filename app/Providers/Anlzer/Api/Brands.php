<?php

namespace App\Providers\Anlzer\Api;


class Brands extends Api
{
    protected $apiUrlPath = "/brands/";
    public function list()
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL());
        return json_decode($res->getBody());
    }

    public function getById($id)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/');
        return json_decode($res->getBody());
    }
}