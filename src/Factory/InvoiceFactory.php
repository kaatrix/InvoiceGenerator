<?php

namespace App\Factory;

use App\Entity\Invoice;
use Faker\Factory;
use Faker\Generator;
use App\Nip\NipGenerator;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceFactory
{
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

    public function createInvoices(int $count)
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
    
}