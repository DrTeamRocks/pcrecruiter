<?php namespace PCRecruiter;

/**
 * Class Users
 * @package PCRecruiter
 */
class Users extends Client
{
    public function __construct($token)
    {
        parent::__construct();
        $this->token = $token;
    }

    /**
     * Show all users or search by username
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/users.json
     *
     * @param   string|null $username
     * @param   array $parameters
     *
     * @return  array|false
     */
    public function get($username = null, $parameters = array())
    {
        $endpoint = '/users';

        if (!empty($username))
            $endpoint .= '/' . $username;

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

}
