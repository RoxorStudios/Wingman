<?php

namespace Wingman\Services;

use DI\FactoryInterface;

class BackupService {

	private $wingman;

	/**
     * Construct
     */
    public function __construct(FactoryInterface $wingman)
    {
     	$this->wingman = $wingman;
    }

	/**
     * Execute
     */
    public function execute($job)
    {
        $tmpfile = $this->executeBackup($job);

        $this->writeFile($job, $tmpfile);
        unlink($tmpfile);
    }

    /**
     * Run backup
     */
    protected function executeBackup($job)
    {
        switch($job['payload']['type']) {
            case 'mysql':
                $driver = $this->wingman->get('Wingman\Repositories\Backup\MysqlBackup');
                break;
            default:
                return;
        }

        return $driver->backup($job['payload']['database']);
    }

    /**
     * Write file to destination
     */
    protected function writeFile($job, $tmpfile)
    {
        $parameters = reset($job['payload']['destination']);

        switch(key($job['payload']['destination'])) {
            case 'local':
                $disk = $this->wingman->get('Wingman\Repositories\Storage\LocalStorage');
                break;
            default:
                return;
        }

        $disk->put($parameters['folder'] . '/' . $this->generateFilename($job['payload']['database']['name']), file_get_contents($tmpfile));
        
    }

    /**
     * Generate filename
     */
    protected function generateFilename($name)
    {
        return $name.'_'.date('y-m-d_H:i').'.sql';
    }
	
}