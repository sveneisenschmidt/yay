<?php

namespace App\Integration\Controller;

use Component\Engine\AchievementValidatorInterface;
use Component\Entity\Achievement\AchievementDefinitionInterface;
use Component\Entity\Achievement\ActionDefinitionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Component\Engine\Engine;
use Fhaculty\Graph\Graph;
use Graphp\GraphViz\GraphViz;

/**
 * @Route("/graph")
 */
class GraphController extends AbstractController
{
    /**
     * @Route(
     *     "/",
     *     name="integration_graph_index",
     *     methods={"GET"}
     * )
     */
    public function indexAction(Engine $engine): Response
    {
        $graph = new Graph();
        $graph->setAttribute('graphviz.graph.rankdir', 'LR');

        // Actions
        foreach ($engine->findActionDefinitionAny() as $actionDefinition) {
            /** @var ActionDefinitionInterface $actionDefinition */
            $actionDefinitionVertex = $graph->createVertex($actionDefinition->getName());
            $actionDefinitionVertex->setAttribute('graphviz.shape', 'Mrecord');
            $actionDefinitionVertex->setAttribute('graphviz.label', GraphViz::raw(sprintf(
                '"Action | %s"',
                $actionDefinition->getLabel()
            )));
        }

        // Achievements
        foreach ($engine->findAchievementDefinitionAny() as $achievementDefinition) {
            /** @var AchievementDefinitionInterface $achievementDefinition */
            $achievementDefinitionVertex = $graph->createVertex($achievementDefinition->getName());
            $achievementDefinitionVertex->setAttribute('graphviz.shape', 'Mrecord');
            $achievementDefinitionVertex->setAttribute('graphviz.label', GraphViz::raw(sprintf(
                '"Achievement | %s"',
                $achievementDefinition->getLabel()
            )));

            foreach ($achievementDefinition->getActionDefinitions() as $actionDefinition) {
                /** @var ActionDefinitionInterface $actionDefinition */
                $actionDefinitionVertex = $graph->getVertex($actionDefinition->getName());
                $actionDefinitionVertex->createEdgeTo($achievementDefinitionVertex);
            }
        }

        // Validators
        foreach ($engine->getAchievementValidators() as $achievementValidator) {
            /** @var AchievementValidatorInterface $achievementValidator */

            foreach ($engine->findAchievementDefinitionAny() as $achievementDefinition) {
                /** @var AchievementDefinitionInterface $achievementDefinition */
                if ($achievementValidator->supports($achievementDefinition)) {
                    $achievementValidatorVertex = $graph->createVertex(sprintf(
                        'validator.%s.%s',
                        spl_object_id($achievementDefinition),
                        $achievementDefinition->getLabel()
                    ));
                    $achievementValidatorVertex->setAttribute('graphviz.shape', 'Mrecord');
                    $achievementValidatorVertex->setAttribute('graphviz.label', GraphViz::raw(sprintf(
                        '"Validator | For: %s"',
                        $achievementDefinition->getLabel()
                    )));


                    $achievementDefinitionVertex = $graph->getVertex($achievementDefinition->getName());
                    $achievementDefinitionVertex->createEdgeTo($achievementValidatorVertex);
                    $achievementDefinitionVertex->setAttribute('graphviz.tailport', 'middle');
                    $achievementDefinitionVertex->setAttribute('graphviz.headport', 'right');
                }
            }
        }

        $graphviz = new GraphViz();
        $html = $graphviz->createImageHtml($graph);

        return new Response($html);
    }
}
