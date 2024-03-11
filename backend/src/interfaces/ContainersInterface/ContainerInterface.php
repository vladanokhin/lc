<?php

namespace src\interfaces\ContainersInterface;

interface ContainerInterface
{
    public static function fill(string $key, $data);

    public static function get(string $key);
}