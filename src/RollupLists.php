<?php namespace PCRecruiter;

/**
 * Class RollupLists
 * @package PCRecruiter
 */
class RollupLists extends Client
{
    /**
     * Get RollupList by Rollup Code
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/rolluplists.json
     * @param   string|null $code - RollupCode
     * @param   array $params - List of parameters for query
     * @return  array|false
     */
    public function get($code = null, $params = [])
    {
        $endpoint = '/rolluplists';

        if (!empty($code))
            $endpoint .= '/' . $code;

        if (!empty($params) && is_array($params))
            $endpoint .= $this->compileURL($params);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Create a new RollupLists
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/rolluplists.json
     * @param   array $rolluplists - The RollupList to create
     * @return  array|false
     */
    public function create($rolluplists = [])
    {
        $endpoint = '/rolluplists';

        return $this->doRequest('post', $endpoint, array('RollupLists' => $rolluplists));
    }

    /**
     * Update a RollupLists
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/rolluplists.json
     * @param   int $code - The RollupCode of the Rollup Record that needs to be updated.
     * @param   array $rolluplists - The Rollup List to update
     * @return  array|false
     */
    public function update($code, $rolluplists = [])
    {
        $endpoint = '/rolluplists/' . $code;

        return $this->doRequest('put', $endpoint, array('RollupLists' => $rolluplists));
    }
}
