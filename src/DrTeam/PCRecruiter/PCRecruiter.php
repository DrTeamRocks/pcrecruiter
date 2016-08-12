<?php namespace DrTeam\PCRecruiter;

use GuzzleHttp\Client;

class PCRecruiter
{
    /**
     * Initial state of some variables
     */
    protected $params = array();
    public $client;

    /**
     * Default server parameters
     */
    public $host = 'www.pcrecruiter.net';
    public $port = '443';
    public $path = '/rest/api';
    public $useSSL = true;

    /**
     * User initial states
     */
    public $token;
    public $database;
    public $user;
    public $pass;
    public $app;
    public $key;

    /**
     * Class Constructor
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Make the request and analyze the result
     *
     * @param   string $type Request method
     * @param   string $endpoint Api request endpoint
     * @param   array $params Parameters
     * @return  array|false Array with data or error, or False when something went fully wrong
     */
    private function doRequest($type, $endpoint, $params = array())
    {
        // Create the base URL
        $base = ($this->useSSL) ? "https" : "http";

        // Generate the URL for request
        $url = $base . "://" . $this->host . ":" . $this->port . $this->path . $endpoint;

        // Default headers
        $headers = array(
            'Accept' => 'application/json',
            'Authorization' => 'BEARER ' . $this->token
        );

        $body = json_encode($params);

        switch ($type) {
            case 'get':
                $result = $this->client->get($url, compact('headers'));
                break;
            case 'post':
                $headers += ['Content-Type' => 'application/json'];
                $result = $this->client->post($url, compact('headers', 'body'));
                break;
            case 'delete':
                $headers += ['Content-Type' => 'application/json'];
                $result = $this->client->delete($url, compact('headers', 'body'));
                break;
            case 'put':
                $headers += ['Content-Type' => 'application/json'];
                $result = $this->client->put($url, compact('headers', 'body'));
                break;
            default:
                $result = null;
                break;
        }

        if ($result->getStatusCode() == 200 || $result->getStatusCode() == 201) {
            return array('status' => true, 'message' => json_decode($result->getBody()));
        }
        return array('status' => false, 'message' => json_decode($result->getBody()));

    }

    public function getAccessToken()
    {
        $endpoint = '/access-token?'
            . 'DatabaseId=' . $this->database . '&'
            . 'Username=' . $this->user . '&'
            . 'Password=' . $this->pass . '&'
            . 'AppId=' . $this->app . '&'
            . 'ApiKey=' . $this->key;

        return $this->doRequest('get', $endpoint);
    }

    public function getPositions()
    {
        $endpoint = '/positions';
        return $this->doRequest('get', $endpoint);
    }

}
