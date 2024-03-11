<?php

namespace src\App\Services\RouteService;

class ControllerService
{
    private $data;

    /**
     * Registering URI matches
     * ControllerService constructor.
     * @param $matches
     */
    public function __construct($matches)
    {
        $this->data = $matches;
    }

    /**
     * Preparing data
     */
    public function handleMatches()
    {
        $matches = $this->data['target'];
        $matches[0] = new $matches[0];

        if (is_callable($matches)) {
            return $this->engageController();
        }
        return null;
    }

    /**
     * Launching needed Controller
     * @return mixed
     */
    private function engageController()
    {
        $class = new $this->data['target'][0]();
        $method = $this->data['target'][1];

        return call_user_func_array([$class, $method], [$this->data['params']]);
    }
}