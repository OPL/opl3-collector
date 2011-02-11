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
use Opl\Collector\Exception\LoaderException;

/**
 * Loads the collection data from an INI file.
 *
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class IniFileLoader extends FileLoader
{
	/**
	 * @see LoaderInterface
	 */
	public function import()
	{
		if(null === $this->currentFile)
		{
			throw new BadMethodCallException('Cannot load an INI file: no file specified');
		}

		$ini = @parse_ini_file($this->findFile($this->currentFile));

		if(false === $ini)
		{
			throw new LoaderException($this->currentFile.' is not a valid INI file.');
		}

		$data = array();
		foreach($ini as $name => $value)
		{
			$path = explode('.', $name);
			$cnt = sizeof($path);
			$item = &$data;
			for($i = 0; $i < $cnt; $i++)
			{
				if(!isset($item[$path[$i]]))
				{
					if($i + 1 == $cnt)
					{
						$item[$path[$i]] = $value;
					}
					else
					{
						$item[$path[$i]] = array();
						$item = &$item[$path[$i]];
					}
				}
				elseif(!is_array($item[$path[$i]]))
				{
					throw new LoaderException('Cannot treat the scalar value as a collection in key: '.$name);
				}
				else
				{
					$item = &$item[$path[$i]];
				}
			}
		}

		return $data;
	} // end import();
} // end IniFileLoader;