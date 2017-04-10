<?php

namespace Yay\Bundle\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Yay\Component\Entity\PlayerInterface;

/**
 * @Route("/players")
 */
class PlayerController extends ApiController
{
    /**
     * **Example Response:**
     * ```json
     * [{
     *     "name": "Toney Beatty",
     *     "username": "gschowalter",
     *     "links": {
     *         "self": "http://example.org/api/players/gschowalter",
     *         "personal_achievements": "http://example.org/api/players/gschowalter/personal-achievements",
     *         "personal_actions": "http://example.org/api/players/gschowalter/personal-actions"
     *     }
     * }]
     * ```
     *
     * @Method("GET")
     * @Route(
     *     "/",
     *     name="player_index"
     * )
     * @ApiDoc(
     *     section="Players",
     *     resource=true,
     *     description="Returns a collection of all known Players",
     *     statusCodes = {
     *         200 = "Returned when successful"
     *     }
     * )
     */
    public function indexAction(): Response
    {
        return $this->respond(
            $this->getEngine()->findPlayerAny()->toArray(),
            ['player.index']
        );
    }

    /**
     * **Example Response:**
     * ```json
     * {
     *     "name": "Toney Beatty",
     *     "username": "gschowalter",
     *     "links": {
     *         "self": "http://example.org/api/players/gschowalter",
     *         "personal_achievements": "http://example.org/api/players/gschowalter/personal-achievements",
     *         "personal_actions": "http://example.org/api/players/gschowalter/personal-actions"
     *     }
     * }
     * ```
     *
     * @Method("GET")
     * @Route(
     *     "/{username}",
     *     name="player_show",
     *     requirements={"username" = "[A-Za-z0-9\-\_\.]+"}
     * )
     * @ApiDoc(
     *     section="Players",
     *     resource=true,
     *     description="Returns a Player identified by its username property",
     *     requirements={
     *         {
     *             "name"="username",
     *             "dataType"="string",
     *             "requirement"="[A-Za-z0-9\-\_\.]+"
     *         }
     *     },
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when the player is not found"
     *     }
     * )
     */
    public function showAction(string $username): Response
    {
        $players = $this->getEngine()->findPlayerBy(['username' => $username]);
        if ($players->isEmpty()) {
            throw $this->createNotFoundException();
        }

        return $this->respond(
            $players->first(),
            ['player.show']
        );
    }

    /**
     * **Example Response:**
     * ```json
     * [{
     *     "name": "yay.achievement.demo_achievement_1",
     *     "achieved_at": "2017-04-07T14:12:29+0000",
     *     "links": {
     *         "self": "http://example.org/api/players/gschowalter/personal-achievements",
     *         "player": "http://example.org/api/players/gschowalter",
     *         "achievement": "http://example.org/api/achievements/yay.achievement.demo_achievement_1"
     *     }
     * }, {
     *     "name": "yay.achievement.demo_achievement_2",
     *     "achieved_at": "2017-04-07T14:12:29+0000",
     *     "links": {
     *         "self": "http://example.org/api/players/gschowalter/personal-achievements",
     *         "player": "http://example.org/api/players/gschowalter",
     *         "achievement": "http://example.org/api/achievements/yay.achievement.demo_achievement_2"
     *     }
     * }]
     * ```
     *
     * @Method("GET")
     * @Route(
     *     "/{username}/personal-achievements",
     *     name="player_personal_achievements_show",
     *     requirements={"username" = "[A-Za-z0-9\-\_\.]+"}
     * )
     * @ApiDoc(
     *     section="Players",
     *     resource=true,
     *     description="Returns a Player achievements identified by its username property",
     *     requirements={
     *         {
     *             "name"="username",
     *             "dataType"="string",
     *             "requirement"="[A-Za-z0-9\-\_\.]+"
     *         }
     *     },
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when the player is not found"
     *     }
     * )
     */
    public function indexPersonalAchievementsAction(string $username)
    {
        $players = $this->getEngine()->findPlayerBy(['username' => $username]);
        if ($players->isEmpty()) {
            throw $this->createNotFoundException();
        }

        /** @var PlayerInterface $player */
        $player = $players->first();

        return $this->respond(
            $player->getPersonalAchievements(),
            ['player.personal_achievements.show']
        );
    }

    /**
     * **Example Response:**
     * ```json
     * [{
     *     "name": "yay.action.demo_action",
     *     "achieved_at": "2017-04-07T14:12:29+0000",
     *     "links": {
     *         "self": "http://example.org/api/players/gschowalter/personal-actions",
     *         "player": "http://example.org/api/players/gschowalter",
     *         "action": "http://example.org/api/actions/yay.action.demo_action"
     *     }
     * }, {
     *     "name": "yay.action.demo_action",
     *     "achieved_at": "2017-04-07T14:12:29+0000",
     *     "links": {
     *         "self": "http://example.org/api/players/gschowalter/personal-actions",
     *         "player": "http://example.org/api/players/gschowalter",
     *         "action": "http://example.org/api/actions/yay.action.demo_action"
     *     }
     * }]
     * ```
     *
     * @Method("GET")
     *
     * @Route(
     *     "/{username}/personal-actions",
     *     name="player_personal_actions_show",
     *     requirements={"username" = "[A-Za-z0-9\-\_\.]+"}
     * )
     *
     * @ApiDoc(
     *     section="Players",
     *     resource=true,
     *     description="Returns a Player achievements identified by its username property",
     *     requirements={
     *         {
     *             "name"="username",
     *             "dataType"="string",
     *             "requirement"="[A-Za-z0-9\-\_\.]+"
     *         }
     *     },
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when the player is not found"
     *     }
     * )
     */
    public function indexPersonalActionsAction(string $username)
    {
        $players = $this->getEngine()->findPlayerBy(['username' => $username]);
        if ($players->isEmpty()) {
            throw $this->createNotFoundException();
        }

        /** @var PlayerInterface $player */
        $player = $players->first();

        return $this->respond(
            $player->getPersonalActions(),
            ['player.personal_actions.show']
        );
    }
}
