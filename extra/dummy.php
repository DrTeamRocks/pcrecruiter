<?php

class Dummy
{
    public function get($parameters = array())
    {
        $endpoint = '/url';

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

    public function post($mode = array())
    {
        $endpoint = '/url';
        return $this->doRequest('post', $endpoint, array('mode' => $mode));
    }

    public function put($id, $mode = array())
    {
        $endpoint = '/url/' . $id;
        return $this->doRequest('put', $endpoint, array('mode' => $mode));
    }

    public function delete($id)
    {
        $endpoint = '/url/' . $id;
        return $this->doRequest('delete', $endpoint);
    }
}
