<?php

namespace App\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Repository\InvoiceRepository;
use App\Entity\Invoice;

class PdfFactory 
{ 
    private $tableHeader = array('Description', 'Unit', "Price per unit (USD)", 'Amount (USD)');

    public function createContentTable(Invoice $invoice, \TCPDF $pdf) {

        $pdf->SetFillColor(255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0);
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('times','B');

        $columnWidth = array(70,20,40,40);
        $num_headers = count($this->tableHeader);

        for ($i=0; $i< $num_headers; ++$i) {
            $pdf->Cell($columnWidth[$i], 7, $this->tableHeader[$i], 1, 0, 'C',0,'', 1);
        }

        $pdf->Ln();
        $pdf->SetFillColor(255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('times');

        $fill = 0;
        $pdf->Cell($columnWidth[0], 6, $invoice->getDescription(), 'LR', 0, 'L', $fill);
        $pdf->Cell($columnWidth[1], 6, $invoice->getUnit(), 'LR', 0, 'L', $fill);
        $pdf->Cell($columnWidth[2], 6, number_format($invoice->getPricePerUnit()), 'LR', 0, 'R', $fill);
        $pdf->Cell($columnWidth[3], 6, number_format($invoice->getNetValue()), 'LR', 0, 'R', $fill);
        $pdf->Ln();
        $pdf->Cell(array_sum($columnWidth), 0, '', 'T');
        $pdf->Ln();
    }

    public function createPDF(Invoice $invoice){
        

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetFont('helvetica', 'B', 20);
        $pdf->AddPage();

        
        $pdf->Write(0, 'INVOICE', '', 0, 'C', true, 0, false, false, 0);

        $pdf->SetFont('times', '', 14);
        $pdf->SetFillColor(255, 255, 255);

        $sellerData = $invoice->getSellerName()."\n".$invoice->getSellerAddress()."\n".$invoice->getSellerNip();
        $pdf->MultiCell(60, 5, $sellerData, 0, 'L', 1, 0, '', '', true);

        $buyerData = $invoice->getBuyerName()."\n".$invoice->getBuyerAddress()."\n".$invoice->getBuyerNip();
        $pdf->MultiCell(60, 60, 'Bill to:'."\n".$buyerData."\n", 0, 'L', 1, 1, 10, 45, true, 0, false, true, 60, 'M', true);

        $invoiceData = 'invoice number: '.$invoice->getInvoiceNumber()."\n".'date of issue: '.$invoice->getInvoiceDate()->format('Y-m-d');
        $pdf->MultiCell(60, 60, $invoiceData."\n", 0, 'L', 1, 1, 125, 61, true, 0, false, true, 60, 'M', true);

        
        $this->createContentTable($invoice,$pdf);

        $valueDataNames = 'Subtotal: '."\n".'Tax('.$invoice->getTaxRate().'%): '."\n".'Total(USD): ';
        $valueDataAmounts = $invoice->getNetValue()."\n".$invoice->getTaxValue()."\n".$invoice->getTotalValue();
        $pdf->MultiCell(60, 60, $valueDataNames, 0, 'L', 0, 0, 115, '', true);
        $pdf->MultiCell(60, 60, $valueDataAmounts, 0, 'R', 0, 1, 120, '', true);
        $pdf->Output(__DIR__ . '/invoices/'.$invoice->getInvoiceNumber().'.pdf', 'F');
    }
}