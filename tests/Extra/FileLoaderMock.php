<?php
/**
 * Unit tests for Open Power Collector
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
namespace Extra;
use Opl\Collector\ProviderInterface;
use Opl\Collector\Configuration\FileLoader;

class FileLoaderMock extends FileLoader
{
	public function import(ProviderInterface $provider)
	{
		return array();
	} // end import();

	public function _getPaths()
	{
		return $this->_paths;
	} // end getPaths();
} // end FileLoaderMock;