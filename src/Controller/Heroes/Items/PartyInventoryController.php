<?php

namespace App\Controller\Heroes\Items;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\HeroRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PartyInventoryController
 * @package App\Controller\Heroes\Items
 * @Route("/heros/{id<\d+>}/inventaire-de-groupe", name="partyInventory")
 * @IsGranted("ROLE_USER")
 */
class PartyInventoryController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public HeroRepository $heroRepository;

    /**
     * @param int $id
     * @return Response
     */
    public function __invoke(int $id): Response
    {
        if (!empty(array_intersect(
            ['ROLE_MJ', 'ROLE_ADMIN'],
            $this->getUser()->getRoles())
        )) {
            $hero = $this->heroRepository->find($id);
        } else {
            $hero = $this->heroRepository->findOneBy([
                'account' => $this->getUser(),
                'id' => $id,
            ]);
        }

        if ($hero === null || $hero->getParty() === null) {
            throw new NotFoundHttpException("Hero not found");
        }

        return $this->render('heroes/partyInventory.html.twig', [
            'hero' => $hero,
            'partyItems' => $hero->getParty()->getPartyItems(),
        ]);
    }
}