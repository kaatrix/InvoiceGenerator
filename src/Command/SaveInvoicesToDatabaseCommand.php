<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use App\Factory\InvoiceFactory;
use App\Service\InvoiceService;
use Doctrine\ORM\EntityManagerInterface;

class SaveInvoicesToDatabaseCommand extends Command
{
    private $invoiceService;
    
    public function __construct(string $name = null, InvoiceService $invoiceService)
    {
        parent::__construct($name);
        $this->invoiceService = $invoiceService;
    }

    protected static $defaultName = 'app:save-invoices';

    protected function configure()
    {
        $this
            ->setDescription('Save invoices to database')
            ->setHelp('Saves generated invoices to database')
            ->addArgument('count', InputArgument::REQUIRED, 'number of invoices you want to add to datebase')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $count = $input->getArgument('count');
        $this->invoiceService->saveInvoicesToDatabase($count);
    }
}
