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
use Twig\Environment;

class CreateAccountScenario extends AbstractScenario
{
    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        LoggerInterface $logger
    ) {
        parent::__construct($entityManager, $urlGenerator, $twig, $logger);
    }

    /** @required */
    public UserPasswordEncoderInterface $encoder;

    /**
     * @param FormInterface $form
     * @return Response
     * @throws ScenarioException
     */
    public function handle(FormInterface $form): Response
    {
        if($form->isSubmitted() && $form->isValid()) {
            $account = $form->getData();
            $account
                ->setPassword(
                    $this->encoder->encodePassword(
                        $account,
                        $form->get('plainPassword')->getData()
                    )
                )
                ->setIsPasswordChangeNeeded(true)
            ;
            $this->manager->persist($account);
            $this->manager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->renderNewResponse('admin/createAccount.html.twig', [
            'form' => $form->createView()
        ]);
    }
}