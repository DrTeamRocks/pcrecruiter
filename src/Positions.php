<?php namespace PCRecruiter;

/**
 * Class Positions
 * @package PCRecruiter
 */
class Positions extends Factory
{
    /**
     * Search position records
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/positions.json
     *
     * @param   string|null $id PositionId
     * @param   array $parameters
     *
     * @example $parameters = array('Query' => 'some_query', 'Order' => 'name', 'ResultsPerPage' => '10')
     * @return  array|false
     */
    public function get($id = null, $parameters = array())
    {
        $endpoint = '/positions';

        if (!empty($id))
            $endpoint .= '/' . $id;

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Create a new Position
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/positions.json
     *
     * @param   array $positions
     *
     * @return  array|false
     */
    public function post($positions = array())
    {
        $endpoint = '/positions';
        return $this->doRequest('post', $endpoint, array('Position' => $positions));
    }

    /**
     * Update a Position by JobId
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/positions.json
     *
     * @param   string $id
     * @param   array $positions
     *
     * @return  array|false
     */
    public function put($id, $positions = array())
    {
        $endpoint = '/positions/' . $id;

        return $this->doRequest('get', $endpoint, array('Position' => $positions));
    }

    /**
     * Delete Position by JobId
     *
     * @param   string $id
     *
     * @return  array|false
     */
    public function delete($id)
    {
        $endpoint = '/positions/' . $id;

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

}
