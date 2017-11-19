<?php

namespace Component\Engine\Tests\AchievementValidator\Validator;

use PHPUnit\Framework\TestCase;
use Component\Engine\AchievementValidator\Validator\ExpressionLanguageValidator;
use Component\Engine\AchievementValidator\ValidationContext;
use Component\Entity\Achievement\AchievementDefinitionInterface;
use Component\Entity\Achievement\PersonalActionCollection;
use Component\Entity\PlayerInterface;

class ExpressionLanguageValidatorTest extends TestCase
{
    public function test_expression_language_is_executed(): void
    {
        $context = $this->createMock(ValidationContext::class);

        $validator1 = new ExpressionLanguageValidator('1 > 2');
        $this->assertFalse($validator1->validate($context));

        $validator2 = new ExpressionLanguageValidator('1 < 2');
        $this->assertTrue($validator2->validate($context));
    }

    public function test_expression_language_supports_achievement(): void
    {
        $achievementDefinition1 = $this->createConfiguredMock(AchievementDefinitionInterface::class, [
            'getName' => 'test-achievement-01',
        ]);

        $achievementDefinition2 = $this->createConfiguredMock(AchievementDefinitionInterface::class, [
            'getName' => 'test-achievement-02',
        ]);

        $this->assertTrue(
            (new ExpressionLanguageValidator('true'))->supports($achievementDefinition1)
        );
        $this->assertTrue(
            (new ExpressionLanguageValidator('true', ['test-achievement-01']))->supports($achievementDefinition1)
        );
        $this->assertFalse(
            (new ExpressionLanguageValidator('true', ['test-achievement-01']))->supports($achievementDefinition2)
        );
    }

    public function test_expression_language_multiple(): void
    {
        $this->assertFalse((new ExpressionLanguageValidator('true', []))->multiple());
        $this->assertTrue((new ExpressionLanguageValidator('true', [], true))->multiple());
        $this->assertFalse((new ExpressionLanguageValidator('true', [], false))->multiple());
    }

    public function test_expression_language_passes_player(): void
    {
        $context = $this->createConfiguredMock(ValidationContext::class, [
            'getPlayer' => $this->createMock(PlayerInterface::class),
        ]);

        $validator = new ExpressionLanguageValidator('player ? true : false');
        $this->assertTrue($validator->validate($context));
    }

    public function test_expression_language_passes_achievement(): void
    {
        $context = $this->createConfiguredMock(ValidationContext::class, [
            'getAchievementDefinition' => $this->createMock(AchievementDefinitionInterface::class),
        ]);

        $validator = new ExpressionLanguageValidator('achievement ? true : false');
        $this->assertTrue($validator->validate($context));
    }

    public function test_expression_language_passes_actions(): void
    {
        $context = $this->createConfiguredMock(ValidationContext::class, [
            'getFilteredPersonalActions' => $this->createMock(PersonalActionCollection::class),
        ]);

        $validator = new ExpressionLanguageValidator('actions ? true : false');
        $this->assertTrue($validator->validate($context));
    }
}
