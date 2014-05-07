<?php

namespace Cti\Storage\Command;

use Cti\Storage\Schema;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class GenerateFiles extends Command
{
    /**
     * @inject
     * @var \Build\Application
     */
    protected $application;

    protected function configure()
    {
        $this
            ->setName('generate:files')
            ->setDescription('Generate php classes')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var $schema Schema
         */
        $schema = $this->application->getStorage()->getSchema();

        $fs = new Filesystem();

        $fs->dumpFile(
            $this->application->getProject()->getPath('build php Storage Master.php'),
            $this->application->getManager()->create('Cti\Storage\Generator\Master', array(
                'schema' => $schema
            ))
        );

        foreach($schema->getModels() as $repository) {

            $path = $this->application->getProject()->getPath('build php Storage Model ' . $repository->getClassName() . 'Base.php');
            $model = $this->application->getManager()->create('Cti\Storage\Generator\Model', array(
                'model' => $repository
            ));
            $modelSource = (String)$model;

            $fs->dumpFile(
                $path,
                $modelSource
            );

            $path = $this->application->getProject()->getPath('build php Storage Repository ' . $repository->getClassName() . 'Repository.php');
            $repository = $this->application->getManager()->create('Cti\Storage\Generator\Repository', array(
                'model' => $repository
            ));
            $repositorySource = (String)$repository;
            $fs->dumpFile(
                $path,
                $repositorySource
            );

//            if($model->hasOwnQuery()) {
//                $fs->dumpFile(
//                    $this->application->getPath('build php Storage Query ' . $model->class_name . 'Select.php'),
//                    $this->application->getManager()->create('Cti\Storage\Generator\Select', array(
//                        'model' => $model
//                    ))
//                );
//            }
        }
    }
}