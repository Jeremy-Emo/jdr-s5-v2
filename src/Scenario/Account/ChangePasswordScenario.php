<?php

namespace App\Scenario\Account;

use App\AbstractClass\AbstractScenario;
use App\Exception\ScenarioException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;

class ChangePasswordScenario extends AbstractScenario
{
    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        LoggerInterface $logger
    ) {
        parent::__construct($entityManager, $urlGenerator, $twig, $logger);
    }

    protected UserPasswordEncoderInterface $encoder;

    /**
     * @required
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function setInterfaces(
        UserPasswordEncoderInterface $passwordEncoder
    ): void {
        $this->encoder = $passwordEncoder;
    }

    /**
     * @param UserInterface $user
     * @param FormInterface $form
     * @param string $routeToRedirect
     * @return Response
     * @throws ScenarioException
     */
    public function handle(UserInterface $user, FormInterface $form, string $routeToRedirect = 'index'): Response
    {
        if($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $this->encoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            )->setIsPasswordChangeNeeded(false);
            $this->manager->persist($user);
            $this->manager->flush();

            return $this->redirectToRoute($routeToRedirect);
        }

        return $this->renderNewResponse('account/newPassword.html.twig', [
            'form' => $form->createView()
        ]);
    }
}