<?php
namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * EMS II Cron job to be run nightly
 *
 * This script should be setup in the cron job list
 * to run every 30-60 min. See the documentation for more info.
 *
 * # run Nightly EMS site cron job
 *   0  4,16  *   *   *      php /home/user/public_html/bin/ems cron > /dev/null 2>&1
 * # OR
 *   0  4  *   *   *      php /home/user/public_html/bin/ems cron > /dev/null 2>&1
 *
 *
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @see http://www.tropotek.com/
 * @license Copyright 2017 Michael Mifsud
 */
class Cron extends Command
{

    /**
     * @var OutputInterface
     */
    public $output = null;

    /**
     * @var InputInterface
     */
    public $input = null;


    /**
     *
     */
    protected function configure()
    {
        $this->setName('cron')
            ->setDescription('A nightly cron script. crontab: 0 4  * * *   {path-to-site}/bin/project cron > /dev/null 2>&1');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Tk\Db\Exception
     * @throws \Tk\Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;


        //$output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);
        $this->output->writeln('--------------------------------------', OutputInterface::VERBOSITY_VERBOSE);
        $this->output->writeln('               CRONJOB                ', OutputInterface::VERBOSITY_VERBOSE);
        $this->output->writeln('--------------------------------------', OutputInterface::VERBOSITY_VERBOSE);


    }

}
