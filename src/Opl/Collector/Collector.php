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

class Collector extends Provider
{
	const CACHE_ENABLED = true;
	const CACHE_DISABLED = false;
	const ROOT = null;

	protected $cache;
	protected $cacheLoaded = true;
	protected $cacheKey;

	public function __construct(Cache $cache = null, $cacheKey = 'collector')
	{
		if(null !== $cache)
		{
			$this->cache = $cache;
			$this->data = $cache->get($cacheKey);

			if(null === $this->data)
			{
				$this->cacheLoaded = false;
				$this->cache = array();
			}
			$this->cacheKey = $cacheKey;
		}
	} // end __construct();

	public function isCached()
	{
		return $this->cacheLoaded;
	} // end isCached();

	public function save()
	{
		if(null === $this->cache)
		{
			throw new BadMethodCallException('Cannot use Opl\\Collector\\Collector::save(): no cache system installed.');
		}
		$this->cache->set($this->cacheKey, $this->data);
	} // end save();

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