<?php

namespace Wingman\Repositories\Storage;

use Wingman\Interfaces\StorageInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

class LocalStorage implements StorageInterface {

	private $filesystem;

	/**
	 * Construct
	 */
	public function __construct()
	{
		$adapter = new Local(storage_path());
		$this->filesystem = new Filesystem($adapter);
	}

	/**
	 * Put
	 */
	public function put($path, $contents)
	{
        $this->filesystem->put($path, $contents);
	}

}