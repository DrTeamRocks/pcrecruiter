<?php namespace PCRecruiter;
/**
 * @author Paul Rock <paul@drteam.rocks>
 * @link http://drteam.rocks
 * @license MIT
 */

use GuzzleHttp\Client;

/**
 * Class PCRecruiter for work with PCRecruiter RESTful API {@link https://www.pcrecruiter.net/apidocs_v2/}
 */
class Factory
{
    /**
     * Initial state of some variables
     */
    public $_client;
    public $_config;

    /**
     * Default server parameters
     */
    public $host = 'www.pcrecruiter.net';
    public $port = '443';
    public $path = '/rest/api';
    public $useSSL = true;

    /**
     * User initial values
     */
    public $token;
    public $database;
    public $username;
    public $password;
    public $app_id;
    public $app_key;

    /**
     * PCRecruiter constructor.
     */
    public function __construct()
    {
        $this->_client = new Client();
    }

    /**
     * Read the file with config
     *
     * @param   string $file Filename
     * @param   bool $autoload Automatically apply the configuration
     * @return  mixed
     */
    public function readConfig($file, $autoload = true)
    {
        if (file_exists($file)) {
            $this->_config = require_once $file;
            if ($autoload === true) $this->loadConfig();
            return $this->_config;
        } else {
            return false;
        }
    }

    /**
     * Parse the incoming config
     */
    public function loadConfig()
    {
        if (!empty($this->_config) && is_array($this->_config))
            foreach ($this->_config as $key => $value)
                $this->$key = $value;
    }

    /**
     * Make the request and analyze the result
     *
     * @param   string $type Request method
     * @param   string $endpoint Api request endpoint
     * @param   array $params Parameters
     * @return  array|false Array with data or error, or False when something went fully wrong
     */
    public function doRequest($type, $endpoint, $params = array())
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

        // If request is not a GET
        if ($type != 'get') {
            $key = key($params);
            $body = json_encode($params[$key]);
        }

        switch ($type) {
            case 'get':
                $result = $this->_client->get($url, compact('headers'));
                break;
            case 'post':
                $headers += ['Content-Type' => 'application/json'];
                $result = $this->_client->post($url, compact('headers', 'body'));
                break;
            case 'delete':
                $headers += ['Content-Type' => 'application/json'];
                $result = $this->_client->delete($url, compact('headers', 'body'));
                break;
            case 'put':
                $headers += ['Content-Type' => 'application/json'];
                $result = $this->_client->put($url, compact('headers', 'body'));
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

    /**
     * Check if array is multidimentoinal
     *
     * @param array $array
     * @return bool
     */
    public function isMulti(array $array)
    {
        $rv = array_filter($array, 'is_array');
        if (count($rv) > 0) return true;
        return false;
    }

    /**
     * Generate new URL from parameters
     *
     * @param array $parameters
     * @return string
     */
    public function compileURL(array $parameters)
    {
        // Initial endpoint
        $endpoint = '';

        // Check for multidementional array
        if ($this->isMulti($parameters)) {

            // Element of array
            $el = 0;

            // Generte url from parameters
            foreach ($parameters as $key => $value) {

                // Work mode
                $mode = mb_strtolower($key);

                // If we have parameters
                if ($el == 0) {
                    $endpoint .= '?' . ucfirst($mode) . '=';
                    $el++;
                } else {
                    $endpoint .= '&' . ucfirst($mode) . '=';
                }

                // Choose the work mode by key
                switch ($mode) {

                    // Create a search query
                    case 'query':
                        $step = 0;
                        foreach ($value as $key2 => $value2) {
                            if ($step != 0) $comma = ','; else $comma = null;
                            $endpoint .= $comma . $key2 . ' eq ' . $value2;
                            $step++;
                        }
                        break;

                    // Create a simple query
                    default:
                        $step = 0;
                        foreach ($value as $key2 => $value2) {
                            if ($step != 0) $comma = ','; else $comma = null;
                            $endpoint .= $comma . $value2;
                            $step++;
                        }
                        break;

                }

            }
        } else {
            // Init chart for endpoint
            $endpoint = '?Query=';
            // Then parse the array and create the url
            foreach ($parameters as $key => $value) $endpoint .= $key . ' eq ' . $value;
        }

        // Return data
        return $endpoint;
    }

}
