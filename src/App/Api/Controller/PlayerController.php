<?php

namespace App\Api\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Api\Response\ResponseSerializer;
use App\Api\Validator\EntityValidator;
use Component\Engine\Engine;
use Component\Entity\PlayerInterface;

/**
 * @Route("/players")
 */
class PlayerController extends Controller
{
    /**
     * **Example Response:**
     * ```json
     * [{
     *     "name": "Jane Doe",
     *     "username": "jane.doe",
     *     "links": {
     *         "self": "https://example.org/api/players/jane.doe/",
     *         "personal_achievements": "https://example.org/api/players/jane.doe/personal-achievements/",
     *         "personal_actions": "https://example.org/api/players/jane.doe/personal-actions/"
     *     }
     * }]
     * ```.
     *
     * @Method("GET")
     * @Route(
     *     "/",
     *     name="api_player_index"
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
    public function indexAction(
        Engine $engine,
        ResponseSerializer $serializer
    ): Response {
        return $serializer->createResponse(
            $engine->findPlayerAny()->toArray(),
            ['player.index']
        );
    }

    /**
     * **Example Response:**
     * ```json
     * {
     *     "name": "Jane Doe",
     *     "username": "jane.doe",
     *     "links": {
     *         "self": "https://example.org/api/players/jane.doe/",
     *         "personal_achievements": "https://example.org/api/players/jane.doe/personal-achievements/",
     *         "personal_actions": "https://example.org/api/players/jane.doe/personal-actions/"
     *     }
     * }
     * ```.
     *
     * @Method("GET")
     * @Route(
     *     "/{username}/",
     *     name="api_player_show",
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
     *
     * @param Engine             $engine
     * @param ResponseSerializer $serializer
     * @param string             $username
     *
     * @return Response
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
     *     "image_url": "https://api.adorable.io/avatars/128/497"
     * }
     * ```.
     *
     * **Example Response:**
     * ```json
     * {
     *     "name": "Billy Turner V",
     *     "username": "marianne58",
     *     "image_url": "https://api.adorable.io/avatars/128/497",
     *     "score": 0,
     *     "links": {
     *         "self": "https://example.org/api/players/marianne58",
     *         "personal_achievements": "https://example.org/api/players/marianne58/personal-achievements/",
     *         "personal_actions": "https://example.org/api/players/marianne58/personal-actions/"
     *     }
     * }
     * ```
     *
     * @Method("POST")
     * @Route(
     *     "/",
     *     name="api_player_create"
     * )
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
     *
     * @param Engine                $engine
     * @param EntityValidator       $validator
     * @param ResponseSerializer    $serializer
     * @param UrlGeneratorInterface $urlGenerator
     * @param PlayerInterface       $player
     *
     * @return Response
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
     *         "self": "https://example.org/api/players/jane.doe/personal-achievements/",
     *         "player": "https://example.org/api/players/jane.doe/",
     *         "achievement": "https://example.org/api/achievements/demo-achievement-01/"
     *     }
     * }, {
     *     "name": "demo-achievement-02",
     *     "achieved_at": "2017-04-07T14:12:29+0000",
     *     "links": {
     *         "self": "https://example.org/api/players/jane.doe/personal-achievements/",
     *         "player": "https://example.org/api/players/jane.doe/",
     *         "achievement": "https://example.org/api/achievements/demo-achievement-02/"
     *     }
     * }]
     * ```.
     *
     * @Method("GET")
     * @Route(
     *     "/{username}/personal-achievements/",
     *     name="api_player_personal_achievements_show",
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
     *
     * @param Engine             $engine
     * @param ResponseSerializer $serializer
     * @param string             $username
     *
     * @return Response
     */
    public function indexPersonalAchievementsAction(
        Engine $engine,
        ResponseSerializer $serializer,
        string $username
    ): Response {
        $players = $engine->findPlayerBy(['username' => $username]);
        if ($players->isEmpty()) {
            throw $this->createNotFoundException();
        }

        /** @var PlayerInterface $player */
        $player = $players->first();

        return $serializer->createResponse(
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
     *         "self": "https://example.org/api/players/jane.doe/personal-actions/",
     *         "player": "https://example.org/api/players/jane.doe/",
     *         "action": "https://example.org/api/actions/yay.action.demo_action/"
     *     }
     * }, {
     *     "name": "yay.action.demo_action",
     *     "achieved_at": "2017-04-07T14:12:29+0000",
     *     "links": {
     *         "self": "https://example.org/api/players/jane.doe/personal-actions/",
     *         "player": "https://example.org/api/players/jane.doe/",
     *         "action": "https://example.org/api/actions/yay.action.demo_action/"
     *     }
     * }]
     * ```.
     *
     * @Method("GET")
     *
     * @Route(
     *     "/{username}/personal-actions/",
     *     name="api_player_personal_actions_show",
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
     *
     * @param Engine             $engine
     * @param ResponseSerializer $serializer
     * @param string             $username
     *
     * @return Response
     */
    public function indexPersonalActionsAction(
        Engine $engine,
        ResponseSerializer $serializer,
        string $username
    ): Response {
        $players = $engine->findPlayerBy(['username' => $username]);
        if ($players->isEmpty()) {
            throw $this->createNotFoundException();
        }

        /** @var PlayerInterface $player */
        $player = $players->first();

        return $serializer->createResponse(
            $player->getPersonalActions(),
            ['player.personal_actions.show']
        );
    }
}
