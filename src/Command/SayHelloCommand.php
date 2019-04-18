<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\NipContainer\NipGenerator;
use App\NipContainer\NipChecker;
use App\Controller\InvoiceController;

class SayHelloCommand extends Command
{
    protected static $defaultName = 'app:show-invoices';


    protected function configure()
    {
       $this
            ->setDescription('Shows invoices')
            ->setHelp('Show invoices gathered together')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

       
        $nip1 = NipGenerator::generateNip();
        $nip2 = NipGenerator::generateFalseNip();
        $section1 = $output->section();
        $section2 = $output->section();
        //$section1->writeln( NipGenerator::generateNip());
        //$output->writeln(NipChecker::checkNip($nip1));
        //$section2->writeln( NipGenerator::generateFalseNip());
       // $output->writeln(NipChecker::checkNip($nip2));

        foreach(InvoiceController::createManyInvoices(100) as $invoice) {
            $output->writeln($invoice);
        }

    }
}