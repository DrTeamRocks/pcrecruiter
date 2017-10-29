<?php namespace PCRecruiter;

/**
 * Class Token
 * @package PCRecruiter
 */
class Token extends Client
{
    /**
     * Retrieve a valid api session
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/access-token.json
     * @return  array|false
     * @throws  \Exception
     */
    public function get()
    {
        // Read the configs
        $configs = $this->getConfig();

        // Default endpoint
        $endpoint = '/access-token?';

        // Parse the config and set the variables
        $i = 1;
        foreach ($configs as $key => $value) {
            // Choose the current key
            switch ($key) {
                case 'DatabaseId':
                case 'Username':
                case 'Password':
                case 'AppId':
                case 'ApiKey':
                    $endpoint .= "$key=" . $value;
                    if ($i < count($configs)) $endpoint .= '&';
                    break;
                default:
                    throw new \Exception("Invalid parameter $key found.");
                    break;
            }
            $i++;
        }

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Log user off (delete current access token)
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/access-token.json
     * @return  array|false
     */
    public function delete()
    {
        $endpoint = '/access-token';

        return $this->doRequest('delete', $endpoint);
    }

}
