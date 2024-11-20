<?php

namespace App\Middleware;

use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\Middleware;

class PreventRootConnectionMiddleware implements Middleware
{
    public function wrap(Driver $driver): Driver
    {
        return new PreventRootConnectionDriver($driver);
    }
}
