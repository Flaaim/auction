<?php

namespace App\Console;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Auth\Service\JoinConfirmationSender;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MailerCheckCommand extends Command
{
    private JoinConfirmationSender $confirmationSender;
    public function __construct(JoinConfirmationSender $confirmationSender)
    {
        parent::__construct();
        $this->confirmationSender = $confirmationSender;
    }
    protected function configure()
    {
        $this->setName('mailer:check');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Sending</comment>');

        $this->confirmationSender->send(
            new Email('user@app.test'),
            new Token(Uuid::uuid4()->toString(), new \DateTimeImmutable())
        );

        $output->writeln('<info>Email sent.</info>');

        return 0;
    }
}