<?php

namespace App\Engine\Controller;

use Component\Engine\Engine;
use Component\Entity\Achievement\ActionDefinitionCollection;
use Component\Entity\Player;

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
        $actionDefinitions = new ActionDefinitionCollection();

        foreach ($actions as $action) {
            $actionDefinition = $engine
                ->findActionDefinitionBy(['name' => $action])
                ->first();

            if (!$actionDefinition) {
                throw $this->createNotFoundException(sprintf('Action "%s" not found', $action));
            }

            $actionDefinitions->add($actionDefinition);
        }

        $personalAchievements = [];
        foreach ($actionDefinitions as $actionDefinition) {
            $personalAchievements = array_merge(
                $personalAchievements,
                $engine->advance($player, $actionDefinition)
            );
        }

        return $personalAchievements;
    }
}
