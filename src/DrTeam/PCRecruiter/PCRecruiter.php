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
    public $user;
    public $pass;
    public $app;
    public $key;

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
     * Generate new URL from parameters
     *
     * @param $parameters
     * @return string
     */
    function compileURL($parameters)
    {
        // Init chart for endpoint
        $endpoint = '?Query=';
        // Then parse the array and create the url
        foreach ($parameters as $key => $value) {
            $endpoint .= $key . ' eq ' . $value ;
        }

        return $endpoint;
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

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

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
     * @param   string $id CandidateId
     * @param   array $parameters
     * @return  array|false
     */
    public function getCandidateByID($id, $parameters = array())
    {
        $endpoint = '/candidates/' . $id;

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Update a Candidate by CandidateId
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id CandidateId
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
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id CandidateId
     * @return  array|false
     */
    public function deleteCandidateByID($id)
    {
        $endpoint = '/candidates/' . $id;

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Get Attachments by CandidateId
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id CandidateId
     * @param   array $parameters
     * @return  array|false
     */
    public function getCandidateAttachmentsByID($id, $parameters = array())
    {
        $endpoint = '/candidates/' . $id . '/attachments';

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Create a new Candidate Attachment
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id CandidateId
     * @param   array $attachments
     * @return  array|false
     */
    public function postCandidateAttachmentsByID($id, $attachments = array())
    {
        $endpoint = '/candidates/' . $id . '/attachments';
        return $this->doRequest('post', $endpoint, array('Attachments' => $attachments));
    }

    /**
     * Get Candidate Attachment by AttachmentId
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $cid CandidateId
     * @param   string $aid AttachmentId
     * @param   array $parameters
     * @return  array|false
     */
    public function getCandidateAttachmentsByAttachmentID($cid, $aid, $parameters = array())
    {
        $endpoint = '/candidates/' . $cid . '/attachments/' . $aid;

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Update a Candidate Attachment
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $cid CandidateId
     * @param   string $aid AttachmentId
     * @param   array $candidate Candidate Attachments Body
     * @return  array|false
     */
    public function putCandidateAttachmentsByAttachmentID($cid, $aid, $candidate = array())
    {
        $endpoint = '/candidates/' . $cid . '/attachments/' . $aid;
        return $this->doRequest('put', $endpoint, array('Attachments' => $candidate));
    }


    /**
     * Search Candidate Activities by CandidateId
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id CandidateId
     * @param   array $parameters
     * @return  array|false
     */
    public function getCandidateActivitiesByID($id, $parameters = array())
    {
        $endpoint = '/candidates/' . $id . '/activities';

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Create a new Candidate Activity
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id CandidateId
     * @param   array $activity
     * @return  array|false
     */
    public function postCandidateActivitiesByID($id, $activity = array())
    {
        $endpoint = '/candidates/' . $id . '/activities';
        return $this->doRequest('post', $endpoint, array('CandidateActivity' => $activity));
    }

    /**
     * Return a Candidate's Resume
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id CandidateId
     * @param   array $parameters
     * @return  array|false
     */
    public function getCandidateResumesByID($id, $parameters = array())
    {
        $endpoint = '/candidates/' . $id . '/resumes';

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Create a new Candidate Resume
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id CandidateId
     * @param   array $resume
     * @return  array|false
     */
    public function postCandidateResumesByID($id, $resume = array())
    {
        $endpoint = '/candidates/' . $id . '/resumes';
        return $this->doRequest('post', $endpoint, array('Resume' => $resume));
    }

    /**
     * Update Candidate Resume
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id CandidateId
     * @param   array $resume
     * @return  array|false
     */
    public function putCandidateResumesByID($id, $resume = array())
    {
        $endpoint = '/candidates/' . $id . '/resumes';
        return $this->doRequest('put', $endpoint, array('Resume' => $resume));
    }

    /**
     * Return a Candidate's Blinded Resume
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id CandidateId
     * @param   array $parameters
     * @return  array|false
     */
    public function getCandidateBlindedByID($id, $parameters = array())
    {
        $endpoint = '/candidates/' . $id . '/blindedresumes';

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Create a new Candidate Blinded Resume
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id CandidateId
     * @param   array $resume
     * @return  array|false
     */
    public function postCandidateBlindedByID($id, $resume = array())
    {
        $endpoint = '/candidates/' . $id . '/blindedresumes';
        return $this->doRequest('post', $endpoint, array('Resume' => $resume));
    }

    /**
     * Update Candidate Blinded Resume
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id CandidateId
     * @param   array $resume
     * @return  array|false
     */
    public function putCandidateBlindedByID($id, $resume = array())
    {
        $endpoint = '/candidates/' . $id . '/blindedresumes';
        return $this->doRequest('put', $endpoint, array('Resume' => $resume));
    }

    /**
     * Search Candidate Activities
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   array $parameters
     * @return  array|false
     */
    public function getCandidatesActivities($parameters = array())
    {
        $endpoint = '/candidates/activities';

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Search Candidate Activities by ActivityId
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id ActivityId
     * @param   array $parameters
     * @return  array|false
     */
    public function getCandidatesActivitiesByActivityID($id, $parameters = array())
    {
        $endpoint = '/candidates/activities/' . $id;

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Get Distinct Fields
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   array $parameters
     * @return  array|false
     */
    public function getCandidatesDistinctFields($parameters = array())
    {
        $endpoint = '/candidates/DistinctFields';

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

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

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

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

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

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

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

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

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

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

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

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

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

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

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

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

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

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

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

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

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

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

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

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

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }
}
