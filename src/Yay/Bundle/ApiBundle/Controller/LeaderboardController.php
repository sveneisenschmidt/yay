<?php

namespace Yay\Bundle\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Yay\Bundle\ApiBundle\Response\ResponseSerializer;
use Yay\Component\Engine\Engine;
use Yay\Component\Entity\PlayerInterface;

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
     *         "self": "http://example.org/api/players/gschowalter",
     *         "personal_achievements": "http://example.org/api/players/gschowalter/personal-achievements",
     *         "personal_actions": "http://example.org/api/players/gschowalter/personal-actions"
     *     }
     * },{
     *     "name": "Carmen Davis",
     *     "username": "cdavis",
     *     "points": 125,
     *     "links": {
     *         "self": "http://example.org/api/players/cdavis",
     *         "personal_achievements": "http://example.org/api/players/cdavis/personal-achievements",
     *         "personal_actions": "http://example.org/api/players/cdavis/personal-actions"
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
     *     }
     * )
     *
     * @param Engine             $engine
     * @param ResponseSerializer $serializer
     *
     * @return Response
     */
    public function indexAction(
        Engine $engine,
        ResponseSerializer $serializer
    ): Response {
        $players = $engine
            ->findPlayerAny()
            ->filter(function (PlayerInterface $player) {
                return $player->getScore() > 0;
            })
            ->toArray();

        usort($players, function (PlayerInterface $player1, PlayerInterface $player2) {
            return $player1->getScore() > $player2->getScore() ? -1 : 1;
        });

        return $serializer->createResponse(
            $players,
            ['leaderboard.index']
        );
    }
}
