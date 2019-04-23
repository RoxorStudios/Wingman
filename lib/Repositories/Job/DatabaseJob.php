<?php

namespace Wingman\Repositories\Job;

use Wingman\Interfaces\JobInterface;
use Wingman\Interfaces\DatabaseInterface;

use Carbon\Carbon;

class DatabaseJob implements JobInterface {

	private $database;
	private $job;

	/**
	 * Construct
	 */
	public function __construct(DatabaseInterface $database)
	{
		$this->database = $database;
	}

	/**
	 * Add job
	 */
	public function add($type, $payload)
	{
		if(!$type) return;

		$this->database->table('jobs')->insert([
			'type' => $type,
			'payload' => json_encode($payload),
			'created_at' => Carbon::now()->toDateTimeString(),
		]);
	}

	/**
	 * Next
	 */
	public function next()
	{
		$job = $this->database->table('jobs')->where('reserved_at', '=', '')->first();
		if(!$job) return;
		
		$this->job = [
			'id' => $job->id,
			'type' => $job->type,
			'payload' => json_decode($job->payload, true)
		];

		return $this;
	}

	/**
	 * Get type
	 */
	public function getType()
	{
		return $this->job ? $this->job['type'] : null;
	}

	/**
	 * Get payload
	 */
	public function getPayload()
	{
		return $this->job ? $this->job['payload'] : null;
	}

	/**
	 * Done
	 */
	public function done()
	{
		$this->database->table('jobs')->delete($this->job['id']);
	}

}