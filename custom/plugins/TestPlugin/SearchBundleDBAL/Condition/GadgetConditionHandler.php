<?php

namespace TestPlugin\SearchBundleDBAL\Condition;

use Shopware\Bundle\SearchBundle\ConditionInterface;
use Shopware\Bundle\SearchBundleDBAL\ConditionHandlerInterface;
use Shopware\Bundle\SearchBundleDBAL\QueryBuilder;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;

class GadgetConditionHandler implements ConditionHandlerInterface
{
    const STATE_GADGET_INCLUDED = 'gadget_include';

    /**
     * @inheritdoc
     */
    public function supportsCondition(ConditionInterface $condition)
    {
        return ($condition instanceof GadgetCondition);
    }

    /**
     * @inheritdoc
     */
    public function generateCondition(
        ConditionInterface $condition,
        QueryBuilder $query,
        ShopContextInterface $context
    ) {
        if (!$query->hasState(self::STATE_GADGET_INCLUDED)) {
            $query->where('productAttribute.is_gadget != 1 OR productAttribute.is_gadget IS NULL');

            $query->addState(self::STATE_GADGET_INCLUDED);

        }
    }
}
