<?php

namespace App\Providers\Anlzer\Api;


class Feedbacks extends Api
{
    protected $apiUrlPath = "/feedbacks/";
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

    public function getOneWordPhrasesBrById($id)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/one-word-phrases-br/');
        return json_decode($res->getBody());
    }

    public function getOneWordPhrasesPrById($id)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/one-word-phrases-pr/');
        return json_decode($res->getBody());
    }

    public function getTwoWordPhrasesBrById($id)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/two-word-phrases-br/');
        return json_decode($res->getBody());
    }

    public function getTwoWordPhrasesPrById($id)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/two-word-phrases-pr/');
        return json_decode($res->getBody());
    }

    public function getThreeWordPhrasesBrById($id)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/three-word-phrases-br/');
        return json_decode($res->getBody());
    }

    public function getThreeWordPhrasesPrById($id)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/three-word-phrases-pr/');
        return json_decode($res->getBody());
    }

    public function getBrandTimelinesById($id)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/brand_timelines/');
        return json_decode($res->getBody());
    }

    public function getProductTimelinesById($id)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/product_timelines/');
        return json_decode($res->getBody());
    }

    public function getSentimentsById($id)
    {
        $res = $this->httpClient->request('GET', $this->getBaseURL().$id.'/sentiments/');
        return json_decode($res->getBody());
    }
}