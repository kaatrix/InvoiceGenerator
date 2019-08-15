<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Repository\InvoiceRepository;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class InvoiceController
{
    private $invoiceRepository;
    
        public function __construct(InvoiceRepository $invoiceRepository)
        {
            $this->invoiceRepository = $invoiceRepository;
        }
    /**
     * @Route("/downloadCsvInvoiceFile/{invoiceNumber}")
     */
    public function downloadCsvInvoiceFile($invoiceNumber)
    {        
        header('Content-type: text/csv');
        header("Content-Disposition: attachment; filename=$invoiceNumber.csv");
        header('Pragma: no-cache');
        header('Expires: 0');

        $file = fopen('php://output', 'w');
        fputcsv($file, array('Invoice number', 'Invoice date', 'Buyer name', 'Buyer address', 'Buyer NIP', 'Seller name', 'Seller address',
            'Seller NIP', 'Description','Unit', 'Price per unit', 'Net_value', 'Tax rate', 'Tax value', 'Total value'));
        $invoice = $this->invoiceRepository->findOneByInvoiceNumber($invoiceNumber);
        fputcsv($file, array($invoice->getInvoiceNumber(),$invoice->getInvoiceDate()->format('Y-m-d'),
            $invoice->getBuyerName(), $invoice->getBuyerAddress(), $invoice->getBuyerNip(),
            $invoice->getSellerName(), $invoice->getSellerAddress(), $invoice->getSellerNip(),
            $invoice->getDescription(), $invoice->getUnit(), $invoice->getPricePerUnit(),
            $invoice->getNetValue(), $invoice->getTaxRate(),$invoice->getTaxValue(), $invoice->getTotalValue()));
        return new Response('Invoice is getting downloaded');
    }

    /**
     *  @Route("/downloadCsvInvoicesPackage")
     */
    public function downloadCsvInvoicesPackage()
    {
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="Invoices.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $file = fopen('php://output', 'w');
        fputcsv($file, array('Invoice number', 'Invoice date', 'Buyer name', 'Buyer address', 'Buyer NIP', 'Seller name', 'Seller address',
            'Seller NIP', 'Description','Unit', 'Price per unit', 'Net_value', 'Tax rate', 'Tax value', 'Total value'));
        $invoices = $this->invoiceRepository->FindAllInvoices();
        
        foreach($invoices as $invoice){
            fputcsv($file, array($invoice->getInvoiceNumber(),$invoice->getInvoiceDate()->format('Y-m-d'),
            $invoice->getBuyerName(), $invoice->getBuyerAddress(), $invoice->getBuyerNip(),
            $invoice->getSellerName(), $invoice->getSellerAddress(), $invoice->getSellerNip(),
            $invoice->getDescription(), $invoice->getUnit(), $invoice->getPricePerUnit(),
            $invoice->getNetValue(), $invoice->getTaxRate(),$invoice->getTaxValue(), $invoice->getTotalValue()));
        }
        return new Response("package of invoices is geting downloaded");
    }
} 