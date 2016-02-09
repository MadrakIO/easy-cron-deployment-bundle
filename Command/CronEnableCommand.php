<?php

namespace MadrakIO\EasyCronDeploymentBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use MadrakIO\EasyCronDeploymentBundle\Command\AbstractCronCommand;

class CronEnableCommand extends AbstractCronCommand
{
    protected function configure()
    {
        $this
            ->setName('madrakio:cron:enable')
            ->setDescription('Automatically uncomment all tasks in the crontab. This removes all #s from the start of each line.')
            ->addOption(
               'non-interactive',
               null,
               InputOption::VALUE_NONE,
               "If set, you will not be prompted before your user's crontab is commented"
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->interactiveOperationConfirmation($input, $output);
                
        $crontabListOutputLines = explode(PHP_EOL, $this->getSystemCrontabList($output));
        if (empty($crontabListOutputLines[count($crontabListOutputLines) - 1]) === true) {
            array_pop($crontabListOutputLines);        
        }
        
        $newCrontabFileContents = '';
        foreach ($crontabListOutputLines AS $crontabLine) {
            $newCrontabFileContents .= ltrim($crontabLine, '#') . PHP_EOL;
        }

        $this->setSystemCrontab($output, $newCrontabFileContents);
        $this->outputFormattedBlock($output, ['Success!', 'Your cron has been successfully re-enabled!'], 'info');
    }
}


