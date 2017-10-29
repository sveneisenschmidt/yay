<?php

namespace Yay\Component\Engine\Tests;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Yay\Component\Engine\Engine;
use Yay\Component\Engine\StorageInterface;
use Yay\Component\Engine\AchievementValidatorInterface;
use Yay\Component\Engine\AchievementValidatorCollection;
use Yay\Component\Entity\PlayerInterface;
use Yay\Component\Entity\Achievement\AchievementDefinition;
use Yay\Component\Entity\Achievement\AchievementDefinitionCollection;
use Yay\Component\Entity\Achievement\ActionDefinition;
use Yay\Component\Entity\Achievement\ActionDefinitionCollection;
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
    public function extract_matching_achievement_definitions()
    {
        $actionDefinition1 = new ActionDefinition('test-action-1');
        $actionDefinitionCollection1 = new ActionDefinitionCollection();
        $actionDefinitionCollection1->add($actionDefinition1);

        $actionDefinition2 = new ActionDefinition('test-action-2');
        $actionDefinitionCollection2 = new ActionDefinitionCollection();
        $actionDefinitionCollection2->add($actionDefinition2);

        $actionDefinitionCollection3 = new ActionDefinitionCollection();
        $actionDefinitionCollection3->add($actionDefinition1);
        $actionDefinitionCollection3->add($actionDefinition2);

        $achievementDefinition1 = new AchievementDefinition('test-achievement-1');
        $achievementDefinition1->addActionDefinition($actionDefinition1);

        $achievementDefinition2 = new AchievementDefinition('test-achievement-2');
        $achievementDefinition2->addActionDefinition($actionDefinition2);

        $achievementDefinitionCollection = new AchievementDefinitionCollection();
        $achievementDefinitionCollection->add($achievementDefinition1);
        $achievementDefinitionCollection->add($achievementDefinition2);

        $storage = $this->createMock(StorageInterface::class);
        $engine = new Engine($storage);

        $collection1 = $engine->extractMatchingAchievementDefinitions($actionDefinitionCollection1, $achievementDefinitionCollection);
        $this->assertNotEmpty($collection1);
        $this->assertContains($achievementDefinition1, $collection1);
        $this->assertCount(1, $collection1);

        $collection2 = $engine->extractMatchingAchievementDefinitions($actionDefinitionCollection2, $achievementDefinitionCollection);
        $this->assertNotEmpty($collection2);
        $this->assertContains($achievementDefinition2, $collection2);
        $this->assertCount(1, $collection2);

        $collection3 = $engine->extractMatchingAchievementDefinitions($actionDefinitionCollection3, $achievementDefinitionCollection);
        $this->assertNotEmpty($collection3);
        $this->assertContains($achievementDefinition1, $collection3);
        $this->assertContains($achievementDefinition2, $collection3);
        $this->assertCount(2, $collection3);
    }

    /**
     * @test
     */
    public function advance_no_validators()
    {
        $actionDefinition = new ActionDefinition('test-action');
        $personalActionCollection = new PersonalActionCollection();

        $player = $this->createConfiguredMock(PlayerInterface::class, [
            'getPersonalActions' => $personalActionCollection,
            'hasPersonalAchievement' => false,
        ]);

        $personalAction = new PersonalAction($player, $actionDefinition);
        $personalActionCollection->add($personalAction);

        $achievementDefinition = new AchievementDefinition('test-achievement');
        $achievementDefinition->addActionDefinition($actionDefinition);
        $achievementDefinitionCollection = new AchievementDefinitionCollection();
        $achievementDefinitionCollection->add($achievementDefinition);

        $storage = $this->createConfiguredMock(StorageInterface::class, [
            'findAchievementDefinitionBy' => $achievementDefinitionCollection,
        ]);

        $engine = new Engine($storage);
        $results = $engine->advance($player);
        $this->assertEmpty($results);
    }

    /**
     * @test
     */
    public function advance_has_personal_achievement()
    {
        $actionDefinition = new ActionDefinition('test-action');
        $personalActionCollection = new PersonalActionCollection();

        $player = $this->createConfiguredMock(PlayerInterface::class, [
            'getPersonalActions' => $personalActionCollection,
            'hasPersonalAchievement' => true,
        ]);

        $personalAction = new PersonalAction($player, $actionDefinition);
        $personalActionCollection->add($personalAction);

        $achievementDefinition = new AchievementDefinition('test-achievement');
        $achievementDefinition->addActionDefinition($actionDefinition);
        $achievementDefinitionCollection = new AchievementDefinitionCollection();
        $achievementDefinitionCollection->add($achievementDefinition);

        $storage = $this->createConfiguredMock(StorageInterface::class, [
            'findAchievementDefinitionBy' => $achievementDefinitionCollection,
        ]);

        $engine = new Engine($storage);
        $validator = $this->createConfiguredMock(AchievementValidatorInterface::class, [
            'supports' => true,
            'validate' => false,
        ]);
        $engine->getAchievementValidators()->add($validator);

        $results = $engine->advance($player);
        $this->assertEmpty($results);
    }

    /**
     * @test
     */
    public function advance_no_supported_validator()
    {
        $actionDefinition = new ActionDefinition('test-action');
        $personalActionCollection = new PersonalActionCollection();

        $player = $this->createConfiguredMock(PlayerInterface::class, [
            'getPersonalActions' => $personalActionCollection,
            'hasPersonalAchievement' => false,
        ]);

        $personalAction = new PersonalAction($player, $actionDefinition);
        $personalActionCollection->add($personalAction);

        $achievementDefinition = new AchievementDefinition('test-achievement');
        $achievementDefinition->addActionDefinition($actionDefinition);
        $achievementDefinitionCollection = new AchievementDefinitionCollection();
        $achievementDefinitionCollection->add($achievementDefinition);

        $storage = $this->createConfiguredMock(StorageInterface::class, [
            'findAchievementDefinitionBy' => $achievementDefinitionCollection,
        ]);

        $engine = new Engine($storage);
        $validator = $this->createConfiguredMock(AchievementValidatorInterface::class, [
            'supports' => false,
            'validate' => false,
        ]);
        $engine->getAchievementValidators()->add($validator);

        $results = $engine->advance($player);
        $this->assertEmpty($results);
    }

    /**
     * @test
     */
    public function advance_grant_achievement()
    {
        $actionDefinition = new ActionDefinition('test-action');
        $personalActionCollection = new PersonalActionCollection();

        $player = $this->createConfiguredMock(PlayerInterface::class, [
            'getPersonalActions' => $personalActionCollection,
            'hasPersonalAchievement' => false,
        ]);

        $personalAction = new PersonalAction($player, $actionDefinition);
        $personalActionCollection->add($personalAction);

        $achievementDefinition = new AchievementDefinition('test-achievement');
        $achievementDefinition->addActionDefinition($actionDefinition);
        $achievementDefinitionCollection = new AchievementDefinitionCollection();
        $achievementDefinitionCollection->add($achievementDefinition);

        $storage = $this->createConfiguredMock(StorageInterface::class, [
            'findAchievementDefinitionBy' => $achievementDefinitionCollection,
        ]);

        $engine = new Engine($storage);
        $validator = $this->createConfiguredMock(AchievementValidatorInterface::class, [
            'supports' => true,
            'validate' => true,
        ]);
        $engine->getAchievementValidators()->add($validator);

        $results = $engine->advance($player);
        $this->assertNotEmpty($results);
    }
}
