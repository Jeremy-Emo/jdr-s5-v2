<?php

namespace App\Scenario\Party;

use App\AbstractClass\AbstractScenario;
use App\Entity\Account;
use App\Entity\Party;
use App\Exception\ScenarioException;
use App\Repository\HeroRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class SavePartyScenario extends AbstractScenario
{
    /** @required  */
    public HeroRepository $heroRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        LoggerInterface $logger
    ) {
        parent::__construct($entityManager, $urlGenerator, $twig, $logger);
    }

    /**
     * @param FormInterface $form
     * @param Account $account
     * @param bool $isEdit
     * @return Response
     * @throws ScenarioException
     */
    public function handle(FormInterface $form, Account $account, bool $isEdit = false): Response
    {
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Party $party */
            $party = $form->getData();

            if ($account->getCurrentParty() === null) {
                $party->setIsActive(true);
            }
            $party->setMj($account);

            $this->manager->persist($party);
            $this->manager->flush();

            return $this->redirectToRoute('listParties');
        }

        return $this->renderNewResponse('party/save.twig', [
            'form' => $form->createView(),
            'title' => $isEdit ? 'edit' : 'create',
        ]);
    }
}