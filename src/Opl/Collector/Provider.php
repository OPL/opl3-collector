<?php
/*
 *  OPEN POWER LIBS <http://www.invenzzia.org>
 *
 * This file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE. It is also available through
 * WWW at this URL: <http://www.invenzzia.org/license/new-bsd>
 *
 * Copyright (c) Invenzzia Group <http://www.invenzzia.org>
 * and other contributors. See website for details.
 */
namespace Opl\Collector;
use Opl\Collector\Exception\InvalidKeyException;
use Opl\Collector\Exception\UnexpectedScalarException;

/**
 * This is a default implementation of the data provider. It provides the
 * data for various system services. You should not instantiate this class
 * explicitely, as it does not provide any way to inject the data to it.
 *
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class Provider implements ProviderInterface
{
	/**
	 * The stored data, in a form of tree.
	 * @var array
	 */
	protected $data;

	/**
	 * @throws \Opl\Collector\Exception\UnexpectedScalarException
	 * @see ProviderInterface
	 */
	public function get($key, $errorReporting = self::THROW_EXCEPTION)
	{
		$path = $this->processKey($key);
		$size = sizeof($path);
		$data = &$this->data;
		for($i = 0; $i < $size; $i++)
		{
			if(!isset($data[$path[$i]]))
			{
				if(self::THROW_EXCEPTION == $errorReporting)
				{
					throw new InvalidKeyException('The key \''.$key.'\' is not defined. The problem is with: \''.$path[$i].'\'');
				}
				return null;
			}
			if($data[$path[$i]] instanceof LoaderInterface)
			{
				$this->_loadFromArray($data[$path[$i]], $data[$path[$i]]->import());
			}
			if(is_array($data[$path[$i]]))
			{
				if($i == $size - 1)
				{
					$value = new Provider;
					$value->data = &$data[$path[$i]];
					return $value;
				}
				else
				{
					$data = &$data[$path[$i]];
				}
			}
			elseif($i == $size - 1)
			{
				return $data[$path[$i]];
			}
			else
			{
				throw new UnexpectedScalarException('Cannot retrieve \''.$key.'\': \''.$path[$i].' is a scalar value.');
			}
		}
	} // end get();

	/**
	 * An internal method for translating the key to the parts.
	 * 
	 * @param string $key The value key.
	 * @return array
	 */
	protected function processKey($key)
	{
		return explode('.', $key);
	} // end processKey();

	/**
	 * Finds a key in the array, and returns a reference to it, so that it could
	 * be modified.
	 *
	 * @throws \Opl\Collector\Exception\UnexpectedScalarException
	 * @param string $key The value key.
	 * @return array
	 */
	protected function &findKey($key)
	{
		$path = $this->processKey($key);
		
		$size = sizeof($path);
		$data = &$this->data;
		for($i = 0; $i < $size; $i++)
		{
			if(!isset($data[$path[$i]]))
			{
				$data[$path[$i]] = array();
			}
			if(!is_array($data[$path[$i]]) && $i < $size - 1)
			{
				throw new UnexpectedScalarException('Cannot retrieve \''.$key.'\': \''.$path[$i].' is a scalar value.');
			}
			$data = &$data[$path[$i]];
		}
		return $data;
	} // end findKey();

	/**
	 * Loads the data to the collector from the specified array. If the path is
	 * different than <tt>self::ROOT</tt>, the data are appended under the specified
	 * path.
	 *
	 * @param string|null $path The path, where the data should be saved. Null means root.
	 * @param array $array The array with the data.
	 */
	protected function _loadFromArray(&$partial, array $array)
	{
		$partial = array_merge_recursive($partial, $array);
	} // end _loadFromArray();
} // end Provider;