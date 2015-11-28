<?php

namespace Stratedge\Inspection\Console;

use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    public function __construct($name = 'Preplan API Documentor', $version = '1.0.0')
    {
        parent::__construct($name, $version);

        $this->addCommands([
            new Commands\Init(),
            new Commands\Parse(),
        ]);
    }
}
