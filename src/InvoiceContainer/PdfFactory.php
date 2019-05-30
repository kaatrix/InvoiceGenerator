<?php

namespace App\InvoiceContainer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class PdfFactory extends \TCPDF 
{
    private $em;
    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    public function loadInvoice() {
        $repository = $this->em->getRepository(Invoice::class);
        $invoice = $repository->findOneBy(['invoiceNumber' => 41614]);
        if (!$invoice){
            throw new Exception();
        }       
        return $invoice;
    }
    
    private $tableHeader = array('Description', 'Unit', 'Price per unit (USD)', 'Amount (USD');

    public function contentTable() {
        $invoiceData = $this->loadInvoice();
        $this->SetFillColor(255);
        $this->SetTextColor(0);
        $this->SetDrawColor(0);
        $this->SetLineWidth(0.3);
        $this->SetFont('times','B');

        $columnWidth = array(70,10,20,20);
        $num_headers = count($this->tableHeader);

        for ($i=0; $i< $num_headers; ++$i) {
            $this->Cell($columnWidth[$i], 7, $this->header[$i], 1, 0, 'C', 1);
        }

        $this->Ln();
        $this->SetFillColor(255);
        $this->SetTextColor(0);
        $this->SetFont('times');

        $fill = 0;
        $this->Cell($columnWidth[0], 6, $row[0], 'LR', 0, 'L', $fill);
        $this->Cell($columnWidth[1], 6, $row[1], 'LR', 0, 'L', $fill);
        $this->Cell($columnWidth[2], 6, number_format($row[2]), 'LR', 0, 'R', $fill);
        $this->Cell($columnWidth[3], 6, number_format($row[3]), 'LR', 0, 'R', $fill);
        $this->Ln();
    }

    public function createPDF(){
        

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
        $pdf->MultiCell(60, 60, $invoiceData."\n", 0, 'L', 1, 1, 125, 58, true, 0, false, true, 60, 'M', true);

        $itemNetValue = $invoice->getNetValue();
        $itemAmount = 1*$invoice->getNetValue();
        $table = <<<EOT
        <table border="1">
            <tr>
                <th style="width:50%; text-align: center">Description</th>
                <th style="width:10%; text-align: center">Unit</th> 
                <th style="width:15%; text-align: center">Price</th>
                <th style="width:15%; text-align: center">Amount</th>
            </tr>
            <tr>
                <td style="padding-left: 20px">Sale of products and services</td>
                <td style="padding: 5px; text-align: right">1</td> 
                <td style="padding: 5px; text-align: right">$itemNetValue</td>
                <td style="text-align: right; padding: 5px">$itemAmount</td>

            </tr>
            
            </table>
EOT;
        $pdf->writeHTML($table, true, false, false, false, '');
        $pdf->Output(__DIR__ . '/invoices/test_003.pdf', 'F');
    }
}