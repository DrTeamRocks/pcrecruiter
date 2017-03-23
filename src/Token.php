<?php namespace PCRecruiter;

/**
 * Class Token
 * @package PCRecruiter
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
            . 'Username=' . $this->username . '&'
            . 'Password=' . $this->password . '&'
            . 'AppId=' . $this->app_id . '&'
            . 'ApiKey=' . $this->api_key;

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Log user off (delete current access token)
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
