<?php
/**
 * Unit tests for Open Power Collector
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
namespace TestSuite\Visit;
use Opl\Collector\Visit\BrowserLoader;
use Opl\Collector\Collector;

/**
 * @covers \Opl\Collector\Visit\BrowserLoader
 * @runTestsInSeparateProcesses
 */
class BrowserLoaderTest extends \PHPUnit_Framework_TestCase
{
	public function testMissingUserAgent()
	{
		unset($_SERVER['HTTP_USER_AGENT']);

		$collector = new Collector();
		$collector->loadFromLoader(Collector::ROOT, new BrowserLoader());

		$this->assertEquals(null, $collector->get('browser'));
		$this->assertEquals(null, $collector->get('browserName'));
		$this->assertEquals(null, $collector->get('version'));
		$this->assertEquals(null, $collector->get('platform'));
	} // end testMissingUserAgent();

	public function testTypicalUserAgent()
	{
		$_SERVER['HTTP_USER_AGENT'] = 'Opera/9.80 (Windows NT 6.1; U; en-US) Presto/2.7.62 Version/11.01';

		$collector = new Collector();
		$collector->loadFromLoader(Collector::ROOT, new BrowserLoader());

		$this->assertEquals('Opera 11.00', $collector->get('browser'));
		$this->assertEquals('Opera', $collector->get('browserName'));
		$this->assertEquals('11.00', $collector->get('version'));
		$this->assertEquals('Win7', $collector->get('platform'));
	} // end testTypicalUserAgent();
} // end BrowserLoaderTest;