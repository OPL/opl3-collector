<?php
/**
 * Unit tests for Open Power Collector
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
namespace TestSuite\Visit;
use Opl\Collector\Visit\HostLoader;
use Opl\Collector\Collector;

/**
 * @covers \Opl\Collector\Visit\HostLoader
 * @runTestsInSeparateProcesses
 */
class HostLoaderTest extends \PHPUnit_Framework_TestCase
{
	public function testIPv4Importing()
	{
		$_SERVER['REMOTE_ADDR'] = '12.34.56.78';

		$collector = new Collector();
		$collector->loadFromLoader(Collector::ROOT, new HostLoader());

		$this->assertEquals('12.34.56.78', $collector->get('ip'));
		$this->assertEquals(chr(12).chr(34).chr(56).chr(78), $collector->get('binaryIp'));
		$this->assertEquals(4, $collector->get('ipVersion'));
	} // end testIPv4Importing();

	public function testIPv6Importing()
	{
		$_SERVER['REMOTE_ADDR'] = '2001:0db8:85a3:0000:0000:8a2e:0370:7334';

		$collector = new Collector();
		$collector->loadFromLoader(Collector::ROOT, new HostLoader());

		$this->assertEquals('2001:0db8:85a3:0000:0000:8a2e:0370:7334', $collector->get('ip'));
		$this->assertEquals(inet_pton('2001:0db8:85a3:0000:0000:8a2e:0370:7334'), $collector->get('binaryIp'));
		$this->assertEquals(6, $collector->get('ipVersion'));
	} // end testIPv6Importing();
} // end HostLoaderTest;