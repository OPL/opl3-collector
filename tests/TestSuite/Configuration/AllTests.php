<?php
/**
 * The test suite file that configures the execution of the test cases.
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
namespace TestSuite\Configuration;

class AllTests extends \PHPUnit_Framework_TestSuite
{
	public static function suite()
	{
		$suite = new AllTests('TestSuite\\Configuration');

		$suite->addTestSuite('TestSuite\\Configuration\\FileLoaderTest');
		$suite->addTestSuite('TestSuite\\Configuration\\IniFileLoaderTest');
		$suite->addTestSuite('TestSuite\\Configuration\\XmlFileLoaderTest');
		$suite->addTestSuite('TestSuite\\Configuration\\YamlFileLoaderTest');

		return $suite;
	} // end suite();
} // end AllTests;