<?php namespace PCRecruiter;

/**
 * Class Companies
 * @package PCRecruiter
 */
class Companies extends Client
{
    /**
     * Search Company of get a Company by CompanyId
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/companies.json
     * @param   string|null $id - CompanyId
     * @param   array $parameters - List of parameters for query
     * @return  array|false
     */
    public function get($id = null, $parameters = [])
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
     * @param   array $companies - The company to create
     * @return  array|false
     */
    public function create($companies = [])
    {
        $endpoint = '/companies';

        return $this->doRequest('post', $endpoint, array('Company' => $companies));
    }

    /**
     * Update a Company by CompanyId
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/companies.json
     * @param   string $id - CompanyId
     * @param   array $companies - The company to update
     * @return  array|false
     */
    public function update($id, $companies = [])
    {
        $endpoint = '/companies/' . $id;

        return $this->doRequest('put', $endpoint, array('Company' => $companies));
    }

    /**
     * Delete Company by Id
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/companies.json
     * @param   string $id
     * @return  array|false
     */
    public function delete($id)
    {
        $endpoint = '/companies/' . $id;

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('delete', $endpoint);
    }
}
