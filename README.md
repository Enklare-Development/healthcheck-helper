# Readme
A simple class utility to help creating uniform healthchecks with sub-system checks if necessary

# Using
`composer require enkalre-development/healthcheck-helper`
Laravel usage example
```
<?php

namespace App\Http\Controllers;

use \Enklare\Health\HealthCheck;
use \Enklare\Health\BasicHealthCheck;

class HealthController
{

    /**
     * Just nice to have in an API health department
     **/
    public function ping()
    {
        return response(null, 204);
    }

    /**
     * Status healthcheck function
     **/
    public function status()
    {
        $check = new HealthCheck('my-awesome-service', "1.0.0");
        $check->addCheck($this->_checkDatabase());
        $check->addCheck($this->_checkRedis());
        return response()->json($check, 200);
    }

    private function _checkDatabase()
    {
        $check = new BasicHealthCheck('database');
        return SomeCoolHelper::isDatabaseReady() ? $check->passed(): $check->failed();
    }

    private function _checkRedis()
    {
        $check = new BasicHealthCheck('redis');
        return SomeCoolHelper::isRedisReady() ? $check->passed(): $check->failed();
    }
}

```


# Developing
`composer install` and code away

# Testing
`composer test` and fix the issues!
