<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\InvoiceContainer\InvoiceFactory;
use App\InvoiceContainer\PdfFactory;
use App\Repository\InvoiceRepository;

class GeneratePdfInvoiceCommand extends Command
{
    private $pdfFactory;
    private $invoiceRepository;
    
    public function __construct(string $name = null, PdfFactory $pdfFactory, InvoiceRepository $invoiceRepository)
    {
        parent::__construct($name);
        $this->pdfFactory = $pdfFactory;
        $this->invoiceRepository = $invoiceRepository;
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
        $invoice = $this->invoiceRepository->findOneByInvoiceNumber();
        $this->pdfFactory->createPDF($invoice);
    }
}
