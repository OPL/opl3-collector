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
use RuntimeException;
use SplQueue;
use Symfony\Component\Yaml\Yaml;

class XmlFileLoader extends FileLoader
{
	public function import()
	{
		if(null === $this->currentFile)
		{
			throw new BadMethodCallException('Cannot load an XML file: no file specified');
		}

		$data = \simplexml_load_file($this->findFile($this->_currentFile));

		$queue = new SplQueue;
		$opts = array();
		$root = $this->_groupFactory($opts, $data->group, $queue);
		

		while($queue->count() > 0)
		{
			list($localRoot, $pageDesc) = $queue->dequeue();
			$this->_groupFactory($localRoot, $data, $queue);
		}

		return $opts;
	} // end import();

	protected function _groupFactory($root, $data, SplQueue $queue)
	{
		foreach($data as $xmlElement)
		{
			if(!isset($xmlElement['name']))
			{
				throw new RuntimeException('Cannot load an XML file: the \''.$xmlElement->getName().'\' element has no \'name\' attribute.');
			}
			$name = (string)$xmlElement['name'];
			switch($xmlElement->getName())
			{
				case 'item':
					$root[$name] = $xmlElement->__toString();
					break;
				case 'group':
					$root[$name] = array();
					foreach($xmlElement as $subElement)
					{
						$queue->enqueue(array(&$root[$name], $subElement));
					}
				default:
					throw new RuntimeException('Cannot load an XML file: unknown element: \''.$xmlElement->getName().'\'');
			}
		}
	} // end _groupFactory();
} // end XmlFileLoader;