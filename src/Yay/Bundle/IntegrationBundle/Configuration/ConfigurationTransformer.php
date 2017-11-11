<?php

namespace Yay\Bundle\IntegrationBundle\Configuration;

use Symfony\Component\Config\Definition\Processor;
use Yay\Component\Entity\Achievement\ActionDefinition;
use Yay\Component\Entity\Achievement\AchievementDefinition;
use Yay\Component\Entity\Achievement\Level;
use Yay\Component\Engine\AchievementValidator\Validator\ExpressionLanguageValidator;
use Yay\Component\Webhook\Incoming\Processor\ChainProcessor as IncomingChainProcessor;
use Yay\Component\Webhook\Incoming\Processor\DummyProcessor as IncomingDummyProcessor;
use Yay\Component\Webhook\Incoming\Processor\NullProcessor as IncomingNullProcessor;
use Yay\Component\Webhook\Outgoing\Processor\NullProcessor as OutgoingNullProcessor;

class ConfigurationTransformer
{
    /**
     * @param Processor     $processor
     * @param Configuration $configuration
     */
    public function __construct(Processor $processor, Configuration $configuration)
    {
        $this->processor = $processor;
        $this->configuration = $configuration;
    }

    /**
     * @param array $unprocessedConfig
     *
     * @return array
     */
    public function transformFromUnprocessedConfig(array $unprocessedConfig): array
    {
        $processedConfig = $this->processor->processConfiguration($this->configuration, $unprocessedConfig);
        $transformedConfigs = $this->transformFromProcessedConfig($processedConfig);

        return $transformedConfigs;
    }

    /**
     * @param array $processedConfig
     *
     * @return array
     */
    public function transformFromProcessedConfig(array $processedConfig): array
    {
        return [
            'entities.yml' => $this->transformToEntities($processedConfig),
            'services.yml' => $this->transformToServices($processedConfig),
        ];
    }

    /**
     * @param array $processedConfig
     *
     * @return array
     */
    public function transformToEntities(array $processedConfig)
    {
        $entities = [];

        foreach ($processedConfig['actions'] as $name => $action) {
            if (!isset($entities[ActionDefinition::class])) {
                $entities[ActionDefinition::class] = [];
            }
            $entities[ActionDefinition::class][$name] = [
                '__construct' => [$name],
                'label' => $action['label'],
                'description' => $action['description'],
            ];
        }

        foreach ($processedConfig['achievements'] as $name => $achievement) {
            if (!isset($entities[AchievementDefinition::class])) {
                $entities[AchievementDefinition::class] = [];
            }
            $entities[AchievementDefinition::class][$name] = [
                '__construct' => [$name],
                '__calls' => !empty($achievement['actions'])
                    ? array_map(function (string $action) {
                        return ['addActionDefinition' => [sprintf('@%s', $action)]];
                    }, $achievement['actions'])
                    : [],
                'label' => $achievement['label'],
                'description' => $achievement['description'],
                'points' => $achievement['points'],
            ];
        }

        foreach ($processedConfig['levels'] as $name => $level) {
            if (!isset($entities[Level::class])) {
                $entities[Level::class] = [];
            }
            $entities[Level::class][$name] = [
                '__construct' => [$name, $level['level'], $level['points']],
                'label' => $level['label'],
                'description' => $level['description'],
            ];
        }

        return $entities;
    }

    /**
     * @param array $processedConfig
     *
     * @return array
     */
    public function transformToServices(array $processedConfig)
    {
        $services = [
            'services' => [
                '_defaults' => ['autoconfigure' => true],
            ],
        ];

        foreach ($processedConfig['validators'] as $name => $validator) {
            if ('expression' === $validator['type']) {
                $validator['class'] = ExpressionLanguageValidator::class;
            }

            $services['services'][$name] = [
                'class' => $validator['class'],
                'arguments' => $validator['arguments'],
                'tags' => ['yay.achievement_validator'],
            ];
        }

        foreach ($processedConfig['webhooks']['incoming_processors'] as $name => $processor) {
            switch ($processor['type']) {
                case 'chain':
                    $processor['class'] = IncomingChainProcessor::class;
                    break;
                case 'dummy':
                    $processor['class'] = IncomingDummyProcessor::class;
                    break;
                case 'null':
                    $processor['class'] = IncomingNullProcessor::class;
                    break;
            }

            $services['services'][$name] = [
                'class' => $processor['class'],
                'arguments' => array_merge([$name], $processor['arguments']),
                'tags' => ['yay.webhook_incoming.processor'],
            ];
        }

        foreach ($processedConfig['webhooks']['outgoing_processors'] as $name => $processor) {
            switch ($processor['type']) {
                case 'null':
                    $processor['class'] = OutgoingNullProcessor::class;
                    break;
            }

            $services['services'][$name] = [
                'class' => $processor['class'],
                'arguments' => array_merge([$name], $processor['arguments']),
                'tags' => ['yay.webhook_outgoing.processor'],
            ];
        }

        return $services;
    }
}
