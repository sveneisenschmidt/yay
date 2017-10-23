<?php

namespace Yay\Bundle\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Yay\Bundle\ApiBundle\Response\ResponseSerializer;
use Yay\Component\Engine\Engine;

/**
 * @Route("/levels")
 */
class LevelController extends Controller
{
    /**
     * **Example Response:**
     * ```json
     * [{
     *     "name": "Level 1",
     *     "label": "label-001",
     *     "description": "description-001",
     *     "level": 1,
     *     "points": 100
     * },{
     *     "name": "Level 2",
     *     "label": "label-002",
     *     "description": "description-002",
     *     "level": 2,
     *     "points": 200
     * }]
     * ```.
     *
     * @Method("GET")
     * @Route(
     *     "/",
     *     name="api_level_index"
     * )
     * @ApiDoc(
     *     section="Levels",
     *     resource=true,
     *     description="Returns a collection of all known Levels",
     *     statusCodes = {
     *         200 = "Returned when successful"
     *     }
     * )
     */
    public function indexAction(
        Engine $engine,
        ResponseSerializer $serializer
    ): Response {
        return $serializer->createResponse(
            $engine->findLevelAny()->toArray(),
            ['level.index']
        );
    }
}
