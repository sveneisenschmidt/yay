<?php

namespace App\Api\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Api\Request\CriteriaHandler;
use App\Api\Response\ResponseSerializer;
use Component\Engine\Engine;

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
     *     "label": "Label of Level 1",
     *     "description": "Description of Level 1",
     *     "level": 1,
     *     "points": 100
     * },{
     *     "name": "Level 2",
     *     "label": "Label of Level 2",
     *     "description": "Description of Level 2",
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
     *     },
     *     filters={
     *         {"name"="limit", "dataType"="int", "pattern"="0-9"},
     *         {"name"="offset", "dataType"="int", "pattern"="0-9"},
     *         {"name"="order[$field]", "dataType"="string", "pattern"="ASC|DESC"},
     *         {"name"="filter[$field]", "dataType"="string"},
     *         {"name"="filter[$field:eq]", "dataType"="string"},
     *         {"name"="filter[$field:neq]", "dataType"="string"},
     *         {"name"="filter[$field:gt]", "dataType"="string"},
     *         {"name"="filter[$field:lt]", "dataType"="string"},
     *         {"name"="filter[$field:gte]", "dataType"="string"},
     *         {"name"="filter[$field:lte]", "dataType"="string"},
     *     }
     * )
     */
    public function indexAction(
        Request $request,
        Engine $engine,
        CriteriaHandler $handler,
        ResponseSerializer $serializer
    ): Response {
        $levels = $engine->findLevelAny()
            ->matching($handler->createCriteria($request))
            ->getValues();

        return $serializer->createResponse(
            $levels,
            ['level.index']
        );
    }
}
