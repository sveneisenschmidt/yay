<?php

namespace Yay\Bundle\WebhookBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Yay\Component\Webhook\Webhook;

/**
 * @Route("/incoming")
 */
class IncomingController extends Controller
{
    /**
     * @Method("POST")
     * @Route(
     *     "/{processorName}/",
     *     name="webhook_incoming_submit_post"
     * )
     */
    public function submitPostAction(
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

        if (!$request->attributes->has('player')) {
            throw $this->createNotFoundException('Processor does not provide player');
        }

        if (!$request->attributes->has('actions')) {
            throw $this->createNotFoundException('Processor does not provide actions');
        }

        return $this->redirectToRoute('api_progress_submit_get', [
            'player' => $request->attributes->get('player'),
            'actions' => $request->attributes->get('actions'),
        ], 303);
    }
}
