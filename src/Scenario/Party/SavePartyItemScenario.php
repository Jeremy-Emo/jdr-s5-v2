<?php

namespace App\Scenario\Party;

use App\AbstractClass\AbstractScenario;
use App\Entity\Party;
use App\Entity\PartyItem;
use App\Exception\ScenarioException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class SavePartyItemScenario extends AbstractScenario
{
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
     * @param Party $party
     * @return Response
     * @throws ScenarioException
     */
    public function handle(FormInterface $form, Party $party): Response
    {
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var PartyItem $partyItem */
            $partyItem = $form->getData();
            $partyItem->setParty($party);
            $this->manager->persist($partyItem);
            $this->manager->flush();

            return $this->redirectToRoute('mj_partyInventory', [
                'id' => $party->getId(),
            ]);
        }

        return $this->renderNewResponse('admin/defaultGenericForm.html.twig', [
            'form' => $form->createView(),
            'title' => 'add_party_item',
        ]);
    }
}