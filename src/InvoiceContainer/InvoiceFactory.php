<?php

namespace App\InvoiceContainer;

use App\Entity\Invoice;
use Faker\Factory;
use Faker\Generator;
use App\NipContainer\NipGenerator;
use Doctrine\ORM\EntityManager;

class InvoiceFactory
{
    /** @var Generator */
    private $faker;

    private $em;
    
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function fakerLoader()
    {
       $this->faker = Factory::create();
    }

    private static $invoiceTaxRate = [23,8,5,0];

    public function new()
    {
        self::fakerLoader();
        $sellerName = $this->faker->company;
        $sellerAddress = $this->faker->address;
        $sellerNip = NipGenerator::generateNip();

        $invoice = new Invoice();
        $invoice->setInvoiceNumber((string)$this->faker->randomNumber())
            ->setInvoiceDate($this->faker->dateTimeBetween('-7 years', '-1 days'))
            ->setSellerName($sellerName)
            ->setSellerAddress($sellerAddress)
            ->setSellerNip($sellerNip)
            ->setBuyerName($this->faker->company)
            ->setBuyerAddress($this->faker->address)
            ->setBuyerNip(NipGenerator::generateNip())
            ->setNetValue($this->faker->randomFloat(2, 0, 1000000))
            ->setTaxRate($this->faker->randomElement(self::$invoiceTaxRate))
            ->setTaxValue(round($invoice->getNetValue() * $invoice->getTaxRate() / 100))
            ->setTotalValue($invoice->getNetValue() + $invoice->getTaxValue());
        return $invoice;
    }

    public function createManyInvoices(int $count)
    {   

        for ($i = 0; $i < $count; $i++) {
            $invoice = $this->new();
            $this->em->persist($invoice);

            $invoices[] = $invoice;
        }
        $this->em->flush();
        return $invoices;
    }
}