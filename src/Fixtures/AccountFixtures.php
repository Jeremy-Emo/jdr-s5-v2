<?php

namespace App\Fixtures;

use App\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountFixtures extends Fixture implements FixtureGroupInterface
{
    protected UserPasswordEncoderInterface $passwordEncoder;

    /**
     * @param UserPasswordEncoderInterface $encoder
     * @required
     */
    public function setInterfaces(UserPasswordEncoderInterface $encoder): void
    {
        $this->passwordEncoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $account = (new Account())
            ->setIsAdmin(true)
            ->setUsername('MoiLeFabuleux')
            ->setIsPasswordChangeNeeded(false)
            ->setCreatedAt(new \DateTime())
        ;
        $account->setPassword($this->passwordEncoder->encodePassword($account, 'password1'));

        $manager->persist($account);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['prod'];
    }
}