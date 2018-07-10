<?php

namespace App\Api\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Api\Response\ResponseSerializer;
use App\Engine\Controller\EngineControllerTrait;
use Component\Engine\Engine;
use Component\Entity\Player;

/**
 * @Route("/progress")
 */
class ProgressController extends Controller
{
    use EngineControllerTrait;

    /**
     * **Example Request (1):**
     * ```query
     * username=alex.doe&action=yay.action.demo_action
     * ```.
     *
     * **Example Request (2):**
     * ```query
     * username=alex.doe&actions[]=yay.action.demo_action&actions[]=yay.action.demo_action&actions[]=yay.action.demo_action&actions[]=yay.action.demo_action
     * ```
     *
     * **Example Response:**
     * ```json
     * [{
     *     "name": "demo-achievement-01",
     *     "achieved_at": "2017-04-07T14:12:29+0000",
     *     "links": {
     *         "self": "http://example.org/api/players/gschowalter/personal-achievements/",
     *         "player": "http://example.org/api/players/gschowalter/",
     *         "achievement": "http://example.org/api/achievements/demo-achievement-01/"
     *     }
     * }, {
     *     "name": "demo-achievement-02",
     *     "achieved_at": "2017-04-07T14:12:29+0000",
     *     "links": {
     *         "self": "http://example.org/api/players/gschowalter/personal-achievements/",
     *         "player": "http://example.org/api/players/gschowalter/",
     *         "achievement": "http://example.org/api/achievements/demo-achievement-02/"
     *     }
     * }]
     * ```
     *
     * @Route(
     *     "/",
     *     name="api_progress_submit_get",
     *     methods={"GET"}
     * )
     * 
     * @ApiDoc(
     *     section="Progress of an Player",
     *     resource=true,
     *     description="Submit a payload to update a users progress",
     *     requirements={
     *         {
     *             "name"="player",
     *             "dataType"="string",
     *             "requirement"="[a-z\.\-\_]+",
     *             "description"="Username of the Player to progress"
     *         },
     *         {
     *             "name"="action",
     *             "dataType"="string",
     *             "requirement"="[a-z\.\-\_]+",
     *             "description"="Action that the Player has made progress in"
     *         },
     *         {
     *             "name"="actions",
     *             "dataType"="array",
     *             "requirement"="Array<[a-z\.\-\_]+>",
     *             "description"="Actions that the Player has made progress in"
     *         }
     *     },
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = {
     *             "Returned when the player is not found",
     *             "Returned when on of the actions does can not found"
     *         }
     *     }
     * )
     *
     * @ParamConverter(
     *     name="username",
     *     converter="QueryString"
     * )
     * @ParamConverter(
     *     name="action",
     *     converter="QueryString"
     * )
     * @ParamConverter(
     *     name="actions",
     *     converter="QueryString"
     * )
     */
    public function submitGetAction(
        Engine $engine,
        ResponseSerializer $serializer,
        string $username,
        string $action = null,
        array $actions = []
    ): Response {
        if (!empty($action)) {
            $actions[] = $action;
        }

        return $serializer->createResponse(
            $this->advance($engine, $username, $actions),
            ['progress.submit']
        );
    }

    /**
     * **Example Request (1):**
     * ```json
     * {
     *     "username": "alex.doe",
     *     "action": "yay.action.demo_action"
     * }
     * ```.
     *
     * **Example Request (2):**
     * ```json
     * {
     *     "username": "alex.doe",
     *     "actions": [
     *         "yay.action.demo_action",
     *         "yay.action.demo_action",
     *         "yay.action.demo_action",
     *         "yay.action.demo_action"
     *     ]
     * }
     * ```
     *
     * **Example Response:**
     * ```json
     * [{
     *     "name": "demo-achievement-01",
     *     "achieved_at": "2017-04-07T14:12:29+0000",
     *     "links": {
     *         "self": "http://example.org/api/players/gschowalter/personal-achievements/",
     *         "player": "http://example.org/api/players/gschowalter/",
     *         "achievement": "http://example.org/api/achievements/demo-achievement-01/"
     *     }
     * }, {
     *     "name": "demo-achievement-02",
     *     "achieved_at": "2017-04-07T14:12:29+0000",
     *     "links": {
     *         "self": "http://example.org/api/players/gschowalter/personal-achievements/",
     *         "player": "http://example.org/api/players/gschowalter/",
     *         "achievement": "http://example.org/api/achievements/demo-achievement-02/"
     *     }
     * }]
     * ```
     * 
     * @Route(
     *     "/",
     *     name="api_progress_submit_post",
     *     methods={"POST"}
     * )
     * @ApiDoc(
     *     section="Progress of an Player",
     *     resource=true,
     *     description="Submit a payload to update a users progress",
     *     requirements={
     *         {
     *             "name"="username",
     *             "dataType"="string",
     *             "requirement"="[a-z\.\-\_]+",
     *             "description"="Username of the Player to progress"
     *         },
     *         {
     *             "name"="action",
     *             "dataType"="string",
     *             "requirement"="[a-z\.\-\_]+",
     *             "description"="Action that the Player has made progress in"
     *         },
     *         {
     *             "name"="actions",
     *             "dataType"="array",
     *             "requirement"="Array<[a-z\.\-\_]+>",
     *             "description"="Actions that the Player has made progress in"
     *         }
     *     },
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = {
     *             "Returned when the player is not found",
     *             "Returned when on of the actions does can not found"
     *         }
     *     }
     * )
     * @ParamConverter(
     *     name="username",
     *     converter="JsonField"
     * )
     * @ParamConverter(
     *     name="action",
     *     converter="JsonField"
     * )
     * @ParamConverter(
     *     name="actions",
     *     converter="JsonField"
     * )
     */
    public function submitPostAction(
        Engine $engine,
        ResponseSerializer $serializer,
        string $username,
        string $action = null,
        array $actions = []
    ): Response {
        if (!empty($action)) {
            $actions[] = $action;
        }

        return $serializer->createResponse(
            $this->advance($engine, $username, $actions),
            ['progress.submit']
        );
    }
}
