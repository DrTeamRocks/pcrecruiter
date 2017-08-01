<?php namespace PCRecruiter;

/**
 * Class RollupLists
 * @package PCRecruiter
 */
class RollupLists extends Client
{
    /**
     * RollupLists constructor.
     * @param string $token - PCR token for work with system
     */
    public function __construct($token)
    {
        parent::__construct();
        $this->token = $token;
    }

    /**
     * Get all RollupLists or RollupList by Rollup Code
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/rolluplists.json
     * @param   string|null $code RollupCode
     * @param   array $parameters
     * @return  array|false
     */
    public function get($code = null, $parameters = array())
    {
        $endpoint = '/rolluplists';

        if (!empty($code))
            $endpoint .= '/' . $code;

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Create a new RollupLists
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/rolluplists.json
     * @param   array $placements
     * @return  array|false
     */
    public function post($placements = array())
    {
        $endpoint = '/rolluplists';

        return $this->doRequest('post', $endpoint, array('RollupLists' => $placements));
    }

    /**
     * Update a RollupLists
     *
     * @param   $code
     * @param   array $positions
     * @return  array|false
     */
    public function put($code, $positions = array())
    {
        $endpoint = '/rolluplists/' . $id;

        return $this->doRequest('put', $endpoint, array('RollupLists' => $positions));
    }
}
