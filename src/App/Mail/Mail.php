<?php

namespace App\Mail;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\Mail\DependencyInjection\MailExtension;

class Mail extends Bundle
{
    public function build(ContainerBuilder $container)
    {
    }

    public function getContainerExtension(): MailExtension
    {
        return new MailExtension();
    }
}
