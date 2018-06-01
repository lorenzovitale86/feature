<?php
namespace TestPlugin;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Tools\SchemaTool;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware\Components\Plugin\Context\UpdateContext;
use Shopware\Bundle\AttributeBundle\Service\TypeMapping;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use TestPlugin\Models\Player;
use TestPlugin\Models\Team;
use Shopware\Models\Article\Article;

class TestPlugin extends Plugin
{
    /**
     * @param InstallContext $installContext
     */
    public function install(InstallContext $installContext)
    {
        /** @var CrudService $attributeCrudService */
      $attributeCrudService = $this->container->get('shopware_attribute.crud_service');
      $attributeCrudService->update('s_user_attributes','team',TypeMapping::TYPE_SINGLE_SELECTION,[
          'label'=>'Team',
          'supporText'=>'Favorite Team',
          'helpText'=>'Insert Your Favorite Team',
          'displayInBackend'=>true,
          'entity'=>Team::class,
          'position'=>99,

      ]);

      $attributeCrudService->update('s_user_attributes','player',TypeMapping::TYPE_SINGLE_SELECTION,[
          'label'=>'Player',
          'supporText'=>'Favorite Player',
          'helpText'=>'Insert Your Favorite Player',
          'displayInBackend'=>true,
          'entity'=>Player::class,
          'position'=>100,

      ]);
        $attributeCrudService->update('s_articles_attributes','team',TypeMapping::TYPE_SINGLE_SELECTION,[
            'label'=>'Team',
            'supporText'=>'Favorite Team',
            'helpText'=>'Insert Favorite Team',
            'displayInBackend'=>true,
            'entity'=>Team::class,
            'position'=>100,
        ]);

        $attributeCrudService->update('s_articles_attributes','gadget',TypeMapping::TYPE_SINGLE_SELECTION,[
            'label'=>'Gadget',
            'supporText'=>'Gift Gadget',
            'helpText'=>'Insert Gift Gadget',
            'displayInBackend'=>true,
            'entity'=>Article::class,
            'position'=>101,
        ]);

        $attributeCrudService->update('s_articles_attributes','is_gadget',TypeMapping::TYPE_BOOLEAN,[
            'label'=>'Is Gadget',
            'supporText'=>'Is Gift Gadget?',
            'helpText'=>'Check if is Gift Gadget',
            'displayInBackend'=>true,
            'position'=>102,
        ]);

        Shopware()->Models()->generateAttributeModels(['s_articles_attributes']);
        Shopware()->Models()->generateAttributeModels(['s_user_attributes']);

       $connection = $this->container->get('dbal_connection');
       /** Add New Customer Group For Data Entry */
        $connection->insert(
            's_core_customergroups',
            [
                'groupkey' => 'DE',
                'description' => 'Data Entry'

            ]
        );

        /** Add New Category For Gadget Articles */

        $connection->insert(
            's_categories',
            [
                'parent' => 39,
                'path'  =>  '|39|',
                'description' => 'Gadgets',
                'position' => 0

            ]
        );
        $connection->insert(
            's_categories',
            [
                'parent' => 3,
                'path'  =>  '|3|',
                'description' => 'Gadgets',
                'position' => 0

            ]
        );
      $this->updateSchema();
    }

    /**
     * @param UninstallContext $uninstallContext
     */
    public function uninstall(UninstallContext $uninstallContext)
    {
        if ($uninstallContext->keepUserData()) {
            return;
        }

        $attributeCrudService = $this->container->get('shopware_attribute.crud_service');
        $attributeCrudService->delete('s_user_attributes','team');
        $attributeCrudService->delete('s_user_attributes','player');
        $attributeCrudService->delete('s_articles_attributes','team');
        $attributeCrudService->delete('s_articles_attributes','gadget');
        $attributeCrudService->delete('s_articles_attributes','is_gadget');


        $connection = $this->container->get('dbal_connection');
        /** Delete  Customer Group For Data Entry */
        $connection->delete('s_core_customergroups',array('groupkey' => 'DE'));
        $connection->delete('s_categories',array('description' => 'Gadgets'));

        $tool = new SchemaTool($this->container->get('models'));
        $classes = $this->getModelMetaData();
        $tool->dropSchema($classes);

        $uninstallContext->scheduleClearCache(UninstallContext::CACHE_LIST_ALL);

    }

    private function updateSchema()
    {
        $tool = new SchemaTool($this->container->get('models'));
        $classes = $this->getModelMetaData();

    try {
        $tool->dropSchema($classes);
    } catch (\Exception $e) {

    }
    $tool->createSchema($classes);

    }
    /**
     * @return array
     */
    private function getModelMetaData()
    {

        return [$this->container->get('models')->getClassMetadata(Models\Team::class), $this->container->get('models')->getClassMetadata(Models\Player::class)];
    }

    public function update(UpdateContext $updateContext)
    {

    }

    public function activate(ActivateContext $activateContext)
    {
        $activateContext->scheduleClearCache(ActivateContext::CACHE_LIST_ALL);

    }

    public function deactivate(DeactivateContext $deactivateContext)
    {
     $deactivateContext->scheduleClearCache(DeactivateContext::CACHE_LIST_ALL);
    }

    /**
     * Builds the Plugin and adds the plugin path to the container
     *
     * @param ContainerBuilder $container
     */

    public function build(ContainerBuilder $container)
    {
        $container->setParameter('test_plugin.plugin_dir', $this->getPath());
        parent::build($container);
    }

}