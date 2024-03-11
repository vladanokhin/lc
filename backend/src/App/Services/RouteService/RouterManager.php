<?php

namespace src\App\Services\RouteService;

class RouterManager {

    /**
     * Finding needed Controller and launching it
     * @param array $matches
     * @return mixed|null
     */
    public function handleMatches(array $matches)
    {
        return (new ControllerService($matches))->handleMatches();
    }
}