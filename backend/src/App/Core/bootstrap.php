<?php

use src\App\Container\AppContainer;

/**
 * Automatically filling container with needed settings.
 */
foreach ($configArray as $config) {
    foreach ($config as $key => $value) {
        AppContainer::fill($key, $value);
    }
}