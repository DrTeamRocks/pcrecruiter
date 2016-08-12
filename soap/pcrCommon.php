<?php

function get_soap_client()
{
    static $client;
    if(!isset($client)){
        $wsdl = 'http://localhost:61757/PcrApi.svc?wsdl';
        $endpoint = 'http://localhost:61757/PcrApi.svc/soap';
        $client = new SoapClient($wsdl, array('trace'=>1,'location'=>$endpoint));
    }
    return $client;
}

function perform_authentication()
{
    $container = new stdClass();
    $container->Credentials->UserName = 'admin';
    $container->Credentials->Password = '';
    $container->Credentials->DatabaseId='kw2.txt';
    $container->Credentials->ApiKey ='ad7008f4c8650b25bba3440ef2e8ff4c';
    $container->Credentials->AppId= '6d168d38';
    $container->Credentials->Language ='en-US';
    
    $client = get_soap_client();
    $results = $client->Logon($container);
    
    if (strlen($results->LogonResult->SessionId) != 0)
    {
        setcookie("pcr-id",$results->LogonResult->SessionId,60);
        return $results->LogonResult->SessionId;
    }
    else
    {
        return "";
    }
}

function fix_mssql_quotes($inputString)
{
    return str_replace("'","''",$inputString);
}

?>