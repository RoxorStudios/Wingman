<?php

namespace Wingman\Console\Commands;

use DI\FactoryInterface;
use Ahc\Cron\Expression;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wingman\Interfaces\JobInterface;

class CronCommand extends Command
{

    private $config;
    private $queue;

    /**
     * Construct
     */
    public function __construct(FactoryInterface $wingman, JobInterface $queue)
    {
        $this->config = $wingman->get('Config');
        $this->queue = $queue;

        parent::__construct();
    }

	/**
     * Configure the command
     */
    protected function configure()
    {
        $this->setName('run:cron');
        $this->setDescription('Run the cron command');
    }

    /**
     * Excecute the command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {   
        $this->parseBackups($this->config);
    }

    /**
     * Parse backups
     */
    protected function parseBackups($config)
    {
        if(!isset($config['backups'])) return;

        foreach($this->config['backups'] as $database) {
            $this->parseBackupCandidate(key($database), reset($database));
        }
    }

    /**
     * Parse database
     */
    protected function parseBackupCandidate($type, $database)
    {
        if(!isset($database['destinations'])) return;

        foreach($database['destinations'] as $destination) {
            $parameters = reset($destination);
            if(Expression::isDue($parameters['frequency'])) {
               $this->addToQueue($type, $database, $destination);
            }
        }
    }

    /**
     * Post job
     */
    protected function addToQueue($type, $database, $destination)
    {   
        unset($database['destinations']);
        $payload = [
            'type' => $type,
            'database' => $database,
            'destination' => $destination
        ];

        $this->queue->add('backup', $payload);
    }
 
}