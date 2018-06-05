<?php

namespace TestPlugin\SearchBundleDBAL\Condition;

use Shopware\Bundle\SearchBundle\ConditionInterface;

class GadgetCondition implements ConditionInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'test_plugin_gadget';
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
