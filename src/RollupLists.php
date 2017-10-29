<?php namespace PCRecruiter;

/**
 * Class RollupLists
 * @package PCRecruiter
 */
class RollupLists extends Client
{
    /**
     * Search Rollup Lists
     *
     * @param   array $params - List of parameters for query
     * @return  array|false
     */
    public function search($params = array())
    {
        $endpoint = '/rolluplists';

        if (!empty($params) && is_array($params))
            $endpoint .= $this->compileURL($params);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Create a new RollupLists
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/rolluplists.json
     * @param   array $params - The RollupList to create
     * @return  array|false
     */
    public function post($params = array())
    {
        $endpoint = '/rolluplists';

        return $this->doRequest('post', $endpoint, array('RollupLists' => $params));
    }

    /**
     * Get RollupList by Rollup Code
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/rolluplists.json
     * @param   string|null $code - RollupCode
     * @param   array $params - List of parameters for query
     * @return  array|false
     */
    public function get($code = null, $params = array())
    {
        $endpoint = '/rolluplists';

        if (!empty($code))
            $endpoint .= '/' . $code;

        if (!empty($params) && is_array($params))
            $endpoint .= $this->compileURL($params);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Update a RollupLists
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/rolluplists.json
     * @param   int $code - The RollupCode of the Rollup Record that needs to be updated.
     * @param   array $params - The Rollup List to update
     * @return  array|false
     */
    public function put($code, $params = array())
    {
        $endpoint = '/rolluplists/' . $code;

        return $this->doRequest('put', $endpoint, array('RollupLists' => $params));
    }
}
