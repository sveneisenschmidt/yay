<?php

namespace App\Integration\Configuration;

use Symfony\Component\Config\Definition\Processor;
use Component\Entity\Achievement\ActionDefinition;
use Component\Entity\Achievement\AchievementDefinition;
use Component\Entity\Achievement\Level;
use Component\Engine\AchievementValidator\Validator\ExpressionLanguageValidator;
use Component\Webhook\Incoming\Processor\ChainProcessor as IncomingChainProcessor;
use Component\Webhook\Incoming\Processor\DummyProcessor as IncomingDummyProcessor;
use Component\Webhook\Incoming\Processor\NullProcessor as IncomingNullProcessor;
use Component\Webhook\Incoming\Processor\StaticMapProcessor as IncomingStaticMapProcessor;
use Component\Webhook\Incoming\Processor\SimpleProcessor as IncomingSimpleProcessor;
use Component\Webhook\Outgoing\Processor\NullProcessor as OutgoingNullProcessor;

class ConfigurationTransformer
{
    /** @var Processor */
    protected $processor;

    /** @var Configuration */
    protected $configuration;

    public function __construct(Processor $processor, Configuration $configuration)
    {
        $this->processor = $processor;
        $this->configuration = $configuration;
    }

    public function transformFromUnprocessedConfig(array $unprocessedConfig): array
    {
        $processedConfig = $this->processor->processConfiguration($this->configuration, $unprocessedConfig);
        $transformedConfigs = $this->transformFromProcessedConfig($processedConfig);

        return $transformedConfigs;
    }

    public function transformFromProcessedConfig(array $processedConfig): array
    {
        return [
            'entities.yml' => $this->transformToEntities($processedConfig),
            'services.yml' => $this->transformToServices($processedConfig),
        ];
    }

    public function transformToEntities(array $processedConfig): array
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
                'imageUrl' => $action['imageUrl'],
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
                'imageUrl' => $achievement['imageUrl'],
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
                'imageUrl' => $level['imageUrl'],
            ];
        }

        return $entities;
    }

    public function transformToServices(array $processedConfig): array
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
            if ('chain' === $processor['type']) {
                $processor['class'] = IncomingChainProcessor::class;
            }
            if ('dummy' === $processor['type']) {
                $processor['class'] = IncomingDummyProcessor::class;
            }
            if ('null' === $processor['type']) {
                $processor['class'] = IncomingNullProcessor::class;
            }
            if ('static-map' === $processor['type']) {
                $processor['class'] = IncomingStaticMapProcessor::class;
            }
            if ('simple' === $processor['type']) {
                $processor['class'] = IncomingSimpleProcessor::class;
            }

            $services['services'][$name] = [
                'class' => $processor['class'],
                'arguments' => array_merge([$name], $processor['arguments']),
                'tags' => ['yay.webhook_incoming.processor'],
            ];
        }

        foreach ($processedConfig['webhooks']['outgoing_processors'] as $name => $processor) {
            if ('null' === $processor['type']) {
                $processor['class'] = OutgoingNullProcessor::class;
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
