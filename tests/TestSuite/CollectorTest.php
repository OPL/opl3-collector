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

	public function testLoadFromArrayNestedAddsUnexistingKeys()
	{
		$collector = new Collector();
		$collector->loadFromArray('bar.hoo.loo', array(
			'foo' => 'foo value',
			'bar' => array(
				'joe' => 'joe value'
			)
		));
	} // end testLoadFromArrayNestedAddsUnexistingKeys();

	/**
	 * @expectedException Opl\Collector\Exception\UnexpectedScalarException
	 */
	public function testLoadFromLoaderNestedFailsAtScalarDereferencing()
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

		$collector->loadFromLoader('bar.joe.loo', $loaderMock);
	} // end testLoadFromLoaderNestedNoticesUnexpectedScalars();

	public function testLoadFromLoaderReturnsFalseIfTheLoaderDoesNotReturnArray()
	{
		$loaderMock1 = $this->getMock('\\Opl\\Collector\\LoaderInterface');
		$loaderMock1->expects($this->once())
			->method('import')
			->will($this->returnValue(42));

		$loaderMock2 = $this->getMock('\\Opl\\Collector\\LoaderInterface');
		$loaderMock2->expects($this->once())
			->method('import')
			->will($this->returnValue(42));

		$collector = new Collector();
		$collector->loadFromArray(Collector::ROOT, array('foo' => array('bar' => 10)));
		$this->assertFalse($collector->loadFromLoader(Collector::ROOT, $loaderMock1));
		$this->assertFalse($collector->loadFromLoader('foo', $loaderMock2));
	} // end testLoadFromLoaderReturnsFalseIfTheLoaderDoesNotReturnArray();

	public function testSerialization()
	{
		$collector = new Collector();
		$collector->loadFromArray(Collector::ROOT, array(
			'foo' => 'foo value',
			'bar' => array(
				'joe' => 'joe value'
			)
		));

		$string = serialize($collector);
		$otherCollector = unserialize($string);
		$this->assertEquals('foo value', $otherCollector->get('foo'));
		$this->assertEquals('joe value', $otherCollector->get('bar.joe'));
	} // end testCacheNotSet();

	public function testSetLazyLoaderInstallsLazyLoader()
	{
		$loaderMock1 = $this->getMock('\\Opl\\Collector\\LoaderInterface');
		$loaderMock1->expects($this->once())
			->method('import')
			->will($this->returnValue(array('bar' => 42)));

		$collector = new Collector();
		$collector->setLazyLoader('foo', $loaderMock1);
		$this->assertEquals(42, $collector->get('foo.bar'));
	} // end testSetLazyLoaderInstallsLazyLoader();

	/**
	 * @expectedException Opl\Collector\Exception\UnexpectedCollectionException
	 */
	public function testSetLazyLoaderThrowsExceptionWhenPathIsOccupiedByCollection()
	{
		$loaderMock1 = $this->getMock('\\Opl\\Collector\\LoaderInterface');

		$collector = new Collector();

		$collector->loadFromArray(Collector::ROOT, array(
			'foo' => array(
				'joe' => 'joe value'
			)
		));

		$collector->setLazyLoader('foo', $loaderMock1);
		$collector->get('foo.bar');
	} // end testSetLazyLoaderThrowsExceptionWhenPathIsOccupiedByCollection();
} // end CollectorTest;