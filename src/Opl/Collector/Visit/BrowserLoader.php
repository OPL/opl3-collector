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
namespace Opl\Collector\Visit;
use Opl\Collector\LoaderInterface;

/**
 * This collector collects the information about the browser and its
 * properties.
 *
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class BrowserLoader implements LoaderInterface
{
	/**
	 * @see LoaderInterface
	 */
	public function import()
	{
		if(!isset($_SERVER['USER_AGENT']))
		{
			return array(
				'browser' => null,
				'browserName' => null,
				'version' => null,
				'platform' => null,
				'supports' => array(
					'javaScript' => false,
					'frames' => false,
					'iframes' => false,
					'tables' => false,
					'css' => false,
					'vbscript' => false,
					'html5Forms' => false,
					'canvas' => false,
					'svg' => false,
				),
				'features' => array(
					'cssVersion' => 0,
					'isMobile' => false,
					'isBanned' => false,
				)
			);
		}
		$info = get_browser($_SERVER['USER_AGENT'], true);

		return array(
			'browser' => $info['parent'],
			'browserName' => $info['browser'],
			'version' => $info['version'],
			'platform' => $info['platform'],
			'supports' => array(
				'javaScript' => (bool)$info['javascript'],
				'frames' => (bool)$info['frames'],
				'iframes' => (bool)$info['iframes'],
				'cookies' => (bool)$info['cookies'],
				'tables' => (bool)$info['tables'],
				'css' => (bool)$info['supportscss'],
				'vbscript' => (bool)$info['vbscript'],
				'html5Forms' => false,
				'canvas' => false,
				'svg' => false,
			),
			'features' => array(
				'cssVersion' => $info['cssversion'],
				'isMobile' => $info['ismobiledevice'],
				'isBanned' => $info['isbanned'],
			)
		);
	} // end import();
} // end BrowserLoader;