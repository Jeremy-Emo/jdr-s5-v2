<?php

namespace App\Form\Listener;

use App\Repository\BattleRepository;
use App\Repository\FighterInfosRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CheckSpellCastingListener implements EventSubscriberInterface
{
    /** @required  */
    public BattleRepository $battleRepository;

    /** @required  */
    public FighterInfosRepository $fighterRepository;

    public static function getSubscribedEvents(): array
    {
        return [FormEvents::PRE_SUBMIT => 'onPreSubmit'];
    }

    public function onPreSubmit(FormEvent $event): void
    {
        $form = $event->getForm();
        //TODO : finish this
    }
}