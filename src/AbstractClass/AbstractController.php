<?php

namespace App\AbstractClass;

use App\Entity\Account;
use Psr\Log\LoggerInterface;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractController extends SymfonyAbstractController
{
    /** @required */
    public LoggerInterface $logger;

    /**
     * @return Account|null
     */
    public function getUser(): ?UserInterface
    {
        return parent::getUser();
    }
}