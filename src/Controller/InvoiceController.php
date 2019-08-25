<?php

namespace App\Controller;

use App\Email\EmailSender;
use App\Factory\CsvFactory;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Repository\InvoiceRepository;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class InvoiceController
{
    
    private $invoiceRepository;
    private $csvFactory;
    private $emailSender;
    
        public function __construct(InvoiceRepository $invoiceRepository, CsvFactory $csvFactory, EmailSender $emailSender)
        {
            $this->invoiceRepository = $invoiceRepository;
            $this->csvFactory = $csvFactory;
            $this->emailSender = $emailSender;
        }
    /**
     * @Route("/download-csv-invoice-file/{invoiceNumber}")
     */
    public function downloadCsvInvoiceFile($invoiceNumber)
    {    
        $invoice = $this->invoiceRepository->findOneByInvoiceNumber($invoiceNumber);  
        $this->createHeaders($invoiceNumber);

        $file = fopen('php://output', 'w');
        $this->csvFactory->createCsvHeader($file);
        $this->csvFactory->createCsvLine($file, $invoice);

        return new Response();
    }

    /**
     *  @Route("/download-csv-invoices-package")
     */
    public function downloadCsvInvoicesPackage()
    {
        $filename = 'Invoices.csv';
        $invoices = $this->invoiceRepository->FindAllInvoices();
        $this->createHeaders($filename);

        $file = fopen('php://output', 'w');
        $this->csvFactory->createCsvHeader($file);
        
        foreach($invoices as $invoice){
            $this->csvFactory->createCsvLine($file, $invoice);
        }
        return new Response();
    }
    /**
     * @Route("/download-csv-invoice-file/{invoiceNumber}/{emailAddress}")
     */
    public function sendCsvInvoiceFileToEmail($invoiceNumber, $emailAddress)
    {    
        $this->emailSender->sendEmail($emailAddress, $invoiceNumber);
        return new Response();
    }

    private function createHeaders($filename)
    {
        header('Content-type: text/csv');
        header("Content-Disposition: attachment; filename=$filename.csv");
        header('Pragma: no-cache');
        header('Expires: 0');
    }
} 