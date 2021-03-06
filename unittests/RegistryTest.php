<?php

use Famework\Registry\Famework_Registry;

// backward compatibility
if (!class_exists('\PHPUnit\Framework\TestCase') &&
        class_exists('\PHPUnit_Framework_TestCase')) {
    class_alias('\PHPUnit_Framework_TestCase', '\PHPUnit\Framework\TestCase');
}

class RegistryTest extends \PHPUnit\Framework\TestCase {

    public function setUp() {
        try {
            Famework_Registry::set('foo', 'bar');
            Famework_Registry::setEnv('Test');
        } catch (\Exception $e) {
            
        }
    }

    public function testGet() {
        $this->assertEquals('bar', Famework_Registry::get('foo'));
        $this->assertEquals(NULL, Famework_Registry::getView());
        $this->assertEquals('Test', Famework_Registry::getEnv());
    }

    /**
     * @expectedException Famework\Registry\Famework_Exception_Registry_Error
     */
    public function testGetEmpty() {
        Famework_Registry::get('empty', TRUE);
    }

    /**
     * @expectedException Famework\Db\Famework_Exception_Database_Fail
     */
    public function testGetDb() {
        Famework_Registry::getDb();
    }

}
