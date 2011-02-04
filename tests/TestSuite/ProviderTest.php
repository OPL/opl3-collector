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

/**
 * @covers \Opl\Collector\Provider
 * @runTestsInSeparateProcesses
 */
class ProviderTest extends \PHPUnit_Framework_TestCase
{
	public function testGetReturnsRootData()
	{
		$provider = new Provider;

		$reflClass = new ReflectionObject($provider);
		$reflData = $reflClass->getProperty('data');
		$reflData->setAccessible(true);

		$reflData->setValue($provider, array('foo' => 'bar'));

		$this->assertEquals('bar', $provider->get('foo'));
	} // end testGetReturnsRootData();

	public function testGetReturnsNestedData()
	{
		$provider = new Provider;

		$reflClass = new ReflectionObject($provider);
		$reflData = $reflClass->getProperty('data');
		$reflData->setAccessible(true);

		$reflData->setValue($provider, array('foo' => 'bar', 'joe' => array('goo' => 'hoo')));

		$this->assertEquals('hoo', $provider->get('joe.goo'));
	} // end testGetReturnsNestedData();

	/**
	 * @expectedException Opl\Collector\Exception\InvalidKeyException
	 */
	public function testGetThrowsExceptionWhenRootDataDoesNotExist()
	{
		$provider = new Provider;

		$reflClass = new ReflectionObject($provider);
		$reflData = $reflClass->getProperty('data');
		$reflData->setAccessible(true);

		$reflData->setValue($provider, array('foo' => 'bar', 'joe' => array('goo' => 'hoo')));

		$provider->get('loo');
	} // end testGetThrowsExceptionWhenRootDataDoesNotExist();

	/**
	 * @expectedException Opl\Collector\Exception\InvalidKeyException
	 */
	public function testGetThrowsExceptionWhenNestedDataDoesNotExist()
	{
		$provider = new Provider;

		$reflClass = new ReflectionObject($provider);
		$reflData = $reflClass->getProperty('data');
		$reflData->setAccessible(true);

		$reflData->setValue($provider, array('foo' => 'bar', 'joe' => array('goo' => 'hoo')));

		$this->assertEquals('hoo', $provider->get('joe.goo'));
		$provider->get('joe.hoo');
	} // end testGetThrowsExceptionWhenNestedDataDoesNotExist();

	/**
	 * @expectedException Opl\Collector\Exception\InvalidKeyException
	 */
	public function testGetThrowsExceptionExplicit()
	{
		$provider = new Provider;

		$reflClass = new ReflectionObject($provider);
		$reflData = $reflClass->getProperty('data');
		$reflData->setAccessible(true);

		$reflData->setValue($provider, array('foo' => 'bar', 'joe' => array('goo' => 'hoo')));

		$this->assertEquals('hoo', $provider->get('joe.goo'));
		$provider->get('joe.hoo', Provider::THROW_EXCEPTION);
	} // end testGetThrowsExceptionWhenNestedDataDoesNotExist();

	public function testGetReturnsNullForRootData()
	{
		$provider = new Provider;

		$reflClass = new ReflectionObject($provider);
		$reflData = $reflClass->getProperty('data');
		$reflData->setAccessible(true);

		$reflData->setValue($provider, array('foo' => 'bar', 'joe' => array('goo' => 'hoo')));

		$this->assertSame(null, $provider->get('loo', Provider::THROW_NULL));
	} // end testGetReturnsNullForRootData();

	public function testGetReturnsNullForNestedData()
	{
		$provider = new Provider;

		$reflClass = new ReflectionObject($provider);
		$reflData = $reflClass->getProperty('data');
		$reflData->setAccessible(true);

		$reflData->setValue($provider, array('foo' => 'bar', 'joe' => array('goo' => 'hoo')));

		$this->assertSame(null, $provider->get('joe.hoo', Provider::THROW_NULL));
	} // end testGetReturnsNullForNestedData();

	/**
	 * @expectedException Opl\Collector\Exception\UnexpectedScalarException
	 */
	public function testGetFailsAtScalarDereferencing()
	{
		$provider = new Provider;

		$reflClass = new ReflectionObject($provider);
		$reflData = $reflClass->getProperty('data');
		$reflData->setAccessible(true);

		$reflData->setValue($provider, array('foo' => 'bar', 'joe' => array('goo' => 'hoo')));

		$provider->get('foo.moo');
	} // end testGetFailsAtScalarDereferencing();

	public function testGetReturnsSubproviderForTheData()
	{
		$provider = new Provider;

		$reflClass = new ReflectionObject($provider);
		$reflData = $reflClass->getProperty('data');
		$reflData->setAccessible(true);

		$reflData->setValue($provider, array('foo' => 'bar', 'joe' => array('goo' => 'hoo')));

		$subprovider = $provider->get('joe');
		$this->assertEquals('hoo', $subprovider->get('goo'));
	} // end testGetReturnsSubproviderForTheData();
} // end ProviderTest;