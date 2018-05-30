<?php
namespace TestPlugin\Subscriber;

use Doctrine\Common\Collections\ArrayCollection;
use Enlight\Event\SubscriberInterface;
use Enlight_Controller_ActionEventArgs;
use Shopware\Components\DependencyInjection\Container;
use TestPlugin\Components\Api\Resource\Bundle;
use Shopware\Components\Plugin\DBALConfigReader;
use TestPlugin\Models\Team;
use TestPlugin\Models\Player;

class Frontend implements SubscriberInterface
{
    /**
     * @var DBALConfigReader
     */
    protected $configReader;

    /**
     * @var string
     */
    protected $pluginDirectory;

    /**
     * @var string
     */
    protected $container;

    /**
     * @var string
     */
    protected $userData;

    /**
     * @var string
     */
    protected $resource;

    protected $teamRepository;
    protected  $playerRepository;
    /**
     * @param $pluginDirectory
     * @param Container $container
     */
    public function __construct($pluginDirectory, Container $container)
    {
        $this->pluginDirectory = $pluginDirectory;
        $this->container = $container;
        $this->userData = Shopware()->Modules()->Admin()->sGetUserData();
        $this->resource = $this->getBundleAPI();
        $this->container->get('template')->addTemplateDir(
            $this->pluginDirectory . '/Resources/views/'
        );
        /** @var \Shopware\Components\Model\ModelManager $shopwareModel */
        $shopwareModel=Shopware()->Models();
        $this->teamRepository = $shopwareModel->getRepository(Team::class);
        $this->playerRepository = $shopwareModel->getRepository(Player::class);

    }

    /** Returns an instance of the bundle API... */
    public function getBundleAPI()
    {
        /** @var Bundle $resource */
        $resource = \Shopware\Components\Api\Manager::getResource('Bundle');

        return $resource;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onPostDispatchFrontend',
            'Enlight_Controller_Action_PreDispatch_Frontend_Account'=> 'onPreDispatchAccount',
            'Enlight_Controller_Action_PostDispatchSecure_Widgets' => 'addLabelWidgets',
            //  'Theme_Compiler_Collect_Plugin_Javascript' => 'addJsFiles'
        ];
    }


    public function onPostDispatchFrontend(\Enlight_Event_EventArgs $args)
    {
        $controller = $args->getSubject();
        $view = $controller->View();
        $view->assign('testUserData', $this->userData);
    }


    public function onPreDispatchAccount(\Enlight_Controller_ActionEventArgs $args)
    {
        $controller = $args->getSubject();
        $action = $controller->Request()->getActionName();
        $view = $controller->View();
        $userCustomerGroupKey = $this->userData['additional']['user']['customergroup'];
        $view->assign('groupCustomer',$userCustomerGroupKey);
        if ($action=='preferences') {
            if ($userCustomerGroupKey != 'DE') {
                try {
                    $controller->redirect(['controller' => 'index']);
                } catch (\Exception $e) {
                }
            }
            $par = $controller->Request()->get('msg');
            $controller->forward('index', 'preference',null,array("msg"=>$par));
        }

        if ($action == 'index') {
            if ($this->userData['additional']['user']['team']>0) {
                $par = $this->userData['additional']['user']['team'];
                $userTeam  =  $this->teamRepository->find($par);
                $view->assign('userTeamName', $userTeam->getName());
            }

            if ($this->userData['additional']['user']['player']>0) {
                $par = $this->userData['additional']['user']['player'];

                $userPlayer  =  $this->playerRepository->find($par);
                $view->assign('userPlayerName', $userPlayer->getName(). " " .$userPlayer->getSurname());
            }

        }
        if ($action == 'profile') {
            $teams = $this->resource->getList(0,null,null,null);
            $view->assign('teams',$teams['data']);
            $par = null;
            if ($this->userData['additional']['user']['team']>0) {
                $par = $this->userData['additional']['user']['team'];

            }
            $players = $this->resource->getListPlayersTeam($par,0,null,null,null);
            $view->assign('players',$players['data']);
            $par =   $controller->Request()->getParams();
            if (isset($par['msg'])) {
                $msg = $par['msg'];
                $view->assign('msg', $msg);
            }
        }
        $view->assign('testUserData', $this->userData);
    }

    public function addLabelWidgets(Enlight_Controller_ActionEventArgs $args)
    {
        $controller = $args->getSubject();
        $view = $controller->View();
        $userCustomerGroupKey = $this->userData['additional']['user']['customergroup'];
        $view->assign('groupCustomer',$userCustomerGroupKey);
        $view->assign('testUserData', $this->userData);
    }

    /**
     * Adds javascript files
     *
     * @param \Enlight_Event_EventArgs $arguments
     * @return ArrayCollection
     */
    public function addJsFiles(\Enlight_Event_EventArgs $args){
        $js = $this->container->getParameter('test_plugin.plugin_dir') . '/Resources/frontend/js/testfile.js';
        return new ArrayCollection(array($js));
    }

    private function getPluginConfig($name, $default = null)
    {
        $configReader = $this->container->get('shopware.plugin.config_reader');
        $pluginConfig = $configReader->getByPluginName('TestPlugin');
        return isset($pluginConfig[$name]) ? $pluginConfig[$name] : $default;
    }
}