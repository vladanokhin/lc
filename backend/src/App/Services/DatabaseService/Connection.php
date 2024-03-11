<?php

namespace src\App\Services\DatabaseService;

use Exception;
use PDO;
use phpDocumentor\Reflection\Types\Self_;
use src\App\Container\AppContainer;

final class Connection
{
    protected static $connection;
    /**
     * @return PDO
     * @throws Exception
     */
    public static function make(): PDO
    {
        if (!self::$connection instanceof PDO) {
            self::configureConnection();
        }
        return self::$connection;
    }

    /**
     * @throws Exception
     */
    public static function configureConnection()
    {
        self::$connection = self::connectionMysql();
    }

    /**
     * @return PDO
     * @throws Exception
     */
    protected static function connectionMysql(): PDO
    {
        $config = AppContainer::get('mysql');
        $dsn = "host={$config['host']};dbname={$config['dbname']};port={$config['port']};charset={$config['charset']};";
        $dsn = AppContainer::get('dbdriver') . ":{$dsn}";
        $pdo = new PDO($dsn, $config['user'], $config['password']);
        $options = $config['options'];
        foreach ($options as $k => $v) {
            $pdo->SetAttribute($k, $v);
        }

        return $pdo;
    }
}
