<?php

namespace Yay\Bundle\EngineBundle\Controller;

use Yay\Component\Engine\Engine;
use Yay\Component\Entity\Achievement\PersonalAction;
use Yay\Component\Entity\Achievement\PersonalActionCollection;
use Yay\Component\Entity\Player;
use Yay\Component\Entity\PlayerInterface;

trait EngineControllerTrait
{
    public function advance(
        Engine $engine,
        string $username,
        array $actions
    ): array {
        if (empty($actions)) {
            throw $this->createNotFoundException(sprintf('Actions empty', $username));
        }

        $players = $engine->findPlayerBy(['username' => $username]);
        if ($players->isEmpty()) {
            throw $this->createNotFoundException(sprintf('Player "%s" not found', $username));
        }

        $player = $players->first();
        $personalActionCollection = new PersonalActionCollection();

        foreach ($actions as $action) {
            $actionDefinitions = $engine->findActionDefinitionBy(['name' => $action]);
            if ($actionDefinitions->isEmpty()) {
                continue;
            }

            $personalActionCollection->add(
                new PersonalAction($player, $actionDefinitions->first())
            );
        }

        return $engine->advance($player, $personalActionCollection);
    }
}
