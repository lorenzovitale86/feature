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
use Shopware\Models\Article\Article;
use Shopware\Models\Article\Detail;
use Shopware\Models\Category\Category;
use Shopware\Components\BasketSignature\Basket;

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

    protected $basket;
    protected $teamRepository;
    protected $playerRepository;
    protected $articleRepository;
    protected $articleDetailRepositoty;
    protected $categoryRepository;
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
        $this->articleRepository = Shopware()->Container()->get('models')->getRepository(Article::class);
        $this->articleDetailRepository = Shopware()->Container()->get('models')->getRepository(Detail::class);
        $this->categoryRepository = Shopware()->Container()->get('models')->getRepository(Category::class);
        $this->basket = Shopware()->Modules()->Basket();
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
            'Enlight_Controller_Action_PreDispatch_Frontend_Detail'=> 'onPreDispatchDetail',
            'Enlight_Controller_Action_PostDispatchSecure_Widgets' => 'addLabelWidgets',
            'sBasket::sAddArticle::after' => 'afterAddArticleToCart',
            'sBasket::sDeleteArticle::before' => 'beforeDeleteArticle'
            //  'Theme_Compiler_Collect_Plugin_Javascript' => 'addJsFiles'
        ];
    }


    public function onPreDispatchDetail(\Enlight_Event_EventArgs $args)
    {
        $controller = $args->getSubject();

        $idArticle = $controller->Request()->sArticle;

        $article = $this->articleRepository->findById($idArticle);
        $isGadget = $article[0]->getAttribute()->getIsGadget();

        if ($isGadget) {
            try {
                    $controller->redirect([
                    'controller' => 'index',
                    'action'    => 'index',
                    ]);
                } catch (Exception $e) {

            };
        }

    }

    public function onPostDispatchFrontend(\Enlight_Event_EventArgs $args)
    {
        $controller = $args->getSubject();
        $view = $controller->View();
        $view->assign('testUserData', $this->userData);
    }

    public function afterAddArticleToCart(\Enlight_Hook_HookArgs $args)
    {
        /** $id value of the order number in s_order_basket table     */
        $id = $args->get('id');
        $det = $this->articleDetailRepository->findByNumber($id);
        /** @var  $articleTeamId team id of the Article*/
        $articleTeamId = $det[0]->getArticle()->getAttribute()->getTeam();

        // if the Article has a team id check if is the same of the favorite id of the customer
        if ($articleTeamId>0) {

            $customerteamid = $this->userData['additional']['user']['team'];
            if ($articleTeamId === $customerteamid) {

                /* Search in the categories of the articles if there is Gadgets            */
                //    $articleGadget =  $this->categoryRepository->findByName("Gadgets");

                $articleGadgetNumber = $det[0]->getArticle()->getAttribute()->getGadget();

                /** @var  $gadgetOrderNumber  the ordernumber of the first gadget article*/
                // $gadgetOrderNumber = $articleGadget[0]->getArticles()[0]->getDetails()[0]->getNumber();
                $basket = $this->basket->sGetBasket();
                $gadgetInCart=false;
                foreach ($basket['content'] as $content) {
                    if ($articleGadgetNumber == $content['ordernumber']) {
                        $gadgetInCart = true;
                        return;
                    }
                }
                if (!$gadgetInCart) {
                    $this->basket->sAddArticle($articleGadgetNumber, 1);
                    $this->basket->sRefreshBasket();
                }

            }
        }

    }

    public function beforeDeleteArticle(\Enlight_Hook_HookArgs $args)
    {
        $basket = $this->basket->sGetBasket();
        $idOrderArticle = $args->get('id');
        $gadget = null;
        foreach ($basket['content'] as $content) {
            if ($idOrderArticle == $content['id']) {
                $gadget = $content['additional_details']['gadget'];

          break;
            }
        }
        if ($gadget) {
            foreach ($basket['content'] as $content) {
                if ($gadget == $content['ordernumber']) {
                    $this->basket->sDeleteArticle($content['id']);
                    $this->basket->sRefreshBasket();
                    break;
                }
            }
        }
    }

    public function onPreDispatchAccount(\Enlight_Controller_ActionEventArgs $args)
    {
        $controller = $args->getSubject();
        $action = $controller->Request()->getActionName();
        $view = $controller->View();
        $groupDataEntry = $this->getPluginConfig("groupCustomer");

        $userCustomerGroupKey = $this->userData['additional']['user']['customergroup'];
        $view->assign('groupCustomer',$userCustomerGroupKey);
        if ($action=='preferences') {
            if ($userCustomerGroupKey != $groupDataEntry) {
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

        /** CHECK IF A GIFT GADGET IS PRESENT */

        $basket = $this->basket->sGetBasket();
        $gadgetInCart=false;
        foreach ($basket['content'] as $content) {

            if ($content['additional_details']['is_gadget']) {
                $gadgetInCart = true;
                return;
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