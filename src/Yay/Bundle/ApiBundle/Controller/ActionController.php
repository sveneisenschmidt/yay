<?php

namespace Yay\Bundle\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Yay\Component\Entity\Collection;

/**
 * @Route("/actions")
 */
class ActionController extends ApiController
{
    /**
     * **Example Response:**
     * ```json
     * [{
     *     "name": "yay.action.demo_action",
     *     "links": {
     *         "self": "http://example.org/api/actions/yay.action.demo_action",
     *     }
     * }]
     * ```
     *
     * @Method("GET")
     *
     * @Route(
     *     "/",
     *     name="action_index"
     * )
     *
     * @ApiDoc(
     *     section="Actions",
     *     resource=true,
     *     description="Returns a collection of all known Actions",
     *     statusCodes = {
     *         200 = "Returned when successful"
     *     }
     * )
     */
    public function indexAction(): Response
    {
        return $this->respond(
            $this->getEngine()->findActionDefinitionAny()->toArray(),
            ['action.index']
        );
    }

    /**
     * **Example Response:**
     * ```json
     * {
     *     "name": "yay.action.demo_action",
     *     "links": {
     *         "self": "http://example.org/api/actions/yay.action.demo_action",
     *     }
     * }
     * ```
     *
     * @Method("GET")
     *
     * @Route(
     *     "/{name}",
     *     name="action_show",
     *     requirements={"name" = "[A-Za-z0-9\-\_\.]+"}
     * )
     *
     * @ApiDoc(
     *     section="Actions",
     *     resource=true,
     *     description="Returns an Action identified by its name property",
     *     requirements={
     *         {
     *             "name"="name",
     *             "dataType"="string",
     *             "requirement"="[A-Za-z0-9\-\_\.]+"
     *         }
     *     },
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when the action is not found"
     *     }
     * )
     */
    public function showAction(string $name): Response
    {
        $achievementDefinitions = $this->getEngine()->findActionDefinitionBy(['name' => $name]);
        if ($achievementDefinitions->isEmpty()) {
            throw $this->createNotFoundException();
        }

        return $this->respond($achievementDefinitions->first(), ['action.show']);
    }
}
