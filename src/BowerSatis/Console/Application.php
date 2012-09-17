<?php

/*
 * This file is part of Composer.
 *
 * (c) Rob Loach <robloach@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BowerSatis\Console;

use Composer\Satis\Console\Application as BaseApplication;
use BowerSatis\Command;
use BowerSatis\BowerSatis;

/**
 * The console application that handles the commands
 *
 * @author Rob Loach <robloach@gmail.com>
 */
class Application extends BaseApplication
{
    public function __construct()
    {
        parent::__construct();
        $this->setName('Bower Satis');
        $this->setVersion(BowerSatis::VERSION);
    }

    /**
     * Initializes all the composer commands
     */
    protected function registerCommands()
    {
        $this->add(new Command\BuildCommand());
    }
}
