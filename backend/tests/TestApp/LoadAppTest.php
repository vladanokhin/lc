<?php declare(strict_types=1);

namespace TestApp;

use PHPUnit\Framework\TestCase;
use src\App\Services\CallerService\ServiceManager;

class LoadAppTest extends TestCase
{
    /**
     * Instance of Quantum Backend App
     */
    protected $app;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        include_once 'public/index.php';

        $this->app = new ServiceManager();
    }

    public function testForTest()
    {
        $this->assertTrue(true);
    }
}