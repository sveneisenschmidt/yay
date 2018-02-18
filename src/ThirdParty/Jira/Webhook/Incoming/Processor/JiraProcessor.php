<?php

namespace ThirdParty\Jira\Webhook\Incoming\Processor;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\Request;
use Component\Webhook\Incoming\ProcessorInterface;

class JiraProcessor implements ProcessorInterface
{
    /** @var ExpressionLanguage */
    protected $lanuage;

    /** @var string */
    protected $name;

    /** @var array */
    protected $map;

    public function __construct(string $name, array $map = [])
    {
        $this->lanuage = new ExpressionLanguage();
        $this->name = $name;
        $this->map = $map;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function process(Request $request): void
    {
        $contents = $request->getContent(false);
        $payload = json_decode($contents, true, 32);

        if (null === $payload) {
            throw new \InvalidArgumentException('Could not decode json payload.');
        }

        if (!isset($payload['webhookEvent'])) {
            return;
        }

        $event = $payload['webhookEvent'];

        if (preg_match('/:(?P<action>[A-Za-z_]+)$/', $event, $matches) > 0) {
            $request->request->set('action', sprintf('jira.%s', $matches['action']));
        }

        if (isset($payload['user']['name'])) {
            $request->request->set('username', $payload['user']['name']);
        }

        foreach ($this->map as $mapAction => $mapExpression) {
            if ((bool) $this->lanuage->evaluate($mapExpression, $payload)) {
                $request->request->set('action', $mapAction);

                break;
            }
        }
    }
}
