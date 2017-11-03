<?php

namespace Yay\Component\Engine\Tests\AchievementValidator\Validator;

use PHPUnit\Framework\TestCase;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Yay\Component\Engine\AchievementValidator\Validator\ExpressionLanguageValidator;
use Yay\Component\Engine\AchievementValidator\ValidationContext;
use Yay\Component\Engine\AchievementValidator\ValidationHelper;

class ExpressionLanguageValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function expression_language_is_executed()
    {
        $context = $this->createMock(ValidationContext::class);
        $helper = $this->createMock(ValidationHelper::class);

        $validator1 = new ExpressionLanguageValidator('1 > 2');
        $this->assertFalse($validator1->validate($context, $helper));

        $validator2 = new ExpressionLanguageValidator('1 < 2');
        $this->assertTrue($validator2->validate($context, $helper));
    }

    /**
     * @test
     */
    public function expression_language_passes_player()
    {
        $this->markTestIncomplete( 'This test has not been implemented yet.' );
    }

    /**
     * @test
     */
    public function expression_language_passes_achievement()
    {
        $this->markTestIncomplete( 'This test has not been implemented yet.' );
    }

    /**
     * @test
     */
    public function expression_language_passes_personal_actions()
    {
        $this->markTestIncomplete( 'This test has not been implemented yet.' );
    }

    /**
     * @test
     */
    public function expression_language_passes_filtered_personal_actions()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }
}
