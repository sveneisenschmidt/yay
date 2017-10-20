<?php

namespace Yay\Component\HttpFoundation\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class JsonFieldConverter implements ParamConverterInterface
{
    /**
     * @param ParamConverter $configuration
     *
     * @return bool
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getConverter() === 'JsonField';
    }

    /**
     * @param Request        $request
     * @param ParamConverter $configuration
     */
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
