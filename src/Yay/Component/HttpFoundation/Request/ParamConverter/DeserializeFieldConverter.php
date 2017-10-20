<?php

namespace Yay\Component\HttpFoundation\Request\ParamConverter;

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
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param ParamConverter $configuration
     *
     * @return bool
     */
    public function supports(ParamConverter $configuration)
    {
        return $configuration->getConverter() === 'DeserializeField';
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $options = $configuration->getOptions();
        $target = $configuration->getName();

        if (!isset($options['type'])) {
            return false;
        }

        if (!isset($options['group'])) {
            return false;
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
