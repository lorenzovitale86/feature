<?php

namespace TestPlugin\SearchBundleDBAL\Facet;

use Shopware\Bundle\SearchBundle\Criteria;
use Shopware\Bundle\SearchBundle\FacetInterface;
use Shopware\Bundle\SearchBundle\FacetResult\BooleanFacetResult;
use Shopware\Bundle\SearchBundleDBAL\FacetHandlerInterface;
use Shopware\Bundle\SearchBundleDBAL\QueryBuilderFactory;
use Shopware\Bundle\StoreFrontBundle\Struct;

class GadgetFacetHandler implements FacetHandlerInterface
{
    /**
     * @var QueryBuilderFactory
     */
    private $queryBuilderFactory;

    /**
     * @var \Shopware_Components_Snippet_Manager
     */
    private $snippetManager;

    /**
     * @param QueryBuilderFactory $queryBuilderFactory
     * @param \Shopware_Components_Snippet_Manager $snippetManager
     */
    public function __construct(
        QueryBuilderFactory $queryBuilderFactory,
        \Shopware_Components_Snippet_Manager $snippetManager
    ) {
        $this->queryBuilderFactory = $queryBuilderFactory;
        $this->snippetManager = $snippetManager;
    }

    /**
     * @inheritdoc
     */
    public function supportsFacet(FacetInterface $facet)
    {
        return ($facet instanceof GadgetFacet);
    }


    /**
     * @inheritdoc
     */
    public function generateFacet(
        FacetInterface $facet,
        Criteria $criteria,
        Struct\ShopContextInterface $context
    ) {
        //resets all conditions of the criteria to execute a facet query without user filters.
        $queryCriteria = clone $criteria;
        var_dump($criteria->getFacets());
        $queryCriteria->resetConditions();
        $queryCriteria->resetSorting();

        $query = $this->queryBuilderFactory->createQuery($queryCriteria, $context);
        $query->select('DISTINCT product.id')->where('productAttribute .is_gadget != 1 OR productAttribute .is_gadget IS NULL');


        /**@var $statement \Doctrine\DBAL\Driver\ResultStatement */
        $statement = $query->execute();

        $total = $statement->fetchAll(\PDO::FETCH_COLUMN);

        //found some gadget products?
        if ($total <= 0) {
            return null;
        }

        return new BooleanFacetResult(
            $facet->getName(),
            'is_gadget',
            $criteria->hasCondition($facet->getName()),
            '',
            '1'

        );;
    }
}
