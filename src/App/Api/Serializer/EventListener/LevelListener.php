<?php

namespace App\Api\Serializer\EventListener;

use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GenericSerializationVisitor;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Component\Entity\Achievement\AchievementDefinitionInterface;
use Component\Entity\Achievement\ActionDefinitionInterface;
use Component\Entity\Achievement\PersonalAchievementInterface;
use Component\Entity\Achievement\PersonalActionInterface;
use Component\Entity\PlayerInterface;
use Component\Entity\ActivityInterface;
use Component\Entity\Activity;

class LevelListener
{

    public function onPostSerialize(ObjectEvent $event): void
    {
        /** @var GenericSerializationVisitor $visitor */
        $visitor = $event->getVisitor();

        if (!$event->getObject() instanceof PlayerInterface) {
            return;
        }
    }
}
