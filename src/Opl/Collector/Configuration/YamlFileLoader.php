<?php
/*
 *  TRINITY FRAMEWORK <http://www.invenzzia.org>
 *
 * This file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE. It is also available through
 * WWW at this URL: <http://www.invenzzia.org/license/new-bsd>
 *
 * Copyright (c) Invenzzia Group <http://www.invenzzia.org>
 * and other contributors. See website for details.
 */
namespace Opl\Collector\Configuration;
use BadMethodCallException;
use Opl\Collector\ProviderInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Loads the collection data from a YAML file.
 *
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class YamlFileLoader extends FileLoader
{
	/**
	 * @see LoaderInterface
	 */
	public function import(ProviderInterface $provider)
	{
		if(null === $this->currentFile)
		{
			throw new BadMethodCallException('Cannot load a YAML file: no file specified');
		}

		return Yaml::load($this->findFile($this->currentFile));
	} // end import();
} // end YamlFileLoader;