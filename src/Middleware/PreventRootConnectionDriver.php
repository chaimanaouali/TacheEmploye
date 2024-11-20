<?php

namespace App\Middleware;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\Middleware\AbstractDriverMiddleware;

final class PreventRootConnectionDriver extends AbstractDriverMiddleware
{
    public function connect(array $params): Connection
    {
       

        return parent::connect($params);
    }
}
