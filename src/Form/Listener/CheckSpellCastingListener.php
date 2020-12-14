<?php

namespace App\Form\Listener;

use App\Repository\FighterInfosRepository;
use App\Repository\FighterSkillRepository;
use App\Scenario\Battle\ContinueBattleScenario;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckSpellCastingListener implements EventSubscriberInterface
{
    public FighterInfosRepository $fighterRepository;
    public FighterSkillRepository $fighterSkillRepository;
    public array $actor;

    public function __construct(
        FighterSkillRepository $fighterSkillRepository,
        FighterInfosRepository $fighterRepository,
        array $actor
    ) {
        $this->fighterRepository = $fighterRepository;
        $this->fighterSkillRepository = $fighterSkillRepository;
        $this->actor = $actor;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [FormEvents::SUBMIT => 'onSubmit'];
    }

    /**
     * @param FormEvent $event
     */
    public function onSubmit(FormEvent $event): void
    {
        $form = $event->getForm();
        $turnAction = $event->getData();

        //TODO : vérifier type d'arme

        if ($turnAction["action"] !== null && $turnAction["action"] !== ContinueBattleScenario::ATTACK_WITH_WEAPON) {
            $fsSkill = $this->fighterSkillRepository->find($turnAction["action"]);
            if ($fsSkill === null) {
                throw new NotFoundHttpException("Missing informations.");
            }
            $skill = $fsSkill->getSkill();

            if ($skill->getMpCost() > $this->actor['currentMP']) {
                $form->addError(new FormError("Mana insuffisant."));
            }
            if ($skill->getHpCost() > $this->actor['currentHP']) {
                $form->addError(new FormError("PV insuffisant."));
            }
            if (
                $skill->getFightingSkillInfo() !== null
                && $skill->getFightingSkillInfo()->getIsOnSelfOnly()
                && $turnAction["target"] !== $this->actor["id"]
            ) {
                $form->addError(new FormError("Ne peut s'utiliser que sur soi."));
            }
        }
    }
}