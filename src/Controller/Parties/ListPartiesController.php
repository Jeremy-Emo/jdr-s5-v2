<?php

namespace App\Controller\Parties;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\PartyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListPartiesController
 * @package App\Controller\Parties
 * @Route("/mj/liste-des-groupes", name="listParties")
 * @IsGranted("ROLE_MJ")
 */
class ListPartiesController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public PartyRepository $partyRepository;

    public function __invoke(): Response
    {
        $parties = $this->partyRepository->findBy([
            'mj' => $this->getUser()
        ]);
        return $this->render('party/list.html.twig', [
            'parties' => $parties
        ]);
    }
}