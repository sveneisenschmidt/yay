<?php

namespace Component\HttpFoundation\Request\ParamConverter;

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\DeserializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class DeserializeFieldConverter implements ParamConverterInterface
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * DeserializeFieldConverter constructor.
    */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     */
    public function supports(ParamConverter $configuration): bool
    {
        return 'DeserializeField' === $configuration->getConverter();
    }

    /**
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $options = $configuration->getOptions();
        $target = $configuration->getName();

        if (!isset($options['type'])) {
            return;
        }

        if (!isset($options['group'])) {
            return;
        }

        $entity = $this->serializer->deserialize(
            $request->getContent(false),
            $options['type'],
            'json',
            DeserializationContext::create()->setGroups($options['group'])
        );

        $request->attributes->set($target, $entity);
    }
}
