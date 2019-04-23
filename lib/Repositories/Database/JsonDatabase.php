<?php

namespace Wingman\Repositories\Database;

use Wingman\Interfaces\DatabaseInterface;

use Lazer\Classes\Database as DB;
use  Lazer\Classes\Helpers\Validate;
use  Lazer\Classes\Helpers\LazerException;

class JsonDatabase implements DatabaseInterface {

	private $builder;

	/**
	 * Construct
	 */
	public function __construct()
	{
		define('LAZER_DATA_PATH', __DIR__.'/../../../content/database/'); 		
		$this->setupDB();
	}

	/**
	 * Select table
	 */
	public function table($table)
	{
		$this->builder = DB::table($table);
		return $this;
	}

	/**
	 * Where
	 */
	public function where($field, $operator, $value)
	{
		$this->builder->where($field, $operator, $value);
		return $this;
	}

	/**
	 * Insert a row into the database
	 */
	public function insert($data)
	{
		$this->builder->set($data);
		$this->builder->save();

		return true;
	}

	/**
	 * Update a row
	*/
	public function update($id, $data)
	{
		$row = $this->builder->find($id);
		$row->set($data);
		$row->save();
	}

	/**
	 * Delete a record
	 */
	public function delete($id)
	{
		$row = $this->builder->find($id);
		$row->delete();
	}

	/**
	 * First result
	 */
	public function first()
	{
		$row = $this->builder->find();
		
		return $row;
	}

	/**
	 * Setup database
	 */
	private function setupDB()
	{
		DB::remove('jobs');
		DB::create('jobs', [
		    'id' => 'integer',
		    'type' => 'string',
		    'payload' => 'string',
		    'attempts' => 'integer',
		    'reserved_at' => 'string',
		    'created_at' => 'string'
		]);
	}

}