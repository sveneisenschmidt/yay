<?php

namespace Component\HttpFoundation\Tests\Request\ParamConverter\Fixture;

class DeserializeFieldConverterFixture
{
    /**
     * @JMS\Serializer\Annotation\Type("string")
     * @JMS\Serializer\Annotation\Groups({"test1"})
     */
    public $bar1 = '';

    /**
     * @JMS\Serializer\Annotation\Type("string")
     * @JMS\Serializer\Annotation\Groups({"test2"})
     */
    public $bar2 = '';
}
