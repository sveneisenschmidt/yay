<?php

namespace Yay\Component\Engine\Tests;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Yay\Component\Engine\Engine;
use Yay\Component\Engine\StorageInterface;
use Yay\Component\Engine\AchievementValidatorCollection;
use Yay\Component\Entity\Player;
use Yay\Component\Entity\PlayerInterface;
use Yay\Component\Entity\Achievement\ActionDefinition;
use Yay\Component\Entity\Achievement\PersonalAction;
use Yay\Component\Entity\Achievement\PersonalActionCollection;

class EngineTest extends TestCase
{
    /**
     * @test
     */
    public function get_achievement_validators_empty()
    {
        $storage = $this->createMock(StorageInterface::class);
        $engine = new Engine($storage);

        $this->assertNotNull($engine->getAchievementValidators());
    }

    /**
     * @test
     */
    public function get_achievement_validators_construct()
    {
        $storage = $this->createMock(StorageInterface::class);
        $achievementValidatorCollection = new AchievementValidatorCollection();
        $engine = new Engine($storage, $achievementValidatorCollection);

        $this->assertNotNull($engine->getAchievementValidators());
        $this->assertSame($achievementValidatorCollection, $engine->getAchievementValidators());
    }

    /**
     * @test
     */
    public function get_player_personal_actions()
    {
        $collection = new PersonalActionCollection();
        $player = $this->createConfiguredMock(PlayerInterface::class, ['getPersonalActions' => $collection]);
        $storage = $this->createMock(StorageInterface::class);
        $engine = new Engine($storage);

        $this->assertSame($collection, $engine->getPlayerPersonalActions($player));
    }

    /**
     * @test
     */
    public function get_player_personal_actions_from_arraycollection()
    {
        $player = $this->createConfiguredMock(PlayerInterface::class, ['getPersonalActions' => new ArrayCollection()]);
        $storage = $this->createMock(StorageInterface::class);
        $engine = new Engine($storage);

        $collection = $engine->getPlayerPersonalActions($player);
        $this->assertInstanceOf(PersonalActionCollection::class, $collection);
    }

    /**
     * @test
     */
    public function extract_action_definitions()
    {
        $player = $this->createMock(PlayerInterface::class);

        $personalActionCollection = new PersonalActionCollection();
        $personalActionCollection->add(new PersonalAction($player, $this->createMock(ActionDefinition::class)));
        $personalActionCollection->add(new PersonalAction($player, $this->createMock(ActionDefinition::class)));
        $personalActionCollection->add(new PersonalAction($player, $this->createMock(ActionDefinition::class)));

        $storage = $this->createMock(StorageInterface::class);
        $engine = new Engine($storage);
        $actionDefinitionCollection = $engine->extractActionDefinitions($personalActionCollection);
        $this->assertCount(3, $actionDefinitionCollection);
    }

    /**
     * @test
     */
    public function get_matching_achievement_definitions()
    {

    }
}
