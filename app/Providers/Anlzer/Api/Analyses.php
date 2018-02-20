<?php

namespace App\Providers\Anlzer\Api;


class Analyses extends Api
{
    protected $apiUrlPath = "/analyses/";
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

    public function getTopHashtagsById($id)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/top-hashtags/');
        return json_decode($res->getBody());
    }

    public function getTopAccountsById($id)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/top-accounts/');
        return json_decode($res->getBody());
    }

    public function getOverallTimelinesById($id)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/overall-timeline/');
        return json_decode($res->getBody());
    }

    public function getTimelinesById($id)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/timelines/');
        return json_decode($res->getBody());
    }

    public function getTwoWordPhrasesById($id)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/two-word-phrases/');
        return json_decode($res->getBody());
    }

    public function getConseptTimelinesById($id)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/concept-timelines/');
        return json_decode($res->getBody());
    }

    public function getParametersById($id)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/parameters/');
        return json_decode($res->getBody());
    }

    public function getConceptsParametersById($id, $parameterId)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/facet/?parameter='.$parameterId);
        return json_decode($res->getBody());
    }
}