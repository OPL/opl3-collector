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
use Opl\Collector\Configuration\IniFileLoader;

/**
 * @covers \Opl\Collector\Configuration\IniFileLoader
 * @runTestsInSeparateProcesses
 */
class IniFileLoaderTest extends \PHPUnit_Framework_TestCase
{
	public function testLoadingValidIniFile()
	{
		try
		{
			$loader = new IniFileLoader('./data/');
			$loader->setFile('file.ini');

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
	} // end testLoadingValidIniFile();

	/**
	 * @expectedException BadMethodCallException
	 */
	public function testImportThrowsExceptionWhenNoFileDefined()
	{
		$loader = new IniFileLoader('./data/');
		$collector = new Collector();
		$collector->loadFromLoader(Collector::ROOT, $loader);
	} // end testImportThrowsExceptionWhenNoFileDefined();

	/**
	 * @expectedException \Opl\Collector\Exception\LoaderException
	 */
	public function testImportThrowsExceptionWhenFileIsInvalid()
	{
		$loader = new IniFileLoader('./data/');
		$loader->setFile('invalid1.ini');
		$collector = new Collector();
		$collector->loadFromLoader(Collector::ROOT, $loader);
	} // end testImportThrowsExceptionWhenFileIsInvalid();

	/**
	 * @expectedException \Opl\Collector\Exception\LoaderException
	 */
	public function testImportDoesNotAcceptSavingScalarsAsCollections()
	{
		$loader = new IniFileLoader('./data/');
		$loader->setFile('invalid2.ini');
		$collector = new Collector();
		$collector->loadFromLoader(Collector::ROOT, $loader);
	} // end testImportDoesNotAcceptSavingScalarsAsCollections();

	/**
	 * @expectedException \Opl\Collector\Exception\LoaderException
	 */
	public function testImportDoesNotAcceptSavingCollectionsAsScalars()
	{
		$loader = new IniFileLoader('./data/');
		$loader->setFile('invalid3.ini');
		$collector = new Collector();
		$collector->loadFromLoader(Collector::ROOT, $loader);
	} // end testImportDoesNotAcceptSavingCollectionsAsScalars();
} // end IniFileLoaderTest;