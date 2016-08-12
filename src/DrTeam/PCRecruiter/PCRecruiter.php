<?php
/**
 * @author Pavel Rykov <paul@drteam.rocks>
 * @link http://drteam.rocks
 * @license MIT
 */

namespace DrTeam\PCRecruiter;

use GuzzleHttp\Client;

/**
 * Class PCRecruiter for work with PCRecruiter Rest API {@link https://www.pcrecruiter.net/apidocs_v2/}
 * @package DrTeam\PCRecruiter
 */
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
     * User initial values
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

        // JSON body of the request
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



    /**
     * Access Tokens
     */

    /**
     * Retrieve a valid api session
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/access-token.json
     * @return  array|false
     */
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

    /**
     * Log user off
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/access-token.json
     * @return  array|false
     */
    public function deleteAccessToken()
    {
        $endpoint = '/access-token';
        return $this->doRequest('delete', $endpoint);
    }



    /**
     * Candidate
     */

    /**
     * Search Candidate Records
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   array $parameters
     * @example $parameters = array('Query' => 'some_query', 'Order' => 'name', 'ResultsPerPage' => '10')
     * @return  array|false
     */
    public function getCandidates($parameters = array())
    {
        $endpoint = '/candidates';

        // If options is not empty and is array
        if (!empty($parameters) && is_array($parameters)) {
            // Append chart to endpoint
            $endpoint .= '?';
            // Then parse the array and create the url
            foreach ($parameters as $key => $value) {
                $endpoint .= $key . '=' . $value;
            }
        }

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Create a new Candidate
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   array $candidate Array with candidate descriptions
     * @return  array|false
     */
    public function postCandidates($candidate = array())
    {
        $endpoint = '/candidates';
        return $this->doRequest('post', $endpoint, array('Candidate' => $candidate));
    }

    /**
     * Get a Candidate by CandidateId
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id
     * @return  array|false
     */
    public function getCandidateByID($id)
    {
        $endpoint = '/candidates/' . $id;

        // If options is not empty and is array
        if (!empty($parameters) && is_array($parameters)) {
            // Append chart to endpoint
            $endpoint .= '?';
            // Then parse the array and create the url
            foreach ($parameters as $key => $value) {
                $endpoint .= $key . '=' . $value;
            }
        }

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Update a Candidate by CandidateId
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id
     * @param   array $candidate
     * @return  array|false
     */
    public function putCandidateByID($id, $candidate = array())
    {
        $endpoint = '/candidates/' . $id;
        return $this->doRequest('get', $endpoint, array('Candidate' => $candidate));
    }

    /**
     * Delete Candidate by Id
     *
     * @param   string $id
     * @return  array|false
     */
    public function deleteCandidateByID($id)
    {
        $endpoint = '/candidates/' . $id;

        // If options is not empty and is array
        if (!empty($parameters) && is_array($parameters)) {
            // Append chart to endpoint
            $endpoint .= '?';
            // Then parse the array and create the url
            foreach ($parameters as $key => $value) {
                $endpoint .= $key . '=' . $value;
            }
        }

        return $this->doRequest('get', $endpoint);
    }


    /**
     * Companies
     */

    /**
     * Search Company Records
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/companies.json
     * @param   array $parameters
     * @return  array|false
     */
    public function getCompanies($parameters = array())
    {
        $endpoint = '/companies';

        // If options is not empty and is array
        if (!empty($parameters) && is_array($parameters)) {
            // Append chart to endpoint
            $endpoint .= '?';
            // Then parse the array and create the url
            foreach ($parameters as $key => $value) {
                $endpoint .= $key . '=' . $value;
            }
        }

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Create a new Company
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/companies.json
     * @param   array $positions
     * @return  array|false
     */
    public function postCompanies($positions = array())
    {
        $endpoint = '/companies';
        return $this->doRequest('post', $endpoint, array('Position' => $positions));
    }

    /**
     * Operations about a company
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/companies.json
     * @param   string $id
     * @return  array|false
     */
    public function getCompaniesByID($id)
    {
        $endpoint = '/companies/' . $id;

        // If options is not empty and is array
        if (!empty($parameters) && is_array($parameters)) {
            // Append chart to endpoint
            $endpoint .= '?';
            // Then parse the array and create the url
            foreach ($parameters as $key => $value) {
                $endpoint .= $key . '=' . $value;
            }
        }

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Update a Company by CompanyId
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/companies.json
     * @param   string $id
     * @param   array $positions
     * @return  array|false
     */
    public function putCompaniesByID($id, $positions = array())
    {
        $endpoint = '/companies/' . $id;
        return $this->doRequest('get', $endpoint, array('Position' => $positions));
    }

    /**
     * Delete Company by Id
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/companies.json
     * @param   string $id
     * @return  array|false
     */
    public function deleteCompaniesByID($id)
    {
        $endpoint = '/companies/' . $id;

        // If options is not empty and is array
        if (!empty($parameters) && is_array($parameters)) {
            // Append chart to endpoint
            $endpoint .= '?';
            // Then parse the array and create the url
            foreach ($parameters as $key => $value) {
                $endpoint .= $key . '=' . $value;
            }
        }

        return $this->doRequest('get', $endpoint);
    }



    /**
     * Positions
     */

    /**
     * Search position Records
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/positions.json
     * @param   array $parameters
     * @example $parameters = array('Query' => 'some_query', 'Order' => 'name', 'ResultsPerPage' => '10')
     * @return  array|false
     */
    public function getPositions($parameters = array())
    {
        $endpoint = '/positions';

        // If options is not empty and is array
        if (!empty($parameters) && is_array($parameters)) {
            // Append chart to endpoint
            $endpoint .= '?';
            // Then parse the array and create the url
            foreach ($parameters as $key => $value) {
                $endpoint .= $key . '=' . $value;
            }
        }

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Create a new Position
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/positions.json
     * @param   array $positions
     * @return  array|false
     */
    public function postPositions($positions = array())
    {
        $endpoint = '/positions';
        return $this->doRequest('post', $endpoint, array('Position' => $positions));
    }

    /**
     * Get a Position by JobId
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/positions.json
     * @param   string $id
     * @return  array|false
     */
    public function getPositionsByID($id)
    {
        $endpoint = '/positions/' . $id;

        // If options is not empty and is array
        if (!empty($parameters) && is_array($parameters)) {
            // Append chart to endpoint
            $endpoint .= '?';
            // Then parse the array and create the url
            foreach ($parameters as $key => $value) {
                $endpoint .= $key . '=' . $value;
            }
        }

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Update a Position by JobId
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/positions.json
     * @param   string $id
     * @param   array $positions
     * @return  array|false
     */
    public function putPositionsByID($id, $positions = array())
    {
        $endpoint = '/positions/' . $id;
        return $this->doRequest('get', $endpoint, array('Position' => $positions));
    }

    /**
     * Delete Position by JobId
     *
     * @param   string $id
     * @return  array|false
     */
    public function deletePositionsByID($id)
    {
        $endpoint = '/positions/' . $id;

        // If options is not empty and is array
        if (!empty($parameters) && is_array($parameters)) {
            // Append chart to endpoint
            $endpoint .= '?';
            // Then parse the array and create the url
            foreach ($parameters as $key => $value) {
                $endpoint .= $key . '=' . $value;
            }
        }

        return $this->doRequest('get', $endpoint);
    }



    /**
     * Users
     */

    /**
     * Search User Records
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/users.json
     * @param   array $parameters
     * @return  array|false
     */
    public function getUsers($parameters = array())
    {
        $endpoint = '/users';

        // If options is not empty and is array
        if (!empty($parameters) && is_array($parameters)) {
            // Append chart to endpoint
            $endpoint .= '?';
            // Then parse the array and create the url
            foreach ($parameters as $key => $value) {
                $endpoint .= $key . '=' . $value;
            }
        }

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Get User by UserName
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/users.json
     * @param   string $username
     * @param   array $parameters
     * @return  array|false
     */
    public function getUserByName($username, $parameters = array())
    {
        $endpoint = '/users/' . $username;

        // If options is not empty and is array
        if (!empty($parameters) && is_array($parameters)) {
            // Append chart to endpoint
            $endpoint .= '?';
            // Then parse the array and create the url
            foreach ($parameters as $key => $value) {
                $endpoint .= $key . '=' . $value;
            }
        }

        return $this->doRequest('get', $endpoint);
    }



    /**
     * Placements
     */

    /**
     * Search Placement Records
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/placements.json
     * @param   array $parameters
     * @return  array|false
     */
    public function getPlacements($parameters = array())
    {
        $endpoint = '/placement';

        // If options is not empty and is array
        if (!empty($parameters) && is_array($parameters)) {
            // Append chart to endpoint
            $endpoint .= '?';
            // Then parse the array and create the url
            foreach ($parameters as $key => $value) {
                $endpoint .= $key . '=' . $value;
            }
        }

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Create a new Placement
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/placements.json
     * @param   array $placements
     * @return  array|false
     */
    public function postPlacements($placements = array())
    {
        $endpoint = '/placement';
        return $this->doRequest('post', $endpoint, array('Placement' => $placements));
    }

    /**
     * Get a Placement By PlacementId
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/placements.json
     * @param   string $id
     * @param   array $parameters
     * @return  array|false
     */
    public function getPlacementByID($id, $parameters = array())
    {
        $endpoint = '/placement/' . $id;

        // If options is not empty and is array
        if (!empty($parameters) && is_array($parameters)) {
            // Append chart to endpoint
            $endpoint .= '?';
            // Then parse the array and create the url
            foreach ($parameters as $key => $value) {
                $endpoint .= $key . '=' . $value;
            }
        }

        return $this->doRequest('get', $endpoint);
    }



    /**
     * Interviews
     */

    /**
     * Search Interiew Records
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/interviews.json
     * @param   array $parameters
     * @return  array|false
     */
    public function getInterviews($parameters = array())
    {
        $endpoint = '/interviews';

        // If options is not empty and is array
        if (!empty($parameters) && is_array($parameters)) {
            // Append chart to endpoint
            $endpoint .= '?';
            // Then parse the array and create the url
            foreach ($parameters as $key => $value) {
                $endpoint .= $key . '=' . $value;
            }
        }

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Create a new Interview
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/interviews.json
     * @param   array $placements
     * @return  array|false
     */
    public function postInterviews($placements = array())
    {
        $endpoint = '/interviews';
        return $this->doRequest('post', $endpoint, array('Placement' => $placements));
    }

    /**
     * Get an Interview By InterviewId
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/interviews.json
     * @param   string $id
     * @param   array $parameters
     * @return  array|false
     */
    public function getInterviewsByID($id, $parameters = array())
    {
        $endpoint = '/interviews/' . $id;

        // If options is not empty and is array
        if (!empty($parameters) && is_array($parameters)) {
            // Append chart to endpoint
            $endpoint .= '?';
            // Then parse the array and create the url
            foreach ($parameters as $key => $value) {
                $endpoint .= $key . '=' . $value;
            }
        }

        return $this->doRequest('get', $endpoint);
    }
}
