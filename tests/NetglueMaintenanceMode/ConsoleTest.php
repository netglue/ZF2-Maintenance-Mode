<?php
namespace NetglueMaintenanceMode;

class ConsoleTest extends \Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase
{

    protected $lockfile;

    public function setUp()
    {
        $this->reset();
        $this->setApplicationConfig(
            include __DIR__.'/../config/TestConfig.dist.php'
        );
        $this->lockfile = __DIR__.'/../data/lock';
        parent::setUp();
    }

    public function tearDown()
    {
        if(file_exists($this->lockfile)) {
            unlink($this->lockfile);
        }
        chmod(dirname($this->lockfile), 0755);
        parent::tearDown();
    }

    public function testLockFileCreated()
    {
        $this->assertFalse(file_exists($this->lockfile));
        $this->dispatch('maintenance');
        $this->assertTrue(file_exists($this->lockfile));
        //$this->assertConsoleOutputContains('Maintenance mode enabled');
    }

    public function testLockFileRemoved()
    {
        $this->assertFalse(file_exists($this->lockfile));
        touch($this->lockfile);
        $this->dispatch('maintenance');
        $this->assertFalse(file_exists($this->lockfile));
        //$this->assertConsoleOutputContains('Maintenance mode disabled');
    }

}
