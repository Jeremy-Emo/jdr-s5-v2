<?php

namespace App\AbstractClass;

use App\Entity\Account;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractController extends SymfonyAbstractController
{
    /**
     * @return Account|null
     */
    public function getUser(): ?UserInterface
    {
        return parent::getUser();
    }
}