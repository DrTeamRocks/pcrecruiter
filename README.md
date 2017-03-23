![PCRecruiter PHP API Logo](http://drteam.rocks/images/pcrecruter/prc_api.png)

# PCRecruiter PHP

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/DrTeamRocks/pcrecruiter/master/LICENSE)

PHP library for work with PCRecruiter RESTful API

```php
// Enable the class
$pcr = new PCRecruiter();

// Parameters for PCR
$pcr->user = '[USERNAME]';
$pcr->pass = '[PASSWORD]';
$pcr->database = '[DBNAME].[POOLNAME]';
$pcr->app = '[APPID]';
$pcr->key = '[APIKEY]';

// Get token for current session
$response = $pcr->getAccessToken();
$pcr->token = $response['message']->SessionId;

// Get all jobs from PCR board
$response = $pcr->getPositions();
$jobs = $response['message']->Results;
```

## How to install

### Via composer

* Stable release: `composer require drteam/pcrecruiter`

* Unstable release: `composer require drteam/pcrecruiter "@dev"`

### Classic style

* Download the [PCRecruiter](https://github.com/DrTeamRocks/pcrecruiter/releases) package

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
