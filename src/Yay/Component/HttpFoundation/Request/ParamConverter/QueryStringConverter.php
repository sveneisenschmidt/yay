<?php

namespace Yay\Component\HttpFoundation\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class QueryStringConverter implements ParamConverterInterface
{
    /**
     * @param ParamConverter $configuration
     *
     * @return bool
     */
    public function supports(ParamConverter $configuration): bool
    {
        return 'QueryString' === $configuration->getConverter();
    }

    /**
     * @param Request        $request
     * @param ParamConverter $configuration
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $source = $configuration->getName();
        $target = $source;

        if (!$request->query->has($source)) {
            return;
        }

        $value = $request->query->get($source);
        $request->attributes->set($target, $value);
    }
}
