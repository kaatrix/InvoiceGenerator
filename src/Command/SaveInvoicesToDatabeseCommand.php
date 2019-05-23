<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\InvoiceContainer\InvoiceFactory;
use Doctrine\ORM\EntityManagerInterface;

class SaveInvoicesToDatabeseCommand extends Command
{
    private $em;
    private $invoiceFactory;
    
    public function __construct(string $name = null, EntityManagerInterface $em, InvoiceFactory $invoiceFactory)
    {
        parent::__construct($name);
        $this->em = $em;
        $this->invoiceFactory = $invoiceFactory;
    }

    protected static $defaultName = 'app:save-invoices';

    protected function configure()
    {
        $this
            ->setDescription('Save invoices to database')
            ->setHelp('Saves generated invoices to database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
       foreach ($this->invoiceFactory->createManyInvoices(3) as $invoice) {
           $this->em->persist($invoice);
       }

       $this->em->flush();
    }
}
