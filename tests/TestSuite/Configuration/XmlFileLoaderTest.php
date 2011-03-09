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
use Opl\Collector\Configuration\XmlFileLoader;

/**
 * @covers \Opl\Collector\Configuration\XmlFileLoader
 * @runTestsInSeparateProcesses
 */
class XmlFileLoaderTest extends \PHPUnit_Framework_TestCase
{
	public function testLoadingValidXmlFile()
	{
		try
		{
			$loader = new XmlFileLoader('./data/');
			$loader->setFile('file.xml');

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
	} // end testLoadingValidXmlFile();

	/**
	 * @expectedException BadMethodCallException
	 */
	public function testImportThrowsExceptionWhenNoFileDefined()
	{
		$loader = new XmlFileLoader('./data/');
		$collector = new Collector();
		$collector->loadFromLoader(Collector::ROOT, $loader);
	} // end testImportThrowsExceptionWhenNoFileDefined();

	/**
	 * @expectedException \Opl\Collector\Exception\XmlValidityException
	 */
	public function testImportThrowsExceptionWhenNameIsNotDefined()
	{
		$loader = new XmlFileLoader('./data/');
		$loader->setFile('invalid1.xml');
		$collector = new Collector();
		$collector->loadFromLoader(Collector::ROOT, $loader);
	} // end testImportThrowsExceptionWhenNameIsNotDefined();

	/**
	 * @expectedException \Opl\Collector\Exception\XmlValidityException
	 */
	public function testImportThrowsExceptionOnUnknownTag()
	{
		$loader = new XmlFileLoader('./data/');
		$loader->setFile('invalid2.xml');
		$collector = new Collector();
		$collector->loadFromLoader(Collector::ROOT, $loader);
	} // end testImportThrowsExceptionOnUnknownTag();
} // end XmlFileLoaderTest;