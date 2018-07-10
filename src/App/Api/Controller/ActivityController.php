<?php

namespace App\Api\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Api\Request\CriteriaHandler;
use App\Api\Response\ResponseSerializer;
use Component\Engine\Engine;
use Component\Entity\Collection;
use Component\Entity\Activity;

/**
 * @Route("/activities")
 */
class ActivityController extends Controller
{
    /**
     * **Example Response:**
     * ```json
     * [{
     *     "name": "personal_action_granted",
     *     "data": {
     *         "action": "demo-action",
     *         "player": "alex.doe",
     *         "achieved_at": "2017-11-25T21:06:57+00:00"
     *     },
     *     "created_at": "2017-11-25T21:06:57+00:00",
     *     "links": {
     *         "self": "http://localhost:50080/api/activities/",
     *         "player": "http://localhost:50080/api/players/alex.doe/",
     *         "action": "http://localhost:50080/api/actions/demo-action/"
     *     }
     * },
     * {
     *     "name": "personal_achievement_granted",
     *     "data": {
     *         "action": "demo-achievement-01",
     *         "player": "alex.doe",
     *         "achieved_at": "2017-11-25T21:06:57+00:00"
     *     },
     *     "created_at": "2017-11-25T21:06:57+00:00",
     *     "links": {
     *         "self": "http://localhost:50080/api/activities/",
     *         "player": "http://localhost:50080/api/players/alex.doe/",
     *         "achievement": "http://localhost:50080/api/achievements/demo-achievement-01/"
     *     }
     * }]
     * ```.
     *
     * @Route(
     *     "/",
     *     name="api_activity_index",
     *     methods={"GET"}
     * )
     *
     * @ApiDoc(
     *     section="Activities",
     *     resource=true,
     *     description="Returns a collection of activities",
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
        $activities = $engine->findActivityAny()
            ->matching($handler->createCriteria($request))
            ->getValues();

        return $serializer->createResponse(
            $activities,
            ['activity.index']
        );
    }
}
