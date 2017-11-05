<?php

namespace Yay\Component\Engine\Tests\AchievementValidator\Validator;

use PHPUnit\Framework\TestCase;
use Yay\Component\Engine\AchievementValidator\Validator\ExpressionLanguageValidator;
use Yay\Component\Engine\AchievementValidator\ValidationContext;
use Yay\Component\Entity\Achievement\AchievementDefinitionInterface;
use Yay\Component\Entity\Achievement\PersonalActionCollection;
use Yay\Component\Entity\PlayerInterface;

class ExpressionLanguageValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function expression_language_is_executed()
    {
        $context = $this->createMock(ValidationContext::class);

        $validator1 = new ExpressionLanguageValidator('1 > 2');
        $this->assertFalse($validator1->validate($context));

        $validator2 = new ExpressionLanguageValidator('1 < 2');
        $this->assertTrue($validator2->validate($context));
    }

    /**
     * @test
     */
    public function expression_language_supports_achievement()
    {
        $achievementDefinition1 = $this->createConfiguredMock(AchievementDefinitionInterface::class, [
            'getName' => 'test-achievement-01'
        ]);

        $achievementDefinition2 = $this->createConfiguredMock(AchievementDefinitionInterface::class, [
            'getName' => 'test-achievement-02'
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

    /**
     * @test
     */
    public function expression_language_multiple()
    {
        $this->assertFalse((new ExpressionLanguageValidator('true', []))->multiple());
        $this->assertTrue((new ExpressionLanguageValidator('true', [], true))->multiple());
        $this->assertFalse((new ExpressionLanguageValidator('true', [], false))->multiple());
    }

    /**
     * @test
     */
    public function expression_language_passes_player()
    {
        $context = $this->createConfiguredMock(ValidationContext::class, [
            'getPlayer' => $this->createMock(PlayerInterface::class)
        ]);

        $validator = new ExpressionLanguageValidator('player ? true : false');
        $this->assertTrue($validator->validate($context));
    }

    /**
     * @test
     */
    public function expression_language_passes_achievement()
    {
        $context = $this->createConfiguredMock(ValidationContext::class, [
            'getAchievementDefinition' => $this->createMock(AchievementDefinitionInterface::class)
        ]);

        $validator = new ExpressionLanguageValidator('achievement ? true : false');
        $this->assertTrue($validator->validate($context));
    }

    /**
     * @test
     */
    public function expression_language_passes_actions()
    {
        $context = $this->createConfiguredMock(ValidationContext::class, [
            'getFilteredPersonalActions' => $this->createMock(PersonalActionCollection::class)
        ]);

        $validator = new ExpressionLanguageValidator('actions ? true : false');
        $this->assertTrue($validator->validate($context));
    }
}
