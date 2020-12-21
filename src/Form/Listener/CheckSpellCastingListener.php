<?php

namespace App\Form\Listener;

use App\Entity\FighterItem;
use App\Exception\ListenerException;
use App\Repository\FighterInfosRepository;
use App\Repository\FighterSkillRepository;
use App\Scenario\Battle\ContinueBattleScenario;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

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
     * @throws ListenerException
     */
    public function onSubmit(FormEvent $event): void
    {
        $form = $event->getForm();
        $turnAction = $event->getData();

        if ($turnAction["action"] !== null && $turnAction["action"] !== ContinueBattleScenario::ATTACK_WITH_WEAPON) {
            //Check silence
            if (
                isset($this->actor['statuses'])
                && !empty($this->actor['statuses']['silence'])
            ) {
                throw new ListenerException("Le personnage est sous effet de silence.");
            }

            $fsSkill = $this->fighterSkillRepository->find($turnAction["action"]);
            if ($fsSkill === null) {
                throw new ListenerException("Missing informations.");
            }
            $skill = $fsSkill->getSkill();

            //Check costs (do not check SP because it turn into damages)
            if ($skill->getMpCost() > $this->actor['currentMP']) {
                $form->addError(new FormError("Mana insuffisant."));
            }
            if ($skill->getHpCost() > $this->actor['currentHP']) {
                $form->addError(new FormError("PV insuffisant."));
            }

            //Check if valid target
            if (
                $skill->getFightingSkillInfo() !== null
                && $skill->getFightingSkillInfo()->getIsOnSelfOnly()
                && $turnAction["target"] !== $this->actor["id"]
            ) {
                $form->addError(new FormError("Ne peut s'utiliser que sur soi."));
            }

            //Check if weapon is ok
            if (
                $skill->getFightingSkillInfo() !== null
                && $skill->getFightingSkillInfo()->getNeedWeaponType() !== null
                && !$this->actor['ennemy']
            ) {
                if (isset($this->actor['invokedWeapon'])) {
                    //TODO : implement here when invokedWeapon are released
                    throw new ListenerException("Not implemented yet.");
                } else {
                    $dbActor = $this->fighterRepository->find($this->actor['id']);
                    if ($dbActor === null) {
                        throw new ListenerException("Actor not found");
                    }
                    $stuff = $dbActor->getEquippedWeapons();
                    $willProcError = true;
                    /** @var FighterItem $weapon */
                    foreach ($stuff as $weapon) {
                        if ($weapon->getItem()->getBattleItemInfo()->getWeaponType() === $skill->getFightingSkillInfo()->getNeedWeaponType()) {
                            $willProcError = false;
                        }
                    }
                    if ($willProcError) {
                        $form->addError(new FormError("Le type d'arme nécessaire n'est pas équipé."));
                    }
                }
            }

            //Check if valid status for use
            if (
                $skill->getFightingSkillInfo() !== null
                && $skill->getFightingSkillInfo()->getNeedStatusToCast()->count() > 0
            ) {
                $isGood = true;
                if (isset($this->actor['statuses'])) {
                    foreach ($skill->getFightingSkillInfo()->getNeedStatusToCast() as $neededStatus) {
                        $thisStatusIsOk = false;
                        foreach ($this->actor['statuses'] as $key => $status) {
                            if ($key === $neededStatus->getNameId()) {
                                $thisStatusIsOk = true;
                            }
                        }
                        if (!$thisStatusIsOk) {
                            $isGood = false;
                        }
                    }
                } else {
                    $isGood = false;
                }
                if (!$isGood) {
                    $form->addError(new FormError("Les statuts nécessaires au lancement ne sont pas présents."));
                }
            }

            //Check if prerequites are ok
            if (
                $skill->getNeedSkill() !== null
                && !empty($skill->getNeededSkillLevel())
            ) {
                $dbActor = $this->fighterRepository->find($this->actor['id']);
                if ($dbActor === null) {
                    throw new ListenerException("Actor not found");
                }
                $isGood = false;
                foreach ($dbActor->getSkills() as $dbSkill) {
                    if (
                        $dbSkill->getSkill() === $skill->getNeedSkill()
                        && $dbSkill->getLevel() >= $skill->getNeededSkillLevel()
                    ) {
                        $isGood = true;
                    }
                }
                if (!$isGood) {
                    $form->addError(new FormError("Les prérequis ne sont pas remplis."));
                }
            }
        }
    }
}