<?php

namespace App\Controller;

use App\Entity\Invoice;
use Faker\Factory;
use Faker\Generator;
use App\NipContainer\NipGenerator;

class InvoiceController 
{
    /** @var Generator */
    private $faker;

    public function fakerLoader()
    {
       $this->faker = Factory::create();
    }

    private static $invoiceTaxRate = [23,8,5,0];

    public function createManyInvoices(int $count)
    {   
        self::fakerLoader();
        for ($i = 0; $i < $count; $i++) {
            $invoice = new Invoice();
            $invoice->setInvoiceNumber($this->faker->randomNumber())
                ->setInvoiceDate($this->faker->dateTimeBetween('-7 years', '-1 days'))
                ->setSellerName($this->faker->company)
                ->setSellerAddress($this->faker->address)
                ->setSellerNip(NipGenerator::generateNip())
                ->setBuyerName($this->faker->company)
                ->setBuyerAddress($this->faker->address)
                ->setBuyerNip(NipGenerator::generateNip())
                ->setNetValue($this->faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 1000000))
                ->setTaxRate($this->faker->randomElement(self::$invoiceTaxRate))
                ->setTaxValue($invoice->netValue * $invoice->taxRate / 100)
                ->setTotalValue($invoice->netValue + $invoice->taxValue);
            $invoices[] = $invoice;
        }
        return $invoices;
    }
}