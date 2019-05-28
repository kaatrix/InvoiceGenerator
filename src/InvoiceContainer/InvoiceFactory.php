<?php

namespace App\InvoiceContainer;

use App\Entity\Invoice;
use Faker\Factory;
use Faker\Generator;
use App\NipContainer\NipGenerator;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceFactory
{
    private $em;
    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }
    /** @var Generator */
    private $faker;

    public function fakerLoader()
    {
       $this->faker = Factory::create();
    }

    private static $invoiceTaxRate = [23,8,5,0];

    public function createInvoice($sellerName, $sellerAddress, $sellerNip)
    {
        $invoice = new Invoice();
        $invoice->setInvoiceNumber((string)$this->faker->randomNumber())
            ->setInvoiceDate($this->faker->dateTimeBetween('-7 years', '-1 days'))
            ->setSellerName($sellerName)
            ->setSellerAddress($sellerAddress)
            ->setSellerNip($sellerNip)
            ->setBuyerName($this->faker->company)
            ->setBuyerAddress($this->faker->address)
            ->setBuyerNip(NipGenerator::generateNip())
            ->setDescription('Sale of products or services')
            ->setUnit($this->faker->numberBetween(1,15))
            ->setPricePerUnit($this->faker->randomFloat(2, 0, 10000))
            ->setNetValue($invoice->getUnit()*$invoice->getPricePerUnit())
            ->setTaxRate($this->faker->randomElement(self::$invoiceTaxRate))
            ->setTaxValue(round($invoice->getNetValue() * $invoice->getTaxRate() / 100))
            ->setTotalValue($invoice->getNetValue() + $invoice->getTaxValue());
        return $invoice;
    }

    public function createManyInvoices(int $count)
    {   
        self::fakerLoader();
        $sellerName = $this->faker->company;
        $sellerAddress = $this->faker->address;
        $sellerNip = NipGenerator::generateNip();

        for ($i = 0; $i < $count; $i++) {
            $invoice = $this->createInvoice($sellerName, $sellerAddress, $sellerNip);

            $invoices[] = $invoice;
        }
        return $invoices;
    }
    
    public function createPDF(){
        $repository = $this->em->getRepository(Invoice::class);
        $invoice = $repository->findOneBy(['invoiceNumber' => 41614]);

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