<?php

/*
* This file is part of BowerSatis.
*
* (c) Rob Loach <robloach@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace BowerSatis\Command;

use Composer\Satis\Command\BuildCommand as BaseBuildCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Composer\Json\JsonFile;

class BuildCommand extends BaseBuildCommand
{
    protected function configure()
    {
        parent::configure();
        $this->addOption('source', null, InputOption::VALUE_OPTIONAL, 'The source Bower package JSON file.', 'http://bower.herokuapp.com/packages');
        $this->setDescription('Builds a Composer repository out of a Bower package list');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Retrieve the package list from the given source.
        $source = $input->getOption('source');
        $contents = file_get_contents($source);

        // Build an array of all repositories.
        $repositories = array();
        foreach (json_decode($contents) as $package) {
            $repositories[] = array(
                'type' => 'package',
                'package' => array(
                    'name' => 'bower/' . $package->name,
                    'source' => array(
                        'url' => $package->url,
                        'type' => 'vcs',
                        'reference' => 'master',
                    ),
                    'version' => 'dev-master',
                ),
            );

            /**
             * Using type "vcs" here results in an error missing composer.json
             * files. Therefore, we use the above "package" type instead.
             *
            $repositories[] = array(
                'type' => 'vcs',
                'url' => $package->url,
            );
             */
        }

        // Construct the base of the Bower Satis settings.
        $bowersatis = array(
            'name' => 'Bower Satis',
            'homepage' => 'http://packages.drupalbin.com',
            'repositories' => $repositories,
        );

        $json = new JsonFile($input->getArgument('file'));
        $json->write($bowersatis);

        return parent::execute($input, $output);
    }
}
