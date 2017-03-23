![PCRecruiter PHP API Logo](http://drteam.rocks/images/pcrecruter/prc_api.png)

# PCRecruiter PHP

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/DrTeamRocks/pcrecruiter/master/LICENSE)

PHP library for work with PCRecruiter RESTful API

## Examples

```php
// Enable autoload
require_once __DIR__ . "/vendor/autoload.php";

// Get the token
$token = new PCRecruiter\Token();
$token->readConfig(__DIR__ . "/configs/pcr.php");
// Get the token
$token_response = $token->get();

// Get all positions
$positions = new PCRecruiter\Positions();
$positions->token = $token_response['message']->SessionId;
$positions_response = $positions->get();

// Store positions into value
$positions_results = $positions_response['message']->Results;

// Return the json
header('Content-Type: application/json');
echo json_encode($positions_results);
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

## Developers

* [Paul Rock](https://github.com/EvilFreelancer)

## What inspired and some links

Created under the influence the lack of such a project on the Internet :smile:

* [PCRecruiter](https://www.pcrecruiter.net/) - This company provides a lots of services that can be useful for recruitment agencies.

* [PCRecruiter API](https://www.pcrecruiter.net/apidocs_v2/) - Official documentation about all API calls.

* [Guzzle](https://github.com/guzzle/guzzle) - An extensible PHP HTTP client, what i very like.
