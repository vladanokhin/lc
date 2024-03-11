<?php

namespace src\App\Container;

trait ContainerTrait
{
    protected static $container = [];

    /**
     * Getting data from container if this data exists.
     * @param string $key
     * @return mixed
     * @throws \Exception
     */
    public static function get(string $key)
    {
        if (array_key_exists($key, self::$container)) {
            return self::$container[$key];
        }

        throw new \Exception("You are looking for unregistered item: {$key}!");
    }

    /**
     * Filling container with needed services or some data. (Works globally)
     * @param string $key
     * @param $data
     * @return null
     */
    public static function fill(string $key, $data)
    {
        if (!array_key_exists($key, self::$container)) {
            self::$container[$key] = $data;
        }

        return null;
    }

    /**
     * Updating needed value in container.
     * @param string $key
     * @param $data
     * @return null
     */
    public static function change(string $key, $data)
    {
        if (array_key_exists($key, self::$container)) {
            unset(self::$container[$key]);
            return self::$container[$key] = $data;
        }

        return self::fill($key, $data);
    }

    /**
     * Developers can see what is in the container.
     */
    public static function showAllCases()
    {
        $allElements = array_keys(self::$container);
        array_map(function ($arr) {
            echo "\n{$arr}\n";
        }, $allElements);
    }
}