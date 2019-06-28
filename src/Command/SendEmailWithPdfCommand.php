<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Email\EmailSender;

class SendEmailWithPdfCommand extends Command
{
    private $emailSender;
    public function __construct(string $name = null,EmailSender $emailSender)
    {
        parent::__construct($name);
        $this->emailSender = $emailSender;
    }
    protected static $defaultName = 'app:send-email';

    protected function configure()
    {
        $this
            ->setDescription('Sends email with invoice PDF attachement')
            ->addArgument('invoiceNumber', InputArgument::REQUIRED, 'Invoice number you want to create PDF from')
            ->addArgument('emailAddress', InputArgument::REQUIRED, 'Email to send invoice to')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $invoiceNumber = $input->getArgument('invoiceNumber');
        $emailAddress = $input->getArgument('emailAddress');
        $this->emailSender->sendEmail($emailAddress, $invoiceNumber);
    }
}
