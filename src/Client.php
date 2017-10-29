<?php namespace PCRecruiter;

/**
 * @author Paul Rock <paul@drteam.rocks>
 * @link http://drteam.rocks
 * @license MIT
 */

use \PCRecruiter\Interfaces\Client as ClientInterface;

/**
 * Class PCRecruiter for work with PCRecruiter RESTful API {@link https://www.pcrecruiter.net/apidocs_v2/}
 */
class Client implements ClientInterface
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $_client;

    /**
     * Main configuration
     * @var array
     */
    private $_config = [];

    /**
     * Token for queries
     * @var string
     */
    private $token;

    /**
     * Client constructor.
     * @param null|string $token
     */
    public function __construct($token = null)
    {
        // If incoming token is not empty
        if (!empty($token))
            // Set the token
            $this->setToken($token);

        // Store the client object
        $this->_client = new \GuzzleHttp\Client();
    }

    /**
     * Parse the incoming config
     *
     * @param   string $file
     * @param   bool $autoload
     * @return  object $this
     * @throws  \Exception
     */
    public function setConfig($file, $autoload = true)
    {
        if (file_exists($file)) {
            // Store array into the variable
            $this->_config = require_once $file;
            // Load the parameters
            if ($autoload === true) {
                // Read array and store into values
                foreach ($this->_config as $key => $value) $this->$key = $value;
            }
        } else {
            throw new \Exception("File $file not found.");
        }

        return $this;
    }

    /**
     * Get the array of parameters
     *
     * @return mixed
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * Set the token for work
     *
     * @param $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Get the current token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Make the request and analyze the result
     *
     * @param   string $type Request method
     * @param   string $endpoint Api request endpoint
     * @param   array $parameters Array of parameters for query into API
     * @return  array|false Array with data or error, or False when something went fully wrong
     */
    public function doRequest($type, $endpoint, $parameters = [])
    {
        // Detect the default protocol
        $http_proto = self::useSSL ? 'https' : 'http';

        // Generate the URL for request
        $url = $http_proto . "://" . self::host . ":" . self::port . self::path . $endpoint;

        // Default headers
        $headers = array(
            'Accept' => 'application/json',
            'Authorization' => 'BEARER ' . $this->token
        );

        // If request is not a GET
        if ($type != 'get') {
            $key = key($parameters);
            $body = json_encode($parameters[$key]);
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
            // For phpunit tests
            case 'test':
                $headers += ['Content-Type' => 'application/json'];
                $result = array($url, compact('headers', 'body'));
                return $result;
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

                // Per page results count and page number
                case 'resultsperpage':
                case 'page':
                    $endpoint .= $value;
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

        // Return data
        return $endpoint;
    }

}
