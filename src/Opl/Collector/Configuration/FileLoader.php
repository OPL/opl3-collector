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
use InvalidArgumentException;
use Opl\Collector\LoaderInterface;

/**
 * The abstract class for the loaders that read the configuration from the
 * filesystem.
 *
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
abstract class FileLoader implements LoaderInterface
{
	/**
	 * The current file.
	 * @var string
	 */
	protected $currentFile;

	/**
	 * The list of scanned paths.
	 * @var array
	 */
	protected $_paths;

	/**
	 * Creates the file loader.
	 *
	 * @param array|string $paths The list of paths, where to look for the files.
	 */
	public function __construct($paths = array())
	{
		if(!is_array($paths))
		{
			$paths = array($paths);
		}
		foreach($paths as &$path)
		{
			$length = strlen($path);
			if(0 == $length || DIRECTORY_SEPARATOR != $path[$length - 1])
			{
				$path .= DIRECTORY_SEPARATOR;
			}
		}
		$this->_paths = $paths;
	} // end __construct();

	/**
	 * Sets the file name, which the metadata will be loaded from. Implements
	 * the fluent interface.
	 *
	 * @param string $file The file with the metadata.
	 * @return FileLoader
	 */
	public function setFile($file)
	{
		$this->currentFile = $file;

		return $this;
	} // end setFile();

	/**
	 * Returns the file name, which the metadata will be loaded from.
	 *
	 * @return string
	 */
	public function getFile()
	{
		return $this->currentFile;
	} // end getFile();

	/**
	 * Returns the tree identifier for the cache.
	 *
	 * @return string
	 */
	public function getIdentifier()
	{
		return $this->currentFile;
	} // end getIdentifier();

	/**
	 * Returns a validated path to the specified file. If the file name is
	 * invalid or it does not exist in any of defined paths, an exception
	 * is thrown.
	 *
	 * @throws InvalidArgumentException
	 * @param string $filename The file name
	 * @return string
	 */
	public function findFile($filename)
	{
		foreach($this->_paths as $path)
		{
			if(file_exists($path.$filename))
			{
				return $path.$filename;
			}
		}
		throw new InvalidArgumentException('The file \''.$filename.'\' does not exist in any of the specified paths.');
	} // end findFile();
} // end FileLoader;