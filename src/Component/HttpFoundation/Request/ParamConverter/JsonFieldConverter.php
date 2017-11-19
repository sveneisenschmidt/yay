<?php

namespace Component\HttpFoundation\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class JsonFieldConverter implements ParamConverterInterface
{
    public function supports(ParamConverter $configuration): bool
    {
        return 'JsonField' === $configuration->getConverter();
    }

    public function apply(Request $request, ParamConverter $configuration): void
    {
        $options = $configuration->getOptions();
        $target = $configuration->getName();
        $source = isset($options['field']) ? $options['field'] : $target;

        $content = $request->getContent(false);
        $data = json_decode($content, true, 512, JSON_OBJECT_AS_ARRAY);

        if ($data && isset($data[$source])) {
            $request->attributes->set($target, $data[$source]);
        }
    }
}
