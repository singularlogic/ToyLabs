<?php

namespace App\Providers\Anlzer\Api;
use GuzzleHttp\ClientInterface;


class Api
{
    protected $HttpClient;
    protected $apiUrlPath;
    protected $apiUrlBase;

    public function __construct(ClientInterface $client, $apiUrlBase)
    {
        $this->httpClient = $client;
        $this->apiUrlBase = $apiUrlBase;
    }

    public function getBaseURL()
    {
        return $this->apiUrlBase.$this->apiUrlPath;
    }

    public function create($data)
    {
        $res = $this->httpClient->request('POST', $this->getBaseURL(), [
            'json' => $data
        ]);
        return json_decode($res->getBody());
    }

    public function update($id, $data)
    {
        $res = $this->httpClient->request('PUT', $this->getBaseURL().$id.'/', [
            'json' => $data
        ]);
        return json_decode($res->getBody());
    }

    public function delete($id)
    {
        $res = $this->httpClient->request('DELETE', $this->getBaseURL().$id.'/');
        return json_decode($res->getBody());
    }
}