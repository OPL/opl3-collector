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
use Opl\Collector\Exception\UnexpectedCollectionException;
use BadMethodCallException;
use DomainException;
use Serializable;

/**
 * This class provides a complete implementation of the data provider. It allows
 * both to retrieve the data, and to inject it.
 *
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class Collector extends Provider implements Serializable
{
	const ROOT = null;

	/**
	 * Creates the collector object.
	 */
	public function __construct()
	{
		$this->data = array();
	} // end __construct();

	/**
	 * Loads the data to the collector from a loader. If the path is different
	 * than <tt>self::ROOT</tt>, the data are appended under the specified path.
	 * If the loader does not return an array, the method throws an exception.
	 * Implements fluent interface.
	 *
	 * @throws DomainException
	 * @param string|null $path The path, where the data should be saved. Null means the root.
	 * @param LoaderInterface $loader The loader used to load the data.
	 * @return Collector
	 */
	public function loadFromLoader($path, LoaderInterface $loader)
	{
		$data = $loader->import($this);
		
		if(!is_array($data))
		{
			throw new DomainException('Cannot load the data under \''.$path.'\': The loader must return an array.');
		}

		if(null === $path)
		{
			$this->_loadFromArray($this->data, $data);
		}
		else
		{
			$this->_loadFromArray($this->findKey($path), $data);
		}

		return $this;
	} // end loadFromLoader();

	/**
	 * Loads the data to the collector from the specified array. If the path is
	 * different than <tt>self::ROOT</tt>, the data are appended under the specified
	 * path. Implements fluent interface
	 * 
	 * @param string|null $path The path, where the data should be saved. Null means root.
	 * @param array $array The array with the data.
	 * @return Opl\Collector\Collector
	 */
	public function loadFromArray($path, array $array)
	{
		if(null === $path)
		{
			$this->_loadFromArray($this->data, $array);
		}
		else
		{
			$this->_loadFromArray($this->findKey($path), $array);
		}

		return $this;
	} // end loadFromArray();

	/**
	 * Installs the lazy loader under the given path. It will be called
	 * automatically during the first reference to one of the options
	 * from this path. Implements fluent interface.
	 *
	 * @throws UnexpectedCollectionException
	 * @param string $path The data path
	 * @param LoaderInterface $loader
	 * @return Collector
	 */
	public function setLazyLoader($path, LoaderInterface $loader)
	{
		$partial = &$this->findKey($path);

		if(is_array($partial) && sizeof($partial) > 0)
		{
			throw new UnexpectedCollectionException('The path \''.$path.'\' is already occupied by a collection.');
		}
		$partial = $loader;

		return $this;
	} // end setLazyLoader();

	/**
	 * Serializes the data for the purposes of caching.
	 */
	public function serialize()
	{
		return serialize($this->data);
	} // end serialize();

	/**
	 * Unserializes the object loaded from the cache.
	 * @param string $string
	 */
	public function unserialize($string)
	{
		$this->data = unserialize($string);
	} // end unserialize();
} // end Collector;