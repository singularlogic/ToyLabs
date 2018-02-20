<?php

namespace App\Providers\Anlzer\Api;


class Concepts extends Api
{
    protected $apiUrlPath = "/concepts/";
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