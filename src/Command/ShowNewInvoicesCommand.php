<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Nip\NipGenerator;
use App\Nip\NipChecker;
use App\Factory\InvoiceFactory;

class ShowNewInvoicesCommand extends Command
{
    private $invoiceFactory;
    protected static $defaultName = 'app:show-invoices';

    public function __construct(string $name = null, InvoiceFactory $invoiceFactory)
    {
        parent::__construct($name);
        $this->invoiceFactory = $invoiceFactory;
    }

    protected function configure()
    {
       $this
            ->setDescription('Shows invoices')
            ->setHelp('Show invoices gathered together')
            ->addArgument('count', InputArgument::REQUIRED, 'number of invoices you want to add to datebase')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);
        //$nip1 = NipGenerator::generateNip();
        //$nip2 = NipGenerator::generateFalseNip();
        //$section1 = $output->section();
        //$section2 = $output->section();
        //$section1->writeln( NipGenerator::generateNip());
        //$output->writeln(NipChecker::checkNip($nip1));
        //$section2->writeln( NipGenerator::generateFalseNip());
        //$output->writeln(NipChecker::checkNip($nip2));
        $count = intval($input->getArgument('count'));
        foreach($this->invoiceFactory->createInvoices($count) as $invoice) {
            $rows[] = [$invoice->getInvoiceNumber(), 
                $invoice->getInvoiceDate()->format('Y-m-d'),
                $invoice->getBuyerName()."\n".$invoice->getBuyerAddress()."\n".$invoice->getBuyerNip(),
                $invoice->getSellerName()."\n".$invoice->getSellerAddress()."\n".$invoice->getSellerNip(),
                $invoice->getDescription(),
                $invoice->getUnit(),
                $invoice->getPricePerUnit(),
                $invoice->getNetValue(),
                $invoice->getTaxRate()." %\n".$invoice->getTaxValue(),
                $invoice->getTotalValue()];
        }

        $table
        ->setHeaders(['Invoice Number', 'Invoice Date', 'Buyer','Seller','Net Value','Tax','Total Value'])
        ->setRows($rows);

        $table->render();
        
    }
}