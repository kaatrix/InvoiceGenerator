<?php

namespace App\Service;

use App\Factory\InvoiceFactory;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceService
{
    private $invoiceFactory;
    public function __construct( EntityManagerInterface $em, InvoiceFactory $invoiceFactory)
    {
        $this->em = $em;
        $this->invoiceFactory = $invoiceFactory;
    }

    public function saveInvoicesToDatabase($count)
    {
        foreach ($this->invoiceFactory->createInvoices($count) as $invoice) {
            $this->em->persist($invoice);
        }
 
        $this->em->flush();
    }
}