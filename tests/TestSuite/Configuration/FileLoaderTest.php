<?php
/**
 * Unit tests for Open Power Collector
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
namespace TestSuite\Configuration;
use Extra\FileLoaderMock;
use Opl\Collector\Configuration\FileLoader;

/**
 * @covers \Opl\Collector\Configuration\FileLoader
 * @runTestsInSeparateProcesses
 */
class FileLoaderTest extends \PHPUnit_Framework_TestCase
{
	public function testConstructAcceptsSinglePathAsString()
	{
		$fileLoader = new FileLoaderMock('./foo/');
		$this->assertSame(array(0 => './foo/'), $fileLoader->_getPaths());
	} // end testConstructAcceptsSinglePathAsString();

	public function testConstructAcceptsManyPathsAsArray()
	{
		$fileLoader = new FileLoaderMock(array('./foo/', './bar/'));
		$this->assertSame(array(0 => './foo/', './bar/'), $fileLoader->_getPaths());
	} // end testConstructAcceptsManyPathsAsArray();

	public function testConstructAddsTrailingSlashes()
	{
		$fileLoader = new FileLoaderMock(array('./foo/', './bar'));
		$this->assertSame(array(0 => './foo/', './bar/'), $fileLoader->_getPaths());
	} // end testConstructAddsTrailingSlashes();

	public function testConstructAddsTrailingSlashesToEmptyStrings()
	{
		$fileLoader = new FileLoaderMock(array('./foo/', ''));
		$this->assertSame(array(0 => './foo/', '/'), $fileLoader->_getPaths());
	} // end testConstructAddsTrailingSlashesToEmptyStrings();

	public function testSettingFilename()
	{
		$fileLoader = new FileLoaderMock('./foo/');
		$fileLoader->setFile('foo.txt');
		$this->assertEquals('foo.txt', $fileLoader->getFile());

		$fileLoader->setFile('bar.txt');
		$this->assertEquals('bar.txt', $fileLoader->getFile());
	} // end testSettingFilename();
} // end FileLoaderTest;