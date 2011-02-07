<?php
/**
 * The test suite file that configures the execution of the test cases.
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
namespace TestSuite\Visit;

class AllTests extends \PHPUnit_Framework_TestSuite
{
	public static function suite()
	{
		$suite = new AllTests('TestSuite\\Visit');

		$suite->addTestSuite('TestSuite\\Visit\\HostLoaderTest');
		$suite->addTestSuite('TestSuite\\Visit\\ConnectionLoaderTest');

		return $suite;
	} // end suite();
} // end AllTests;