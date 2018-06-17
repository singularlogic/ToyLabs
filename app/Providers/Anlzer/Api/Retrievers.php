<?php

namespace App\Providers\Anlzer\Api;


class Retrievers extends Api
{
    protected $apiUrlPath = "/retrievers/";
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

    public function getBrandsProductsById($id)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/brands-products/');
        return json_decode($res->getBody());
    }
}