<?php
namespace Yay\Component\HttpFoundation\Request\ParamConverter;

use \Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use \Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use \Symfony\Component\HttpFoundation\Request;


class HeaderFieldConverter implements ParamConverterInterface
{
    /**
     * @param ParamConverter $configuration
     *
     * @return bool
     */
    public function supports(ParamConverter $configuration)
    {
        return $configuration->getConverter() === 'HeaderField';
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $options = $configuration->getOptions();
        $target = $configuration->getName();
        $source = isset($options['field']) ? $options['field'] : $target;

        if (!$request->headers->has($source)) {
            return false;
        }

        $value = $request->headers->get($source);
        $request->attributes->set($target, $value);
    }
}