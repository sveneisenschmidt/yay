<?php

namespace Component\Engine\Tests\AchievementValidator;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Component\Engine\AchievementValidator\ValidationContext;
use Component\Entity\Achievement\AchievementDefinitionInterface;
use Component\Entity\PlayerInterface;
use Component\Entity\Achievement\PersonalActionCollection;
use Component\Entity\Achievement\PersonalActionInterface;
use Component\Entity\Achievement\ActionDefinitionInterface;

class ValidationContextTest extends TestCase
{
    /**
     * @test
     */
    public function set_get_construct()
    {
        $context = new ValidationContext(
            $player = $this->createMock(PlayerInterface::class),
            $achievementDefinition = $this->createMock(AchievementDefinitionInterface::class)
        );

        $this->assertSame($player, $context->getPlayer());
        $this->assertSame($achievementDefinition, $context->getAchievementDefinition());
    }

    /**
     * @test
     */
    public function get_personal_actions()
    {
        $player = $this->createConfiguredMock(PlayerInterface::class, [
            'getPersonalActions' => new ArrayCollection([]),
        ]);
        $achievementDefinition = $this->createMock(AchievementDefinitionInterface::class);
        $context = new ValidationContext($player, $achievementDefinition);

        $this->assertInstanceOf(PersonalActionCollection::class, $context->getPersonalActions());
    }

    /**
     * @test
     */
    public function get_filtered_personal_actions()
    {
        $date = new \DateTime();

        $actionDefnitions = [];
        $actionDefinitions[] = $this->createMock(ActionDefinitionInterface::class);

        $personalActions = [];
        $personalActions[] = $this->createConfiguredMock(PersonalActionInterface::class, [
            'getActionDefinition' => $actionDefinitions[0],
            'getAchievedAt' => $date,
        ]);

        $player = $this->createConfiguredMock(PlayerInterface::class, [
            'getPersonalActions' => new ArrayCollection($personalActions),
        ]);

        $achievementDefinition = $this->createConfiguredMock(AchievementDefinitionInterface::class, [
            'getActionDefinitions' => new ArrayCollection($actionDefinitions),
            'getCreatedAt' => $date,
        ]);

        $context = new ValidationContext($player, $achievementDefinition);
        $this->assertInstanceOf(PersonalActionCollection::class, $context->getFilteredPersonalActions());
        $this->assertNotEmpty($context->getFilteredPersonalActions());
    }
}
