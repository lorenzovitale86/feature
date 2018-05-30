<?php

namespace TestPlugin\Subscriber;

use Enlight\Event\SubscriberInterface;

class Backend implements SubscriberInterface
{

    /**
     * @var string
     */
    protected $pluginDirectory;

    /**
     * @var string
     */
    protected $container;

    /**
     * @param $pluginDirectory
     * @param Container $container
     */
    public function __construct($pluginDirectory, Container $container)
    {
        $this->pluginDirectory = $pluginDirectory;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
    //        'Enlight_Controller_Action_PostDispatchSecure_Backend_Customer' => 'onCustomerPostDispatch'

        ];
    }

    public function onCustomerPostDispatch(\Enlight_Event_EventArgs $args)
    {
        /** @var \Shopware_Controllers_Backend_Customer $controller */
        $controller = $args->getSubject();

        $view = $controller->View();
        $request = $controller->Request();

        $view->addTemplateDir($this->pluginDirectory . '/Resources/views');

     /*   if ($request->getActionName() == 'index') {
            $view->extendsTemplate('backend/test/app.js');
        }
*/
       /* if ($request->getActionName() == 'load') {
            $view->extendsTemplate('backend/test/view/detail/window.js');
        }*/
    }
}