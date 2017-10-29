<?php
// Report all error
error_reporting(E_ALL);

// Enable autoload
require_once __DIR__ . "/../vendor/autoload.php";

// Get the token
$pcr_token = new PCRecruiter\Token();
$pcr_token->setConfig(__DIR__ . "/pcr.php");

// Get the token
$token = $pcr_token->get()['message']->SessionId;

// Get all positions
$pcr_positions = new PCRecruiter\Positions($token);
$positions = $pcr_positions->get()['message']->Results;

// Return the json
header('Content-Type: application/json');
echo json_encode($positions);
