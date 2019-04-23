<?php

namespace Wingman\Console\Commands;

use DI\FactoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wingman\Interfaces\JobInterface;

class QueueCommand extends Command
{

    private $wingman;
    private $queue;

    /**
     * Construct
     */
    public function __construct(FactoryInterface $wingman, JobInterface $job)
    {
        $this->wingman = $wingman;
        $this->queue = $job;

        parent::__construct();
    }

	/**
     * Configure the command
     */
    protected function configure()
    {
        $this->setName('run:queue');
        $this->setDescription('Run the queue worker');
    }

    /**
     * Excecute the command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {   
        return $this->theLoop();
    }

    /**
     * The loop
     */
    protected function theLoop()
    {
        while(1) {
            $this->nextJob();
            sleep(5);
        }
    }

    /**
     * Next job
     */
    protected function nextJob()
    {
        $job = $this->queue->next();
        if(!$job) return;
       
        switch($job->getType()) {
            case 'backup':
                $this->wingman->get('Wingman\Services\BackupService')->execute([
                    'type' => 'backup',
                    'payload' => $job->getPayload()
                ]);
                break;
        }

        $job->done();
    }

}