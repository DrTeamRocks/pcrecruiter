<?php
// Report all error
error_reporting(E_ALL);

// Enable autoload
require_once __DIR__ . "/../vendor/autoload.php";

// Get the token
$pcr_token = new PCRecruiter\Token();
$pcr_token->readConfig(__DIR__ . "/configs/pcr.php");

// Get the token
$token = $pcr_token->get()['message']->SessionId;

// Return the json
header('Content-Type: application/json');
echo json_encode($token);