![PCRecruiter PHP API Logo](http://drteam.rocks/images/pcrecruter/pcr_api.png)

# PCRecruiter PHP

[![Latest Stable Version](https://poser.pugx.org/drteam/pcrecruiter/v/stable)](https://packagist.org/packages/drteam/pcrecruiter)
[![Build Status](https://travis-ci.org/DrTeamRocks/pcrecruiter.svg?branch=master)](https://travis-ci.org/DrTeamRocks/pcrecruiter)
[![Total Downloads](https://poser.pugx.org/drteam/pcrecruiter/downloads)](https://packagist.org/packages/drteam/pcrecruiter)
[![License](https://poser.pugx.org/drteam/pcrecruiter/license)](https://packagist.org/packages/drteam/pcrecruiter)
[![PHP 7 ready](https://php7ready.timesplinter.ch/DrTeamRocks/pcrecruiter/master/badge.svg)](https://travis-ci.org/DrTeamRocks/pcrecruiter)

PHP library for work with PCRecruiter RESTful API

## Examples

### Get AccessToken for work with PCR 

```php
// Enable autoload
require_once __DIR__ . "/vendor/autoload.php";

// Get the token
$pcr_token = new PCRecruiter\Token();
$pcr_token->readConfig(__DIR__ . "/configs/pcr.php");

// Get the token
$token = $pcr_token->get()['message']->SessionId;

// Return the json
header('Content-Type: application/json');
echo json_encode($token);
```

### Get all jobs (positions) from PCR 

```php
// Enable autoload
require_once __DIR__ . "/vendor/autoload.php";

// Get the token
$pcr_token = new PCRecruiter\Token();
$pcr_token->readConfig(__DIR__ . "/configs/pcr.php");

// Get the token
$token = $pcr_token->get()['message']->SessionId;

// Get all positions
$pcr_positions = new PCRecruiter\Positions($token);
$positions = $positions->get()['message']->Results;

// Return the json
header('Content-Type: application/json');
echo json_encode($positions);
```

Any other examples you can find on "[examples](https://github.com/DrTeamRocks/pcrecruiter-examples)" page.

## How to install

### Via composer

`composer require drteam/pcrecruiter`

### Classic style

* Download the latest [PCRecruiter](https://github.com/DrTeamRocks/pcrecruiter/releases) package

* Extract the archive

* Initiate the styles and scripts, just run `composer update` from directory with sources

## Where to get help

If you need help with this project, you can read more about [API Methods](https://github.com/DrTeamRocks/pcrecruiter/wiki/API-methods). 

If you found the bug, please report about this on [GitHub Issues](https://github.com/DrTeamRocks/pcrecruiter/issues) page.

## About PHP Unit Tests

* [ ] Candidates.php
* [ ] Companies.php
* [x] Factory.php
* [ ] Interviews.php
* [ ] Placements.php
* [ ] Positions.php
* [ ] RollupLists.php
* [ ] Token.php
* [ ] Users.php

You can run tests by hands from source directory via `vendor/bin/phpunit` command. 

## Developers

* [Paul Rock](https://github.com/EvilFreelancer)

## What inspired and some links

Created under the influence the lack of such a project on the Internet :smile:

* [PCRecruiter](https://www.pcrecruiter.net/) - This company provides a lots of services that can be useful for recruitment agencies.

* [PCRecruiter API](https://www.pcrecruiter.net/apidocs_v2/) - Official documentation about all API calls.

* [Guzzle](https://github.com/guzzle/guzzle) - An extensible PHP HTTP client, what i very like.
