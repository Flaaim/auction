<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;

class MailerCheckCommand extends Command
{
    protected function configure()
    {
        $this->setName('mailer:check');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Sending</comment>');

        $transport = (new EsmtpTransport('mailer', '1025'))
            ->setUsername('app')
            ->setPassword('secret');

        $mailer = new Mailer($transport);

        $message = (new Email())
            ->subject('Join confirmation')
            ->from('mail@test.app')
            ->to('user@test.app')
            ->text('confirm');

        if($mailer->send($message) === 0){
            throw new \RuntimeException('Unable to send email.');
        }
        $output->writeln('<info>Email sent.</info>');

        return 0;
    }
}