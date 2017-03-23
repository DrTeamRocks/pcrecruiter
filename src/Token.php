<?php namespace PCRecruiter;

/**
 * Class Token
 * @package DrTeam\PCRecruiter
 */
class Token extends Factory
{
    /**
     * Retrieve a valid api session
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/access-token.json
     *
     * @return  array|false
     */
    public function get()
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
     *
     * @return  array|false
     */
    public function delete()
    {
        $endpoint = '/access-token';

        return $this->doRequest('delete', $endpoint);
    }

}
