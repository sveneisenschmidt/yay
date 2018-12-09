<?php

namespace App\Api\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Api\Request\CriteriaHandler;
use App\Api\Response\ResponseSerializer;
use App\Api\Validator\EntityValidator;
use Component\Engine\Engine;
use Component\Entity\PlayerInterface;

/**
 * @Route("/players")
 */
class PlayerController extends AbstractController
{
    /**
     * **Example Response:**
     * ```json
     * [{
     *     "name": "Alex Doe",
     *     "username": "alex.doe",
     *     "links": {
     *         "self": "https://example.org/api/players/alex.doe/",
     *         "personal_achievements": "https://example.org/api/players/alex.doe/personal-achievements/",
     *         "personal_actions": "https://example.org/api/players/alex.doe/personal-actions/"
     *     }
     * }]
     * ```.
     *
     * @Route(
     *     "/",
     *     name="api_player_index",
     *     methods={"GET"}
     * )
     * @ApiDoc(
     *     section="Players",
     *     resource=true,
     *     description="Returns a collection of all known Players",
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
        $players = $engine->findPlayerAny()
            ->matching($handler->createCriteria($request))
            ->getValues();

        return $serializer->createResponse(
            $players,
            ['player.index']
        );
    }

    /**
     * **Example Response:**
     * ```json
     * {
     *     "name": "Alex Doe",
     *     "username": "alex.doe",
     *     "score": 0,
     *     "level": 0,
     *     "links": {
     *         "self": "https://example.org/api/players/alex.doe/",
     *         "personal_achievements": "https://example.org/api/players/alex.doe/personal-achievements/",
     *         "personal_actions": "https://example.org/api/players/alex.doe/personal-actions/"
     *     }
     * }
     * ```.
     *
     * @Route(
     *     "/{username}/",
     *     name="api_player_show",
     *     requirements={"username" = "[A-Za-z0-9\-\_\.]+"},
     *     methods={"GET"}
     * )
     *
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
    public function showAction(
        Engine $engine,
        ResponseSerializer $serializer,
        string $username
    ): Response {
        $players = $engine->findPlayerBy(['username' => $username]);
        if ($players->isEmpty()) {
            throw $this->createNotFoundException();
        }

        return $serializer->createResponse(
            $players->first(),
            ['player.show']
        );
    }

    /**
     * **Example Request:**
     * ```json
     * {
     *     "name": "Billy Turner V",
     *     "username": "marianne58",
     *     "email": "marianne58@gmail.com",
     *     "image_url": "https://avatars.dicebear.com/v2/female/497.svg"
     * }
     * ```.
     *
     * **Example Response:**
     * ```json
     * {
     *     "name": "Billy Turner V",
     *     "username": "marianne58",
     *     "image_url": "https://avatars.dicebear.com/v2/female/497.svg",
     *     "score": 0,
     *     "level": 0,
     *     "links": {
     *         "self": "https://example.org/api/players/marianne58",
     *         "personal_achievements": "https://example.org/api/players/marianne58/personal-achievements/",
     *         "personal_actions": "https://example.org/api/players/marianne58/personal-actions/"
     *     }
     * }
     * ```
     *
     * @Route(
     *     "/",
     *     name="api_player_create",
     *     methods={"POST"}
     * )
     *
     * @ApiDoc(
     *     section="Players",
     *     resource=true,
     *     description="Creates a new player",
     *     statusCodes = {
     *         201 = "Returned when successful",
     *         422 = "Validation failed"
     *     }
     * )
     *
     * @ParamConverter(
     *     name="player",
     *     options={"type"="Component\Entity\Player", "group"="player.create"},
     *     converter="DeserializeField"
     * )
     */
    public function createAction(
        Engine $engine,
        EntityValidator $validator,
        ResponseSerializer $serializer,
        UrlGeneratorInterface $urlGenerator,
        PlayerInterface $player
    ): Response {
        $violations = $validator->validate($player);
        if ($violations->count() > 0) {
            return $serializer->createResponse(
                $violations,
                ['validation'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $player->setCreatedAt(new \DateTime());
        $engine->savePlayer($player);

        return $serializer->createResponse(
            $player,
            ['player.show'],
            Response::HTTP_CREATED,
            ['Location' => $urlGenerator->generate('api_player_show', ['username' => $player->getUsername()])]
        );
    }

    /**
     * **Example Response:**
     * ```json
     * [{
     *     "name": "demo-achievement-01",
     *     "achieved_at": "2017-04-07T14:12:29+0000",
     *     "links": {
     *         "self": "https://example.org/api/players/alex.doe/personal-achievements/",
     *         "player": "https://example.org/api/players/alex.doe/",
     *         "achievement": "https://example.org/api/achievements/demo-achievement-01/"
     *     }
     * }, {
     *     "name": "demo-achievement-02",
     *     "achieved_at": "2017-04-07T14:12:29+0000",
     *     "links": {
     *         "self": "https://example.org/api/players/alex.doe/personal-achievements/",
     *         "player": "https://example.org/api/players/alex.doe/",
     *         "achievement": "https://example.org/api/achievements/demo-achievement-02/"
     *     }
     * }]
     * ```.
     *
     * @Route(
     *     "/{username}/personal-achievements/",
     *     name="api_player_personal_achievements_show",
     *     requirements={"username" = "[A-Za-z0-9\-\_\.]+"},
     *     methods={"GET"}
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
    public function indexPersonalAchievementsAction(
        Request $request,
        Engine $engine,
        CriteriaHandler $handler,
        ResponseSerializer $serializer,
        string $username
    ): Response {
        $players = $engine->findPlayerBy(['username' => $username]);
        if ($players->isEmpty()) {
            throw $this->createNotFoundException();
        }

        $achievements = $players->first()
            ->getPersonalAchievements()
            ->matching($handler->createCriteria($request))
            ->getValues();

        return $serializer->createResponse(
            $achievements,
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
     *         "self": "https://example.org/api/players/alex.doe/personal-actions/",
     *         "player": "https://example.org/api/players/alex.doe/",
     *         "action": "https://example.org/api/actions/yay.action.demo_action/"
     *     }
     * }, {
     *     "name": "yay.action.demo_action",
     *     "achieved_at": "2017-04-07T14:12:29+0000",
     *     "links": {
     *         "self": "https://example.org/api/players/alex.doe/personal-actions/",
     *         "player": "https://example.org/api/players/alex.doe/",
     *         "action": "https://example.org/api/actions/yay.action.demo_action/"
     *     }
     * }]
     * ```.
     *
     * @Route(
     *     "/{username}/personal-actions/",
     *     name="api_player_personal_actions_show",
     *     requirements={"username" = "[A-Za-z0-9\-\_\.]+"},
     *     methods={"GET"}
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
    public function indexPersonalActionsAction(
        Request $request,
        Engine $engine,
        CriteriaHandler $handler,
        ResponseSerializer $serializer,
        string $username
    ): Response {
        $players = $engine->findPlayerBy(['username' => $username]);
        if ($players->isEmpty()) {
            throw $this->createNotFoundException();
        }

        $actions = $players->first()
            ->getPersonalActions()
            ->matching($handler->createCriteria($request))
            ->getValues();

        return $serializer->createResponse(
            $actions,
            ['player.personal_actions.show']
        );
    }

    /**
     * **Example Response:**
     * ```json
     * 	[{
     * 		"name": "personal_action_granted",
     * 		"data": {
     * 			"action": "demo-action",
     * 			"player": "alex.doe",
     * 			"achieved_at": "2017-11-26T14:36:17+00:00"
     * 		},
     * 		"created_at": "2017-11-26T14:36:17+00:00",
     * 		"links": {
     * 			"self": "http://localhost:50080/api/activities/",
     * 			"player": "http://localhost:50080/api/players/alex.doe/",
     * 			"action": "http://localhost:50080/api/actions/demo-action/"
     * 		}
     * 	}, {
     * 		"name": "personal_action_granted",
     * 		"data": {
     * 			"action": "demo-action",
     * 			"player": "alex.doe",
     * 			"achieved_at": "2017-11-26T14:36:18+00:00"
     * 		},
     * 		"created_at": "2017-11-26T14:36:18+00:00",
     * 		"links": {
     * 			"self": "http://localhost:50080/api/activities/",
     * 			"player": "http://localhost:50080/api/players/alex.doe/",
     * 			"action": "http://localhost:50080/api/actions/demo-action/"
     * 		}
     * 	}]
     * ```.
     *
     * @Route(
     *     "/{username}/personal-activities/",
     *     name="api_player_personal_activities_show",
     *     requirements={"username" = "[A-Za-z0-9\-\_\.]+"},
     *     methods={"GET"}
     * )
     *
     * @ApiDoc(
     *     section="Players",
     *     resource=true,
     *     description="Returns a Player activities identified by its username property",
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
    public function indexPersonalActivitiesAction(
        Request $request,
        Engine $engine,
        CriteriaHandler $handler,
        ResponseSerializer $serializer,
        string $username
    ): Response {
        $players = $engine->findPlayerBy(['username' => $username]);
        if ($players->isEmpty()) {
            throw $this->createNotFoundException();
        }

        $activities = $players->first()
            ->getActivities()
            ->matching($handler->createCriteria($request))
            ->getValues();

        return $serializer->createResponse(
            $activities,
            ['player.personal_activities.show']
        );
    }

    /**
     * **Example Response:**
     * ```json
     * 	[{
     *     "name": "demo-achievement-01",
     *     "label": "demo-achievement-02",
     *     "description": "demo-achievement-02",
     *     "progress": 20,
     *     "points": 50,
     *     "imageUrl": "https://exmaple.org/example.png"
     *     "links": {
     *         "self": "http://localhost:50080/api/players/alex.doe/transient-actions/",
     *         "player": "http://localhost:50080/api/players/alex.doe/",
     *         "achievement": "http://localhost:50080/api/achievements/demo-achievement-01/"
     *      }
     *  }]
     * ```.
     *
     * @Route(
     *     "/{username}/transient-achievements/",
     *     name="api_player_personal_transient_achievements_show",
     *     requirements={"username" = "[A-Za-z0-9\-\_\.]+"},
     *     methods={"GET"}
     * )
     *
     * @ApiDoc(
     *     section="Players",
     *     resource=true,
     *     description="Returns a Player's progress identified by its username property",
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
    public function indexTransientAchievementsAction(
        Engine $engine,
        ResponseSerializer $serializer,
        string $username
    ): Response {
        $players = $engine->findPlayerBy(['username' => $username]);
        if ($players->isEmpty()) {
            throw $this->createNotFoundException();
        }

        $transientAchievements = $engine->calculate($players->first());

        return $serializer->createResponse(
            array_unique($transientAchievements),
            ['player.transient_achievements.show']
        );
    }
}
