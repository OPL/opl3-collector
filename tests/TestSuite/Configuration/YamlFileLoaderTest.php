<?php
/**
 * Unit tests for Open Power Collector
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
namespace TestSuite\Configuration;
use Exception;
use Opl\Collector\Collector;
use Opl\Collector\Configuration\YamlFileLoader;

/**
 * @covers \Opl\Collector\Configuration\YamlFileLoader
 * @runTestsInSeparateProcesses
 */
class YamlFileLoaderTest extends \PHPUnit_Framework_TestCase
{
	public function testLoadingValidYamlFile()
	{
		try
		{
			$loader = new YamlFileLoader('./data/');
			$loader->setFile('file.yml');

			$collector = new Collector();
			$collector->loadFromLoader(Collector::ROOT, $loader);

			$this->assertEquals('value 1', $collector->get('foo.bar.joe'));
			$this->assertEquals('value 2', $collector->get('foo.bar.goo'));
			$this->assertEquals('value 3', $collector->get('foo.hoo'));
			$this->assertEquals('value 4', $collector->get('loo'));
		}
		catch(Exception $exception)
		{
			$this->fail('Exception '.get_class($exception).': '.$exception->getMessage());
		}
	} // end testLoadingValidYamlFile();

	/**
	 * @expectedException BadMethodCallException
	 */
	public function testImportThrowsExceptionWhenNoFileDefined()
	{
		$loader = new YamlFileLoader('./data/');
		$collector = new Collector();
		$collector->loadFromLoader(Collector::ROOT, $loader);
	} // end testImportThrowsExceptionWhenNoFileDefined();
} // end YamlFileLoaderTest;