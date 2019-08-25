<?php

namespace App\Factory;

class CsvFactory
{
    public function createCsvHeader($file){
        fputcsv($file, array('Invoice number', 'Invoice date', 'Buyer name', 'Buyer address', 'Buyer NIP', 'Seller name', 'Seller address',
                'Seller NIP', 'Description','Unit', 'Price per unit', 'Net_value', 'Tax rate', 'Tax value', 'Total value'));
    } 

    public function createCsvLine($file, $invoice){
        fputcsv($file, array($invoice->getInvoiceNumber(),$invoice->getInvoiceDate()->format('Y-m-d'),
            $invoice->getBuyerName(), $invoice->getBuyerAddress(), $invoice->getBuyerNip(),
            $invoice->getSellerName(), $invoice->getSellerAddress(), $invoice->getSellerNip(),
            $invoice->getDescription(), $invoice->getUnit(), $invoice->getPricePerUnit(),
            $invoice->getNetValue(), $invoice->getTaxRate(),$invoice->getTaxValue(), $invoice->getTotalValue()));
    }
}