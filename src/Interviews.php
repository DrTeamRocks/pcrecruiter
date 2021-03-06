<?php namespace PCRecruiter;

/**
 * Class Interviews
 * @package PCRecruiter
 */
class Interviews extends Client
{
    /**
     * Search Interview Records
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/interviews.json
     * @param   string|null $id InterviewId
     * @param   array $parameters
     * @return  array|false
     */
    public function get($id = null, $parameters = [])
    {
        $endpoint = '/interviews';

        if (!empty($id))
            $endpoint .= '/' . $id;

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Create a new Interview
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/interviews.json
     * @param   array $interviews
     * @return  array|false
     */
    public function create($interviews = [])
    {
        $endpoint = '/interviews';

        return $this->doRequest('post', $endpoint, array('Interview' => $interviews));
    }

}
