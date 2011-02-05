<?php
/**
 * Unit tests for Open Power Collector
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
namespace TestSuite;
use ReflectionObject;
use Opl\Collector\Provider;
use Opl\Collector\Collector;

/**
 * @covers \Opl\Collector\Collector
 * @covers \Opl\Collector\Provider
 * @runTestsInSeparateProcesses
 */
class CollectorTest extends \PHPUnit_Framework_TestCase
{
	public function testCacheNotSet()
	{
		$collector = new Collector();
		$this->assertFalse($collector->isCached());
	} // end testCacheNotSet();

	public function testCacheSetButMissed()
	{
		$cacheMock = $this->getMockForAbstractClass('\\Opl\\Cache\\Cache', array(0 => array('prefix' => 'mock', 'lifetime' => 100)));
		$cacheMock->expects($this->once())
			->method('get')
			->with($this->equalTo('collector'))
			->will($this->returnValue(null));

		$collector = new Collector($cacheMock);
		$this->assertFalse($collector->isCached());
	} // end testCacheNotSet();
} // end CollectorTest;