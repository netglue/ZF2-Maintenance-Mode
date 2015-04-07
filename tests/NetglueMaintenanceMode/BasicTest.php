<?php
namespace NetglueMaintenanceMode;
use Zend\Mvc\MvcEvent;
class BasicTest extends \Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase
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
        parent::tearDown();
    }

    public function testExpected404InNonMaintenanceMode()
    {
        $this->dispatch('/somewhere');
        $this->assertResponseStatusCode(404);
    }

    public function testExpected503InMaintenanceMode()
    {
        $this->assertFalse(file_exists($this->lockfile));
        $this->assertTrue(is_writable(dirname($this->lockfile)));
        touch($this->lockfile);
        $this->dispatch('/somewhere');
        $this->assertResponseStatusCode(503);
        $this->assertResponseHeaderContains('Content-Type', 'foo/bar');
        $this->assertSame(file_get_contents(__DIR__.'/../../data/Maintenance.html'), $this->getResponse()->getContent());
    }




}
