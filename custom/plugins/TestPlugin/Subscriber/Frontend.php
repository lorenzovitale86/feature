<?php
namespace TestPlugin\Subscriber;

use Doctrine\Common\Collections\ArrayCollection;
use Enlight\Event\SubscriberInterface;
use Enlight_Components_Db_Adapter_Pdo_Mysql;
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
     * Database connection which used for each database operation in this class.
     * Injected over the class constructor
     *
     * @var Enlight_Components_Db_Adapter_Pdo_Mysql
     */
    protected $db;

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
    public function __construct($pluginDirectory, Container $container,  Enlight_Components_Db_Adapter_Pdo_Mysql $db = null)
    {
        $this->db = $db ?: Shopware()->Db();
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
            'Enlight_Controller_Action_PreDispatch_Frontend_Checkout'=> 'onPreDispatchCheckoutConfirm',
            'Enlight_Controller_Action_PostDispatchSecure_Widgets' => 'addLabelWidgets',
         //   'Enlight_Controller_Action_PostDispatchSecure_Frontend_AjaxSearch' => 'onPostDispatchFrontendAjaxSearch',
          //  'Enlight_Controller_Action_PostDispatchSecure_Frontend_Search' => 'onPostDispatchFrontendSearch',
            'sBasket::sAddArticle::after' => 'afterAddArticleToCart',
            'sBasket::sDeleteArticle::before' => 'beforeDeleteArticle',
    //      'sBasket::sGetAmountArticles::replace' =>'replaceGetAmountArticles',
    //      'sBasket::sGetAmount::replace' =>'replaceGetAmount',
            'sBasket::sGetBasket::after' => 'afterGetBasketUpdatePrice'
            //  'Theme_Compiler_Collect_Plugin_Javascript' => 'addJsFiles'
        ];
    }

    public function onPreDispatchCheckoutConfirm(\Enlight_Controller_ActionEventArgs $args)
    {
        $controller = $args->getSubject();
        $action = $controller->Request()->getActionName();
        $showRemoveGadget=false;
        if ($action === 'confirm') {
            $showRemoveGadget = true;
        }
        $controller->View()->assign("showRemoveGadget","$showRemoveGadget");


    }

   /* public function onPostDispatchFrontendAjaxSearch(\Enlight_Controller_ActionEventArgs $args)
    {
     $controller = $args->getSubject();

        $sResults = array($controller->View()->getAssign("sSearchResults"));
        $sCount = $sResults[0]['sArticlesCount'];
        foreach ($sResults[0]['sResults'] as $key => $r) {
            if ($r['is_gadget']==1) {
                array_splice($sResults[0]['sResults'],$key,1);
                $sCount--;
            }
        }
        $controller->View()->assign("sSearchResults", [
                'sResults' => $sResults[0]['sResults'],
                'sArticlesCount' => $sCount
        ]);

    }

    public function onPostDispatchFrontendSearch(\Enlight_Controller_ActionEventArgs $args)
    {
        $controller = $args->getSubject();

        $sResults = array($controller->View()->getAssign("sSearchResults"));
        $sCount = $sResults[0]['sArticlesCount'];
        foreach ($sResults[0]['sArticles'] as $key => $r) {
            if ($r['is_gadget']==1) {
                array_splice($sResults[0]['sArticles'],$key,1);
                $sCount--;
            }
        }
        $controller->View()->assign("sSearchResults", [
            'sArticles' => $sResults[0]['sArticles'],
            'sArticlesCount' => $sCount
        ]);

    }
*/
    public function onPreDispatchDetail(\Enlight_Event_EventArgs $args)
    {
        $controller = $args->getSubject();

        $idArticle = $controller->Request()->sArticle;

        $article = $this->articleRepository->findById($idArticle);
        $isGadget = $article[0]->getAttribute()->getIsGadget();
        $articleNumber = $article[0]->getDetails()[0]->getNumber();

        if ($isGadget) {
            $basket = $this->basket->sGetBasket();
            $gadgetInCart=false;
           foreach ($basket['content'] as $content) {
               if ($articleNumber == $content['ordernumber']) {
                    $gadgetInCart = true;
                    break;
               }
           }
           if (!$gadgetInCart) {
               try {
                   $controller->redirect(['controller' => 'index']);
               } catch (Exception $e) {

               };

           }
        }

    }

    public function onPostDispatchFrontend(\Enlight_Event_EventArgs $args)
    {
        $controller = $args->getSubject();
        $view = $controller->View();
        $view->assign('testUserData', $this->userData);
    }

    public function afterGetBasketUpdatePrice(\Enlight_Hook_HookArgs $args)
    {
        $return = $args->getReturn();

        foreach ($return['content'] as $singleReturn ) {
            if ($singleReturn['additional_details']['is_gadget'] == 1) {
                $return['Amount'] =  str_replace(',','.',$return['Amount']) - str_replace(',','.',$singleReturn['price']);
                $return['AmountNet'] = str_replace(',','.',$return['AmountNet'])  - str_replace(',','.',$singleReturn['netprice']);
                $return['AmountWithTax'] = str_replace(',','.',$return['AmountWithTax'])  - str_replace(',','.',$singleReturn['netprice']);
            }
        }

        $return['AmountNumeric'] = $return['Amount'];
        $return['AmountNetNumeric'] = $return['AmountNet'];
        $return['AmountWithTaxNumeric'] = $return['AmountWithTax'];

        $args->setReturn($return);
       // return $result;
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
                $gadgetIdInBakset = 0;
                foreach ($basket['content'] as $content) {
                    if ($articleGadgetNumber == $content['ordernumber']) {
                        $gadgetInCart = true;
                        break;
                    }
                }
                if (!$gadgetInCart) {

                    //$this->basket->sAddArticle($articleGadgetNumber, 1);
                    $gadgetArticle = $this->articleDetailRepository->findByNumber($articleGadgetNumber)[0]->getArticle();
                    $gadgetName = $gadgetArticle->getAttribute()->getArticle()->getName();
                    $gadgetIdInBakset = $gadgetArticle->getAttribute()->getArticleID();

                    $connection = $this->container->get('dbal_connection');
                    $conn = $connection->executeQuery('INSERT INTO s_order_basket(id,sessionID,userID,articlename,articleID,ordernumber,shippingfree,quantity,price,netprice,datum)VALUES(?,?,?,?,?,?,?,?,?,?,?)',array(
                    '',Shopware()->Session()->get("sessionId"),$this->userData['additional']['user']['id'],$gadgetName,
                    $gadgetIdInBakset,$articleGadgetNumber,0,1,0,0,date("Y-m-d H:i:s")));
               //    $this->basket->sRefreshBasket();
                }

            }
        }
    }

    public function beforeDeleteArticle(\Enlight_Hook_HookArgs $args)
    {
        $showConfirmRemove = true;

        $basket = $this->basket->sGetBasket();
        $idOrderArticle = $args->get('id');
        $gadget = null;
        foreach ($basket['content'] as $content) {
            if ($idOrderArticle == $content['id']) {
                $gadget = $content['additional_details']['gadget'];
                $showConfirmRemove = true;
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

  /*  public function replaceGetAmount(\Enlight_Hook_HookArgs $args)
    {
        $result = $this->db->fetchRow(
            'SELECT SUM(ob.quantity*(floor(ob.price * 100 + .55)/100)) AS totalAmount
                FROM s_order_basket ob ,s_articles_attributes aa  
                WHERE ob.articleID = aa.articleID AND (aa.is_gadget IS NULL OR aa.is_gadget!= 1) AND ob.sessionID =? GROUP BY ob.sessionID',
            [Shopware()->Session()->get("sessionId")]
        );


        $args->setReturn($result[0]['totalAmount']);
        return $result;
    }

    public function replaceGetAmountArticles(\Enlight_Hook_HookArgs $args)
    {
        $result = $this->db->fetchRow(
            'SELECT SUM(ob.quantity*(floor(ob.price * 100 + .55)/100)) AS totalAmount
                FROM s_order_basket ob ,s_articles_attributes aa  
                WHERE ob.articleID = aa.articleID AND (aa.is_gadget IS NULL OR aa.is_gadget!= 1) AND ob.sessionID =? GROUP BY ob.sessionID',
            [Shopware()->Session()->get("sessionId")]
        );

        $args->setReturn($result);
        return $result;
    }
*/
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
            /** CHECK IF A GIFT GADGET IS PRESENT */

            $basket = $this->basket->sGetBasket();
            $gadgetInCart=false;

            foreach ($basket['content'] as $content) {

                if ($content['additional_details']['is_gadget']) {
                    $gadgetInCart = true;
                    break;
                }

            }
            $view->assign('gadgetInCart', $gadgetInCart);
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