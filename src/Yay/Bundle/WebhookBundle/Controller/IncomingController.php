<?php

namespace Yay\Bundle\WebhookBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Yay\Bundle\EngineBundle\Controller\EngineControllerTrait;
use Yay\Component\Engine\Engine;
use Yay\Component\Webhook\Webhook;

/**
 * @Route("/incoming")
 */
class IncomingController extends Controller
{
    use EngineControllerTrait;

    /**
     * @Method("POST")
     * @Route(
     *     "/{processorName}/",
     *     name="webhook_incoming_submit_post"
     * )
     */
    public function submitPostAction(
        Engine $engine,
        Webhook $webhook,
        Request $request,
        UrlGeneratorInterface $urlGenerator,
        string $processorName
    ): Response {
        if (!$webhook->getIncomingProcessors()->hasProcessor($processorName)) {
            throw $this->createNotFoundException('Processor does not exist');
        }

        $processor = $webhook->getIncomingProcessors()->getProcessor($processorName);
        $processor->process($request);

        if (!$request->attributes->has('username')) {
            throw $this->createNotFoundException('Processor(s) did not provide a username');
        }

        if (!$request->attributes->has('action')) {
            throw $this->createNotFoundException('Processor(s) did not provide action');
        }

        $username = $request->attributes->get('username');
        $action = $request->attributes->get('action');

        $personalAchievements = $this->advance($engine, $username, [$action]);

        return new Response('', Response::HTTP_OK, [
            'X-Achievements-Granted-Count' => count($personalAchievements),
        ]);
    }
}
