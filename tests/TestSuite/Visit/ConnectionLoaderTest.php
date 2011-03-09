<?php
/**
 * Unit tests for Open Power Collector
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
namespace TestSuite\Visit;
use Opl\Collector\Visit\ConnectionLoader;
use Opl\Collector\Collector;

/**
 * @covers \Opl\Collector\Visit\ConnectionLoader
 * @runTestsInSeparateProcesses
 */
class ConnectionLoaderTest extends \PHPUnit_Framework_TestCase
{
	public function testSettingPort80()
	{
		$_SERVER['SERVER_PORT'] = 80;
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.0';

		$collector = new Collector();
		$collector->loadFromLoader(Collector::ROOT, new ConnectionLoader());

		$this->assertEquals(80, $collector->get('port'));
		$this->assertFalse($collector->get('isSecure'));
		$this->assertEquals('GET', $collector->get('method'));
		$this->assertEquals('http', $collector->get('protocol'));
	} // end testSettingPort80();

	public function testSettingPort443()
	{
		$_SERVER['SERVER_PORT'] = 443;
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_SERVER['SERVER_PROTOCOL'] = 'HTTPS/1.1';

		$collector = new Collector();
		$collector->loadFromLoader(Collector::ROOT, new ConnectionLoader());

		$this->assertEquals(443, $collector->get('port'));
		$this->assertTrue($collector->get('isSecure'));
		$this->assertEquals('POST', $collector->get('method'));
		$this->assertEquals('https', $collector->get('protocol'));
	} // end testSettingPort443();
} // end ConnectionLoaderTest;