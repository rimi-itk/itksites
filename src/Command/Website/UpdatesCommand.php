<?php

/*
 * This file is part of ITK Sites.
 *
 * (c) 2018–2020 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace App\Command\Website;

use App\Command\AbstractCommand;
use App\Entity\Website;
use Symfony\Component\Console\Input\InputOption;

class UpdatesCommand extends AbstractCommand
{
    protected static $defaultName = 'app:website:updates';

    protected function configure()
    {
        parent::configure();
        $this
            ->setDescription('Get update data from sites')
            ->addOption('types', null, InputOption::VALUE_REQUIRED, 'Type of sites to process');
    }

    protected function runCommand(): void
    {
        $types = array_filter(preg_split('/\s*,\s*/', $this->input->getOption('types'), \PREG_SPLIT_NO_EMPTY));
        $websites = $types ? $this->getWebsitesByTypes($types) : $this->getWebsites();

        $detectors = [
            'drupal (multisite)' => [
                'getCommand' => static function (Website $website) {
                    $siteDirectory = 'sites/'.$website->getDomain();

                    return "cd $siteDirectory && drush pm-list --format=json";
                },
                'getData' => function (array $output, Website $website) {
                    $data = $this->parseJson(implode(\PHP_EOL, $output));

                    $buckets = [
                        'Enabled' => [],
                        'Disabled' => [],
                        'Not installed' => [],
                    ];

                    foreach ($data as $item) {
                        $buckets[$item->status][] = $item;
                    }

                    return json_encode($buckets, \JSON_THROW_ON_ERROR, 512);
                },
            ],
            'drupal' => [
                'command' => 'drush pm-list --format=json',
                'getData' => function (array $output) {
                    $data = $this->parseJson(implode(\PHP_EOL, $output));

                    $buckets = [
                        'Enabled' => [],
                        'Disabled' => [],
                        'Not installed' => [],
                    ];

                    foreach ($data as $item) {
                        $buckets[$item->status][] = $item;
                    }

                    return json_encode($buckets, \JSON_THROW_ON_ERROR, 512);
                },
            ],
        ];

        foreach ($websites as $website) {
            $this->info('Domain {domain}', ['domain' => $website->getDomain()]);

            if (isset($detectors[$website->getType()])) {
                $detector = $detectors[$website->getType()];

                $cmdTemplate = 'ssh -o ConnectTimeout=10 -o BatchMode=yes -o StrictHostKeyChecking=no -A deploy@'.$website->getServer()
                    .' "cd '.$website->getDocumentRoot().' && {{ command }}"';

                $command = isset($detector['getCommand']) ? $detector['getCommand']($website) : $detector['command'];
                $cmd = str_replace('{{ command }}', $command, $cmdTemplate);

                $output = null;
                $code = 0;

                @exec($cmd, $output, $code);
                if (0 === $code) {
                    $data = $detector['getData']($output, $website);
                    if (null !== $data) {
                        $website->setUupdates($data);
                        $this->persist($website);
                    }
                }
            }
        }
    }
}
