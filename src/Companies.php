<?php namespace PCRecruiter;

/**
 * Class Companies
 * @package PCRecruiter
 */
class Companies extends Client
{
    public function __construct($token)
    {
        parent::__construct();
        $this->token = $token;
    }

    /**
     * Search Company Records
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/companies.json
     *
     * @param   string|null $id
     * @param   array $parameters
     *
     * @return  array|false
     */
    public function getCompanies($id = null, $parameters = array())
    {
        $endpoint = '/companies';

        if (!empty($id))
            $endpoint .= '/' . $id;

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Create a new Company
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/companies.json
     *
     * @param   array $positions
     *
     * @return  array|false
     */
    public function post($positions = array())
    {
        $endpoint = '/companies';
        return $this->doRequest('post', $endpoint, array('Position' => $positions));
    }

    /**
     * Update a Company by CompanyId
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/companies.json
     *
     * @param   string $id
     * @param   array $positions
     *
     * @return  array|false
     */
    public function put($id, $positions = array())
    {
        $endpoint = '/companies/' . $id;
        return $this->doRequest('get', $endpoint, array('Position' => $positions));
    }

    /**
     * Delete Company by Id
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/companies.json
     *
     * @param   string $id
     *
     * @return  array|false
     */
    public function delete($id)
    {
        $endpoint = '/companies/' . $id;

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }
}
