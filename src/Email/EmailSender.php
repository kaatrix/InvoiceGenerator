<?php

namespace App\Email;

use Swift_Attachment;

class EmailSender
{
    private $mailer;
    
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    
    public function prepareEmail($email, $invoiceNumber)
    {
        $message = (new \Swift_Message('Invoice to your order'))
            ->setFrom('czarodziejskasymfonia@gmail.com')
            ->setTo($email)
            ->attach(Swift_Attachment::fromPath(__DIR__ . '/../InvoiceContainer/invoices/'.$invoiceNumber.'.pdf'))
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
}