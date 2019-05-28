<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\InvoiceContainer\InvoiceFactory;

class GeneratePdfInvoiceCommand extends Command
{
    private $invoiceFactory;
    
    public function __construct(string $name = null, InvoiceFactory $invoiceFactory)
    {
        parent::__construct($name);
        $this->invoiceFactory = $invoiceFactory;
    }
    protected static $defaultName = 'app:pdf-generate';

    protected function configure()
    {
        $this
            ->setDescription('Generates invoice in PDF')
            ->setHelp('Generates invoice in PDF format from a datebase')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->invoiceFactory->createPDF();
    }
}
