<?php

namespace App\Form\Listener;

use App\Entity\Quest;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CheckQuestCompletionListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [FormEvents::PRE_SUBMIT => 'onPreSubmit'];
    }

    public function onPreSubmit(FormEvent $event): void
    {
        /** @var Quest $quest */
        $quest = $event->getData();
        $form = $event->getForm();

        $quest['isFailed'] = isset($quest['isFailed']) ? $quest['isFailed'] : false;

        if ($quest['isFailed'] && !empty($quest['completionRank'])) {
            $form->addError(new FormError("Il ne peut y avoir de rang de complétion si la quête est échouée."));
        }

        if (!empty($quest['completionRank'])) {
            foreach ($form->getData()->getRewards() as $reward) {
                if ($quest['completionRank'] !== (string)$reward->getCompletionRank()->getId()) {
                    $form->addError(new FormError("Ce rang de complétion n'est pas disponible parmi les récompenses."));
                }
            }
        } elseif (!$quest['isFailed']) {
            $form->addError(new FormError("La quête doit être soit échouée soit complétée !"));
        }
    }
}