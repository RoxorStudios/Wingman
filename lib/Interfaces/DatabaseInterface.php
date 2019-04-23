<?php

namespace Wingman\Interfaces;

interface DatabaseInterface
{

	public function __construct();
	public function insert($data);

}