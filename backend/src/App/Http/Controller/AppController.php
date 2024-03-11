<?php

namespace src\App\Http\Controller;

use src\App\Services\DatabaseService\DataBaseManager;
use src\App\Services\DatabaseService\Connection;

class AppController
{
    /**
     * @throws \Exception
     */
    public function installer(): void
    {
        echo '<pre>';
        echo 'Database installer:';
        $connection = new Connection();
        $db = new DataBaseManager($connection->make(), 'leads', 'scheduled_leads');
        if (!$db->configure()) {
            throw new \Exception("\nCan't create valid database;\n");
        } else {
            echo 'Database configured';
        }
    }
}