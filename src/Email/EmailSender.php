<?php

namespace App\Email;

use App\Factory\PdfFactory;
use App\Repository\InvoiceRepository;
use Swift_Attachment;

class EmailSender
{
    private $mailer;
    private $invoiceRepository;
    private $pdfFactory;
    
    public function __construct(\Swift_Mailer $mailer, PdfFactory $pdfFactory, InvoiceRepository $invoiceRepository)
    {
        $this->mailer = $mailer;
        $this->pdfFactory = $pdfFactory;
        $this->invoiceRepository = $invoiceRepository;
    }
    
    public function prepareEmail($email, $invoiceNumber)
    {
        $this->assertThatDocumentExists($invoiceNumber);
        $message = (new \Swift_Message('Invoice to your order'))
            ->setFrom('czarodziejskasymfonia@gmail.com')
            ->setTo($email)
            ->attach(Swift_Attachment::fromPath(__DIR__ . '/../Factory/invoices/'.$invoiceNumber.'.pdf'))
            ->setBody("Dear Customer<br>
            Thank you for shopping with our store! We thought you'd like to know your order has been completed and it has been despatched to you.<br>
            Your invoice is attached to this email in PDF format.<br>
            Kind Regards",'text/html');
               // ->addPart("Thank you for shopping with our store! We thought you'd like to know your order has been completed and it has been despatched to you.",'text/html')
                //->addPart('Your invoice is attached to this email in PDF format.','text/html')
                //->addPart('Kind Regards' ,'text/html');
        return $message;
    }

    public function sendEmail( $emailAddress, $invoiceNumber)
    {
        
        $this->mailer->send($this->prepareEmail($emailAddress, $invoiceNumber));
        
    }

    private function assertThatDocumentExists($invoiceNumber)
    {
        if(file_exists(__DIR__ . '/../Factory/invoices/'.$invoiceNumber.'.pdf')===false){
            $this->pdfFactory->createPDF($this->invoiceRepository->findOneByInvoiceNumber($invoiceNumber));
        }
    }
}