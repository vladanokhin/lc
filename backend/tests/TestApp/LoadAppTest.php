<?php

namespace Tests\TestApp;

use PHPUnit\Framework\TestCase;
use src\App\Services\CallerService\ServiceManager;

class LoadAppTest extends TestCase
{
    /**
     * Instance of Quantum Backend App
     */
    protected ServiceManager $app;

    public function setUp(): void
    {
        include_once 'public/index.php';

        $this->app = new ServiceManager();

    }
    public function testForTest()
    {
        $this->assertTrue(true);
    }
}