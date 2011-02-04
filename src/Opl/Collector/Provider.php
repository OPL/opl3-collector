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

class Provider implements ProviderInterface
{
	protected $data;

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

	protected function processKey($key)
	{
		return explode('.', $key);
	} // end processKey();

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
} // end Provider;