<?php

namespace App\Controller;

use App\Email\EmailSender;
use App\Factory\CsvFactory;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Repository\InvoiceRepository;
use App\Service\InvoiceService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class InvoiceController
{
    
    private $invoiceRepository;
    private $csvFactory;
    private $emailSender;
    private $invoiceService;
    
    public function __construct(InvoiceRepository $invoiceRepository, CsvFactory $csvFactory, EmailSender $emailSender, InvoiceService $invoiceService)
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->csvFactory = $csvFactory;
        $this->emailSender = $emailSender;
        $this->invoiceService = $invoiceService;
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
     * @Route("/send-pdf-invoice-to-email/{invoiceNumber}/{emailAddress}")
     */
    public function sendInvoiceFileToEmail($invoiceNumber, $emailAddress)
    {    
        $this->emailSender->sendEmail($emailAddress, $invoiceNumber);
        return new Response();
    }

    /**
     * @Route("/add-new-invoices-to-database/{count}")
     */
    public function addNewInvoicesToDatabase($count)
    {    
        $this->invoiceService->saveInvoicesToDatabase($count);
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