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

/**
 * Loaders load the data to the collector.
 *
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
interface LoaderInterface
{
	/**
	 * Returns the imported data in a form of an array.
	 * 
	 * @param \Opl\Collector\ProviderInterface $provider The current provider that can be used to ask for some extra data.
	 * @return array
	 */
	public function import(ProviderInterface $provider);
} // end LoaderInterface;