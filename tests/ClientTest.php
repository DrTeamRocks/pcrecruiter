<?php

require_once(__DIR__ . '/../src/Client.php');

use PHPUnit\Framework\TestCase;
use PCRecruiter\Client;

class ClientTest extends TestCase
{
    public function testReadConfig()
    {
        $factory = new Client();
        $config = __DIR__ . '/pcr.example.php';

        // Check if script can read file
        $this->assertTrue($factory->readConfig($config) !== false);
    }

    public function testLoadConfig()
    {
        $factory = new Client();
        $factory->_config = include __DIR__ . '/pcr.example.php';

        // Check if script can load the file
        $this->assertTrue($factory->loadConfig());
    }

    public function testDoRequest()
    {
        $factory = new Client();

        // Check if request return the array
        $result = $factory->doRequest('test', 'test', array('test' => 'test'));
        $this->assertTrue(is_array($result));
    }

    public function testIsMulti()
    {
        $factory = new Client();

        // Check if array is not multidimensional
        $this->assertFalse($factory->isMulti(array()));

        // Check if array is multidimensional
        $this->assertTrue($factory->isMulti(array(array())));
    }

    public function testCompileURL()
    {
        $factory = new Client();

        // One dimensional check
        $this->assertTrue($factory->compileURL(['test' => 'test']) == '?Query=test eq test');

        // Multidimensional check 1
        $this->assertTrue($factory->compileURL(['query' => ['test' => 'test']]) == '?Query=test eq test');

        // Multidimensional check 2
        $this->assertTrue($factory->compileURL(['query' => ['test1' => 'test1', 'test2' => 'test2']]) == '?Query=test1 eq test1,test2 eq test2');

        // Multidimensional check 3
        $this->assertTrue($factory->compileURL(['query' => ['test1' => 'test1'], 'custom' => ['test1', 'test2']]) == '?Query=test1 eq test1&Custom=test1,test2');
    }

}
