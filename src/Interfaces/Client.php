<?php namespace PCRecruiter\Interfaces;

interface Client
{
    /**
     * Server default url
     */
    const host = 'www2.pcrecruiter.net';

    /**
     * Default path to API interface
     */
    const path = '/rest/api';

    /**
     * Default port
     */
    const port = '443';

    /**
     * SSL mode enabled by default
     */
    const useSSL = true;
}
