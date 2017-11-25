<?php

namespace App\Api\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
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
     *         "player": "jane.doe",
     *         "achieved_at": "2017-11-25T21:06:57+00:00"
     *     },
     *     "created_at": "2017-11-25T21:06:57+00:00",
     *     "links": {
     *         "self": "http://localhost:50080/api/activities/",
     *         "player": "http://localhost:50080/api/players/jane.doe/",
     *         "action": "http://localhost:50080/api/actions/demo-action/"
     *     }
     * },
     * {
     *     "name": "personal_achievement_granted",
     *     "data": {
     *         "action": "demo-achievement-01",
     *         "player": "jane.doe",
     *         "achieved_at": "2017-11-25T21:06:57+00:00"
     *     },
     *     "created_at": "2017-11-25T21:06:57+00:00",
     *     "links": {
     *         "self": "http://localhost:50080/api/activities/",
     *         "player": "http://localhost:50080/api/players/jane.doe/",
     *         "achievement": "http://localhost:50080/api/achievements/demo-achievement-01/"
     *     }
     * }]
     * ```.
     *
     * @Method("GET")
     *
     * @Route(
     *     "/",
     *     name="api_activity_index"
     * )
     *
     * @ApiDoc(
     *     section="Activities",
     *     resource=true,
     *     description="Returns a collection of activities",
     *     statusCodes = {
     *         200 = "Returned when successful"
     *     }
     * )
     */
    public function indexAction(
        Engine $engine,
        ResponseSerializer $serializer
    ): Response {
        $activities = $engine
            ->findActivityAny()
            ->toArray();

        \usort($activities, function(Activity $a, Activity $b) {
            return $a->getCreatedAt() > $b->getCreatedAt() ? -1 : 1;
        });

        return $serializer->createResponse(
            $activities,
            ['activity.index']
        );
    }
}
