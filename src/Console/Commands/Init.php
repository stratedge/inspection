<?php

namespace Stratedge\Inspection\Console\Commands;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Init extends BaseCommand
{
    protected function configure()
    {
        $this->setName('init')
             ->setDescription('Initializes Preplan configuration for current project');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = getcwd();

        $path = realpath($path);

        if (!is_writable($path)) {
            throw new \InvalidArgumentException(sprintf(
                'The directory "%s" is not writeable',
                $path
            ));
        }

        $file_name = 'inspection.yml';
        $file_path = $path . DIRECTORY_SEPARATOR . $file_name;

        if (file_exists($file_path)) {
            throw new \RuntimeException('Project is already initialized');
        }

        if (file_put_contents($file_path, '') === false) {
            throw new \RuntimeException(sprintf(
                'The file "%s" could not be written to',
                $file_path
            ));
        }

        $output->writeln('<info>created</info> .' . str_replace(getcwd(), '', $filePath));
    }
}
