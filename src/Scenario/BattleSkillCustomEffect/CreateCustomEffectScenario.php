<?php


namespace App\Scenario\BattleSkillCustomEffect;


use App\AbstractClass\AbstractScenario;
use App\Exception\ScenarioException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class CreateCustomEffectScenario extends AbstractScenario
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
     * @return Response
     * @throws ScenarioException
     */
    public function handle(FormInterface $form): Response
    {
        if($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($form->getData());
            $this->manager->flush();

            return $this->redirectToRoute('admin_listSkills');
        }

        return $this->renderNewResponse('admin/defaultGenericForm.html.twig', [
            'form' => $form->createView(),
            'title' => 'create_skill_custom_effect'
        ]);
    }
}