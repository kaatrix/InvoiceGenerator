<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Factory\InvoiceFactory;
use App\Factory\PdfFactory;
use App\Repository\InvoiceRepository;
use Symfony\Component\Console\Input\InputArgument;

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
            ->addArgument('invoiceNumber', InputArgument::REQUIRED, 'Invoice number you want to create PDF from')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $invoiceNumber = $input->getArgument('invoiceNumber');
        $invoice = $this->invoiceRepository->findOneByInvoiceNumber($invoiceNumber);
        $this->pdfFactory->createPDF($invoice);
    }
}
