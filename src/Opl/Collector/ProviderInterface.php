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
 * This interface allows to write custom data providers and mock them with
 * a convenient way. The system may use this interface to retireve the data.
 *
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
interface ProviderInterface
{
	const THROW_EXCEPTION = 0;
	const THROW_NULL = 1;

	/**
	 * Returns the value under the specified key. If the key does not exist,
	 * the method either throws an exception or returns NULL, depending on the
	 * value of the second argument: <tt>THROW_EXCEPTION</tt> or <tt>THROW_NULL</tt>
	 *
	 * @throws \Opl\Collector\Exception\InvalidKeyException
	 * @param string $key The value key.
	 * @param int $errorReporting How to report the errors about missing key?
	 * @return mixed
	 */
	public function get($key, $errorReporting = self::THROW_EXCEPTION);
} // end ProviderInterface;