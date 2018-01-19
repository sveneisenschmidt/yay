<?php

namespace Component\Engine\Tests;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Component\Engine\Engine;
use Component\Engine\Storage\StorageInterface;
use Component\Engine\AchievementValidatorInterface;
use Component\Engine\AchievementValidatorCollection;
use Component\Entity\PlayerInterface;
use Component\Entity\Achievement\AchievementDefinition;
use Component\Entity\Achievement\AchievementDefinitionCollection;
use Component\Entity\Achievement\ActionDefinition;
use Component\Entity\Achievement\ActionDefinitionCollection;
use Component\Entity\Achievement\PersonalAction;
use Component\Entity\Achievement\PersonalActionCollection;
use Component\Entity\Achievement\LevelCollection;
use Component\Entity\Achievement\PersonalAchievementCollection;
use Component\Entity\Achievement\Level;
use Component\Entity\Player;
use Component\Entity\Achievement\PersonalAchievement;

class EngineTest extends TestCase
{
    public function test_get_achievement_validators_empty(): void
    {
        $storage = $this->createMock(StorageInterface::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $engine = new Engine($storage, $eventDispatcher);

        $this->assertNotNull($engine->getAchievementValidators());
    }

    public function test_get_achievement_validators_construct(): void
    {
        $storage = $this->createMock(StorageInterface::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $achievementValidatorCollection = new AchievementValidatorCollection();
        $engine = new Engine($storage, $eventDispatcher, $achievementValidatorCollection);

        $this->assertNotNull($engine->getAchievementValidators());
        $this->assertSame($achievementValidatorCollection, $engine->getAchievementValidators());
    }

    public function test_get_player_personal_actions(): void
    {
        $collection = new PersonalActionCollection();
        $player = $this->createConfiguredMock(PlayerInterface::class, ['getPersonalActions' => $collection]);
        $storage = $this->createMock(StorageInterface::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $engine = new Engine($storage, $eventDispatcher);

        $this->assertSame($collection, $engine->getPlayerPersonalActions($player));
    }

    public function test_get_player_personal_actions_from_arraycollection(): void
    {
        $player = $this->createConfiguredMock(PlayerInterface::class, ['getPersonalActions' => new ArrayCollection()]);
        $storage = $this->createMock(StorageInterface::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $engine = new Engine($storage, $eventDispatcher);

        $collection = $engine->getPlayerPersonalActions($player);
        $this->assertInstanceOf(PersonalActionCollection::class, $collection);
    }

    public function test_extract_action_definitions(): void
    {
        $player = $this->createMock(PlayerInterface::class);

        $personalActionCollection = new PersonalActionCollection();
        $personalActionCollection->add(new PersonalAction($player, $this->createMock(ActionDefinition::class)));
        $personalActionCollection->add(new PersonalAction($player, $this->createMock(ActionDefinition::class)));
        $personalActionCollection->add(new PersonalAction($player, $this->createMock(ActionDefinition::class)));

        $storage = $this->createMock(StorageInterface::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $engine = new Engine($storage, $eventDispatcher);
        $actionDefinitionCollection = $engine->extractActionDefinitions($personalActionCollection);
        $this->assertCount(3, $actionDefinitionCollection);
    }

    public function test_extract_matching_achievement_definitions(): void
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
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $engine = new Engine($storage, $eventDispatcher);

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

    public function test_advance_no_achievement_definitions(): void
    {
        $actionDefinition = new ActionDefinition('test-action');
        $personalActionCollection = new PersonalActionCollection();

        $player = $this->createConfiguredMock(PlayerInterface::class, [
            'getPersonalActions' => $personalActionCollection,
            'hasPersonalAchievement' => false,
        ]);

        $personalAction = new PersonalAction($player, $actionDefinition);
        $personalActionCollection->add($personalAction);

        $storage = $this->createConfiguredMock(StorageInterface::class, [
            'findAchievementDefinitionBy' => new AchievementDefinitionCollection(),
            'findLevelBy' => new LevelCollection(),
        ]);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $engine = new Engine($storage, $eventDispatcher);
        $results = $engine->advance($player);
        $this->assertEmpty($results);
    }

    public function test_advance_no_validators(): void
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
            'findLevelBy' => new LevelCollection(),
        ]);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $engine = new Engine($storage, $eventDispatcher);
        $results = $engine->advance($player);
        $this->assertEmpty($results);
    }

    public function test_advance_has_personal_achievement(): void
    {
        $actionDefinition = new ActionDefinition('test-action');
        $personalActionCollection = new PersonalActionCollection();

        $player = $this->createConfiguredMock(PlayerInterface::class, [
            'getPersonalActions' => $personalActionCollection,
            'hasPersonalAchievement' => true,
            'getPersonalAchievements' => new PersonalAchievementCollection(),
        ]);

        $personalAction = new PersonalAction($player, $actionDefinition);
        $personalActionCollection->add($personalAction);

        $achievementDefinition = new AchievementDefinition('test-achievement');
        $achievementDefinition->addActionDefinition($actionDefinition);
        $achievementDefinitionCollection = new AchievementDefinitionCollection();
        $achievementDefinitionCollection->add($achievementDefinition);

        $storage = $this->createConfiguredMock(StorageInterface::class, [
            'findAchievementDefinitionBy' => $achievementDefinitionCollection,
            'findLevelBy' => new LevelCollection(),
        ]);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $engine = new Engine($storage, $eventDispatcher);
        $validator = $this->createConfiguredMock(AchievementValidatorInterface::class, [
            'supports' => true,
            'validate' => false,
            'multiple' => false,
        ]);
        $engine->getAchievementValidators()->add($validator);

        $results = $engine->advance($player);
        $this->assertEmpty($results);
    }

    public function test_advance_no_supported_validator(): void
    {
        $actionDefinition = new ActionDefinition('test-action');
        $personalActionCollection = new PersonalActionCollection();

        $player = $this->createConfiguredMock(PlayerInterface::class, [
            'getPersonalActions' => $personalActionCollection,
            'hasPersonalAchievement' => false,
            'getPersonalAchievements' => new PersonalAchievementCollection(),
        ]);

        $personalAction = new PersonalAction($player, $actionDefinition);
        $personalActionCollection->add($personalAction);

        $achievementDefinition = new AchievementDefinition('test-achievement');
        $achievementDefinition->addActionDefinition($actionDefinition);
        $achievementDefinitionCollection = new AchievementDefinitionCollection();
        $achievementDefinitionCollection->add($achievementDefinition);

        $storage = $this->createConfiguredMock(StorageInterface::class, [
            'findAchievementDefinitionBy' => $achievementDefinitionCollection,
            'findLevelBy' => new LevelCollection(),
        ]);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $engine = new Engine($storage, $eventDispatcher);
        $validator = $this->createConfiguredMock(AchievementValidatorInterface::class, [
            'supports' => false,
            'validate' => false,
            'multiple' => false,
        ]);
        $engine->getAchievementValidators()->add($validator);

        $results = $engine->advance($player);
        $this->assertEmpty($results);
    }

    public function test_advance_grant_achievement_not_multiple(): void
    {
        $actionDefinition = new ActionDefinition('test-action');
        $personalActionCollection = new PersonalActionCollection();

        $player = $this->createConfiguredMock(PlayerInterface::class, [
            'getPersonalActions' => $personalActionCollection,
            'hasPersonalAchievement' => false,
            'getPersonalAchievements' => new PersonalAchievementCollection(),
        ]);

        $personalAction = new PersonalAction($player, $actionDefinition);
        $personalActionCollection->add($personalAction);

        $achievementDefinition = new AchievementDefinition('test-achievement');
        $achievementDefinition->addActionDefinition($actionDefinition);
        $achievementDefinitionCollection = new AchievementDefinitionCollection();
        $achievementDefinitionCollection->add($achievementDefinition);

        $storage = $this->createConfiguredMock(StorageInterface::class, [
            'findAchievementDefinitionBy' => $achievementDefinitionCollection,
            'findLevelBy' => new LevelCollection(),
        ]);

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $engine = new Engine($storage, $eventDispatcher);
        $validator = $this->createConfiguredMock(AchievementValidatorInterface::class, [
            'supports' => true,
            'validate' => true,
            'multiple' => false,
        ]);
        $engine->getAchievementValidators()->add($validator);

        $results = $engine->advance($player);
        $this->assertNotEmpty($results);
    }

    public function test_advance_grant_achievement_multiple(): void
    {
        $actionDefinition = new ActionDefinition('test-action');
        $personalActionCollection = new PersonalActionCollection();

        $player = $this->createConfiguredMock(PlayerInterface::class, [
            'getPersonalActions' => $personalActionCollection,
            'hasPersonalAchievement' => false,
            'getPersonalAchievements' => new PersonalAchievementCollection(),
        ]);

        $personalAction = new PersonalAction($player, $actionDefinition);
        $personalActionCollection->add($personalAction);

        $achievementDefinition = new AchievementDefinition('test-achievement');
        $achievementDefinition->addActionDefinition($actionDefinition);
        $achievementDefinitionCollection = new AchievementDefinitionCollection();
        $achievementDefinitionCollection->add($achievementDefinition);

        $storage = $this->createConfiguredMock(StorageInterface::class, [
            'findAchievementDefinitionBy' => $achievementDefinitionCollection,
            'findLevelBy' => new LevelCollection(),
        ]);

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $engine = new Engine($storage, $eventDispatcher);
        $validator = $this->createConfiguredMock(AchievementValidatorInterface::class, [
            'supports' => true,
            'validate' => true,
            'multiple' => true,
        ]);
        $engine->getAchievementValidators()->add($validator);

        $results = $engine->advance($player);
        $this->assertNotEmpty($results);

        $results = $engine->advance($player);
        $this->assertNotEmpty($results);
    }

    public function test_refresh_score(): void
    {
        $player = new Player();

        $achievementDefinition1 = new AchievementDefinition('test-achievement-1');
        $achievementDefinition1->setPoints($points1 = rand(1, 100));
        $personalAchievement1 = new PersonalAchievement($player, $achievementDefinition1);

        $achievementDefinition2 = new AchievementDefinition('test-achievement-2');
        $achievementDefinition2->setPoints($points2 = rand(1, 100));
        $personalAchievement2 = new PersonalAchievement($player, $achievementDefinition2);

        $player->getPersonalAchievements()->add($personalAchievement1);
        $player->getPersonalAchievements()->add($personalAchievement2);

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $storage = $this->createMock(StorageInterface::class);

        $engine = new Engine($storage, $eventDispatcher);
        $engine->refreshScore($player);

        $this->assertEquals($points1 + $points2, $player->getScore());
    }

    public function test_refresh_level(): void
    {
        $levelCollection = new LevelCollection();
        $levelCollection->add(new Level(1, 1, 1));
        $levelCollection->add(new Level(2, 2, 2));
        $levelCollection->add(new Level(3, 3, 3));

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $storage = $this->createConfiguredMock(StorageInterface::class, [
            'findLevelBy' => $levelCollection,
        ]);

        $player = new Player();
        $player->setScore(2);

        $engine = new Engine($storage, $eventDispatcher);
        $engine->refreshLevel($player);

        $this->assertEquals(2, $player->getLevel());
    }
}
