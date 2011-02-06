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
use BadMethodCallException;
use Opl\Cache\Cache;

/**
 * This class provides a complete implementation of the data provider. It allows
 * both to retrieve the data, and to inject it.
 *
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class Collector extends Provider
{
	const CACHE_ENABLED = true;
	const CACHE_DISABLED = false;
	const ROOT = null;

	protected $cache;
	protected $cacheLoaded = false;
	protected $cacheKey;

	/**
	 * Creates the collector object. The constructor initializes the caching
	 * system, if it is called with arguments.
	 *
	 * @param Cache $cache The cache manager.
	 * @param string $cacheKey The cache key used to store the collection entries.
	 */
	public function __construct(Cache $cache = null, $cacheKey = 'collector')
	{
		if(null !== $cache)
		{
			$this->cache = $cache;
			$this->data = $cache->get($cacheKey);

			if(null === $this->data)
			{
				$this->data = array();
			}
			else
			{
				$this->cacheLoaded = true;
			}
			$this->cacheKey = $cacheKey;
		}
		else
		{
			$this->data = array();
		}
	} // end __construct();

	/**
	 * Checks if the data have been loaded from the cache.
	 *
	 * @return boolean
	 */
	public function isCached()
	{
		return $this->cacheLoaded;
	} // end isCached();

	/**
	 * Saves the already loaded data back to the cache, updating it. Do not
	 * call this method if the cache is not configured.
	 *
	 * @throws BadMethodCallException
	 */
	public function save()
	{
		if(null === $this->cache)
		{
			throw new BadMethodCallException('Cannot use Opl\\Collector\\Collector::save(): no cache system installed.');
		}
		$this->cache->set($this->cacheKey, $this->data);
	} // end save();

	/**
	 * Loads the data to the collector from a loader. If the path is different
	 * than <tt>self::ROOT</tt>, the data are appended under the specified path.
	 * 
	 * @param string|null $path The path, where the data should be saved. Null means the root.
	 * @param LoaderInterface $loader The loader used to load the data.
	 * @return boolean
	 */
	public function loadFromLoader($path, LoaderInterface $loader)
	{
		$data = $loader->load();
		if(null === $path)
		{
			if(is_array($data))
			{
				$this->data = array_merge_recursive($this->data, $data);
				return true;
			}
			return false;
		}
		$partial = $this->findKey($path);
		if(is_array($data))
		{
			$partial = array_merge_recursive($partial, $data);
			return true;
		}
		return false;
	} // end loadFromLoader();

	/**
	 * Loads the data to the collector from the specified array. If the path is
	 * different than <tt>self::ROOT</tt>, the data are appended under the specified
	 * path.
	 * 
	 * @param string|null $path The path, where the data should be saved. Null means root.
	 * @param array $array The array with the data.
	 * @return boolean
	 */
	public function loadFromArray($path, array $array)
	{
		if(null === $path)
		{
			$this->data = array_merge_recursive($this->data, $array);
			return true;
		}
		$partial = $this->findKey($path);
		$partial = array_merge_recursive($partial, $array);

		return true;
	} // end loadFromArray();
} // end Collector;