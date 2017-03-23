<?php namespace PCRecruiter;

/**
 * Class Placements
 * @package PCRecruiter
 */
class Placements extends Factory
{
    /**
     * Search Placement Records
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/placements.json
     *
     * @param   string|null $id PlacementId
     * @param   array $parameters
     *
     * @return  array|false
     */
    public function get($id = null, $parameters = array())
    {
        $endpoint = '/placement';

        if (!empty($id))
            $endpoint .= '/' . $id;

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Create a new Placement
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/placements.json
     *
     * @param   array $placements
     *
     * @return  array|false
     */
    public function post($placements = array())
    {
        $endpoint = '/placement';

        return $this->doRequest('post', $endpoint, array('Placement' => $placements));
    }

}
