<?php

namespace TestPlugin\SearchBundleDBAL\Facet;

use Shopware\Bundle\SearchBundle\FacetInterface;

class GadgetFacet implements FacetInterface
{
    /**
     * @inheritdoc
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
