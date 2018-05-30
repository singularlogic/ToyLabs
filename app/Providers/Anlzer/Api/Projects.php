<?php

namespace App\Providers\Anlzer\Api;


use GuzzleHttp\ClientInterface;

class Projects extends Api
{
    protected $apiUrlPath = "/projects/";
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

    public function listAnalysesById($id)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/analyses/');
        return json_decode($res->getBody());
    }

    public function listFeedbacksById($id)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/feedbacks/');
        return json_decode($res->getBody());
    }

    public function listConceptsById($id)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/concepts/');
        return json_decode($res->getBody());
    }
}