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
use Component\Entity\PlayerInterface;

/**
 * @Route("/leaderboard")
 */
class LeaderboardController extends Controller
{
    /**
     * **Example Response:**
     * ```json
     * [{
     *     "name": "Toney Beatty",
     *     "username": "gschowalter",
     *     "points": 200,
     *     "links": {
     *         "self": "http://example.org/api/players/gschowalter/",
     *         "personal_achievements": "http://example.org/api/players/gschowalter/personal-achievements/",
     *         "personal_actions": "http://example.org/api/players/gschowalter/personal-actions/"
     *     }
     * },{
     *     "name": "Carmen Davis",
     *     "username": "cdavis",
     *     "points": 125,
     *     "links": {
     *         "self": "http://example.org/api/players/cdavis",
     *         "personal_achievements": "http://example.org/api/players/cdavis/personal-achievements/",
     *         "personal_actions": "http://example.org/api/players/cdavis/personal-actions/"
     *     }
     * }]
     * ```.
     *
     * @Method("GET")
     * @Route(
     *     "/",
     *     name="api_leaderboard_index"
     * )
     * @ApiDoc(
     *     section="Misc",
     *     resource=true,
     *     description="Returns a sorted collection of all Players that have more than 0 points, starting with the highest score.",
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
        $criteria = $handler->createCriteria($request);
        if (!$criteria->getOrderings()) {
            $criteria->orderBy(['score' => 'DESC']);
        }

        $players = $engine->findPlayerBy()
            ->matching($criteria)
            ->filter(function (PlayerInterface $player) {
                return $player->getScore() > 0;
            })
            ->toArray();

        return $serializer->createResponse(
            $players,
            ['leaderboard.index']
        );
    }
}
