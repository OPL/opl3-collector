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
	} // end testCacheSetButMissed();

	public function testCacheSetAndHit()
	{
		$cacheMock = $this->getMockForAbstractClass('\\Opl\\Cache\\Cache', array(0 => array('prefix' => 'mock', 'lifetime' => 100)));
		$cacheMock->expects($this->once())
			->method('get')
			->with($this->equalTo('collector'))
			->will($this->returnValue(array('foo' => 'bar')));

		$collector = new Collector($cacheMock);
		$this->assertTrue($collector->isCached());
	} // end testCacheSetAndHit();

	public function testLoadFromArrayAsRootDoesRecursiveMerging()
	{
		$collector = new Collector();
		$collector->loadFromArray(Collector::ROOT, array(
			'foo' => 'foo value',
			'bar' => array(
				'joe' => 'joe value'
			)
		));

		$this->assertEquals('foo value', $collector->get('foo'));
		$this->assertEquals('joe value', $collector->get('bar.joe'));
		$this->assertSame(null, $collector->get('goo', Collector::THROW_NULL));
		$this->assertSame(null, $collector->get('bar.hoo', Collector::THROW_NULL));

		$collector->loadFromArray(Collector::ROOT, array(
			'goo' => 'goo value',
			'bar' => array(
				'hoo' => 'hoo value'
			)
		));

		$this->assertEquals('foo value', $collector->get('foo'));
		$this->assertEquals('joe value', $collector->get('bar.joe'));
		$this->assertEquals('goo value', $collector->get('goo'));
		$this->assertEquals('hoo value', $collector->get('bar.hoo'));
	} // end testLoadFromArrayAsRootDoesRecursiveMerging();

	public function testLoadFromArrayAsNestedDoesRecursiveMerging()
	{
		$collector = new Collector();
		$collector->loadFromArray(Collector::ROOT, array(
			'foo' => 'foo value',
			'bar' => array(
				'joe' => 'joe value'
			)
		));

		$this->assertEquals('foo value', $collector->get('foo'));
		$this->assertEquals('joe value', $collector->get('bar.joe'));
		$this->assertSame(null, $collector->get('goo', Collector::THROW_NULL));
		$this->assertSame(null, $collector->get('bar.hoo', Collector::THROW_NULL));
		$this->assertSame(null, $collector->get('bar.goo', Collector::THROW_NULL));
		$this->assertSame(null, $collector->get('bar.bar.hoo', Collector::THROW_NULL));

		$collector->loadFromArray('bar', array(
			'goo' => 'goo value',
			'bar' => array(
				'hoo' => 'hoo value'
			)
		));

		$this->assertEquals('foo value', $collector->get('foo'));
		$this->assertEquals('joe value', $collector->get('bar.joe'));
		$this->assertSame(null, $collector->get('goo', Collector::THROW_NULL));
		$this->assertSame(null, $collector->get('bar.hoo', Collector::THROW_NULL));
		$this->assertEquals('goo value', $collector->get('bar.goo'));
		$this->assertEquals('hoo value', $collector->get('bar.bar.hoo'));
	} // end testLoadFromArrayAsNestedDoesRecursiveMerging();

	public function testLoadFromLoaderAsRootDoesRecursiveMerging()
	{
		$loaderMock = $this->getMock('\\Opl\\Collector\\LoaderInterface');
		$loaderMock->expects($this->once())
			->method('import')
			->will($this->returnValue(array(
				'goo' => 'goo value',
				'bar' => array(
					'hoo' => 'hoo value'
				)
			)
		));

		$collector = new Collector();
		$collector->loadFromArray(Collector::ROOT, array(
			'foo' => 'foo value',
			'bar' => array(
				'joe' => 'joe value'
			)
		));

		$this->assertEquals('foo value', $collector->get('foo'));
		$this->assertEquals('joe value', $collector->get('bar.joe'));
		$this->assertSame(null, $collector->get('goo', Collector::THROW_NULL));
		$this->assertSame(null, $collector->get('bar.hoo', Collector::THROW_NULL));

		$collector->loadFromLoader(Collector::ROOT, $loaderMock);

		$this->assertEquals('foo value', $collector->get('foo'));
		$this->assertEquals('joe value', $collector->get('bar.joe'));
		$this->assertEquals('goo value', $collector->get('goo'));
		$this->assertEquals('hoo value', $collector->get('bar.hoo'));
	} // end testLoadFromLoaderAsRootDoesRecursiveMerging();

	public function testLoadFromLoaderAsNestedDoesRecursiveMerging()
	{
		$loaderMock = $this->getMock('\\Opl\\Collector\\LoaderInterface');
		$loaderMock->expects($this->once())
			->method('import')
			->will($this->returnValue(array(
				'goo' => 'goo value',
				'bar' => array(
					'hoo' => 'hoo value'
				)
			)
		));

		$collector = new Collector();
		$collector->loadFromArray(Collector::ROOT, array(
			'foo' => 'foo value',
			'bar' => array(
				'joe' => 'joe value'
			)
		));

		$this->assertEquals('foo value', $collector->get('foo'));
		$this->assertEquals('joe value', $collector->get('bar.joe'));
		$this->assertSame(null, $collector->get('goo', Collector::THROW_NULL));
		$this->assertSame(null, $collector->get('bar.hoo', Collector::THROW_NULL));
		$this->assertSame(null, $collector->get('bar.goo', Collector::THROW_NULL));
		$this->assertSame(null, $collector->get('bar.bar.hoo', Collector::THROW_NULL));

		$collector->loadFromLoader('bar', $loaderMock);

		$this->assertEquals('foo value', $collector->get('foo'));
		$this->assertEquals('joe value', $collector->get('bar.joe'));
		$this->assertSame(null, $collector->get('goo', Collector::THROW_NULL));
		$this->assertSame(null, $collector->get('bar.hoo', Collector::THROW_NULL));
		$this->assertEquals('goo value', $collector->get('bar.goo'));
		$this->assertEquals('hoo value', $collector->get('bar.bar.hoo'));
	} // end testLoadFromLoaderAsNestedDoesRecursiveMerging();

	/**
	 * @expectedException BadMethodCallException
	 */
	public function testSaveThrowsExceptionWhenNoCacheIsInstalled()
	{
		$collector = new Collector();
		$collector->save();
	} // end testSaveThrowsExceptionWhenNoCacheIsInstalled();

	public function testSaveSavesTheDataInTheCache()
	{
		$cacheMock = $this->getMockForAbstractClass('\\Opl\\Cache\\Cache', array(0 => array('prefix' => 'mock', 'lifetime' => 100)));
		$cacheMock->expects($this->once())
			->method('set')
			->with(
				$this->equalTo('collector'),
				$this->equalTo(array('foo' => 'bar'))
			)
			->will($this->returnValue(true));

		$collector = new Collector($cacheMock);
		$collector->loadFromArray(Collector::ROOT, array('foo' => 'bar'));
		$collector->save();
	} // end testSaveSavesTheDataInTheCache();
} // end CollectorTest;