<?php

namespace App\Providers\Anlzer;

use App\Providers\Anlzer\Api\Projects;
use App\Providers\Anlzer\Api\Analyses;
use App\Providers\Anlzer\Api\Concepts;
use App\Providers\Anlzer\Api\Parameters;
use App\Providers\Anlzer\Api\Retrievers;
use GuzzleHttp\ClientInterface;


class AnlzerClient
{
    protected $settings;
    protected $httpClient;
    public function __construct(AnlzerServiceProviderSettings $settings, ClientInterface $client)
    {
        $this->settings = $settings;
        $this->httpClient  = $client;
    }

    public function getBaseURL()
    {
        return $this->settings->BASE_URL;
    }

    public function Projects()
    {
        return new Projects($this->httpClient, $this->getBaseURL());
    }

    public function Analyses()
    {
        return new Analyses($this->httpClient, $this->getBaseURL());
    }

    public function Concepts()
    {
        return new Concepts($this->httpClient, $this->getBaseURL());
    }

    public function Parameters()
    {
        return new Parameters($this->httpClient, $this->getBaseURL());
    }

    public function Retrievers()
    {
        return new Retrievers($this->httpClient, $this->getBaseURL());
    }
}