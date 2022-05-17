<?php

namespace App\Command;

use App\ImportVideo\ImportVideoFlub;
use App\ImportVideo\ImportVideoGlorf;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportCommand extends Command
{
    protected static $defaultName = 'import';
    private $resources = [];

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('provider', InputArgument::REQUIRED, 'Name of the video provider');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $provider = $input->getArgument('provider');

        $io = new SymfonyStyle($input, $output);
        $io->title('Importing provider: "'.$provider.'"');

        if(!isset($this->resources[$provider])){
            $io->error('Import provuder "'.$provider.'" not found!');
            return Command::FAILURE;
        }

        $import = null;
        if($provider=='flub'){
            $import = new ImportVideoFlub($this->entityManager);
        } elseif($provider=='glorf'){
            $import = new ImportVideoGlorf($this->entityManager);
        }

        if($import){

            $io->section('Reading file: '.$this->resources[$provider]);

            $import->setFilename($this->resources[$provider]);
            $import->read();
            $import->prepare();
            $import->parse();

            if($import->rows){
                $listing = [];
                foreach ($import->rows as $row){
                    $listing[] = 'importing: "'.$row['name'].'"; Url: '.$row['url'].'; Tags: '.$row['tags'];
                }
                $io->listing($listing);

                // store data here
                $import->save();
            }
        } else {
            $io->error('Class for provider "'.$provider.'" is not found!');
            return Command::FAILURE;
        }

        $io->success('Imported '.count($import->rows).' rows!');
        return Command::SUCCESS;
    }

    public function setResources($sourceName, $source)
    {
        $this->resources[$sourceName] = $source;
    }
}