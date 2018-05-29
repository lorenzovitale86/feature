<?php

use TestPlugin\Components\Api\Resource\Bundle;

class Shopware_Controllers_Frontend_Test extends Enlight_Controller_Action
{
    /**
     * @var sAdmin
     */
    protected $admin;

    /**
     * @array teams
     */
    protected  $teams;

    /**
     * @var userData
     */
    protected  $userData;

    /**
     * @var team_service
     */
    protected  $team_service;

    /** @var Bundle $resource */
    protected $resource;


    /**
     * Init controller method
     */
    public function init()
    {
        $this->admin = Shopware()->Modules()->Admin();
        $this->resource=$this->getBundleAPI();
    }

    public function preDispatch()
    {
        $this->team_service=$this->container->get('test_plugin.test_service');
        $this->View()->setScope(Enlight_Template_Manager::SCOPE_PARENT);
        $this->userData = $this->admin->sGetUserData();
        $this->View()->assign('sUserData' ,$this->userData);
    }

    public function indexAction()
    {
        /*Retrieve all the teams, and assign the collection to teams variable*/
        $teams = $this->resource->getList(0,null,null,null);
        $this->View()->assign('teams',$teams['data']);
        $players = $this->team_service->getAllPlayers();
        $this->View()->assign('players',$players);
        $idfavoriteTeam  = $this->userData['additional']['user']['team'];
        $this->View()->assign('idfavoriteTeam',$idfavoriteTeam);
        $teamname = $this->resource->getOne($idfavoriteTeam);

        $teamname= $this->team_service->findTeamNameById($idfavoriteTeam);
        $this->View()->assign('favoriteTeam' ,$teamname);
        $idfavoritePlayer  = $this->userData['additional']['user']['player'];
        $this->View()->assign('idfavoritePlayer',$idfavoritePlayer);
        $playername= $this->team_service->findPlayerNameById($idfavoritePlayer);
        $this->View()->assign('favoritePlayer' ,$playername);
    }


    public function savePreferenceAction()
    {
        $this->team_service = $this->container->get('test_plugin.test_service');
        $userId = $this->get('session')->get('sUserId');

        $b = $this->Request()->getParams();
        $team = $b['profile']['team'];
        $player = $b['profile']['player'];

        $rows = $this->team_service->updatePreference($userId, $team, $player);

        if ($rows > 0) {

            $this->redirect([
                'controller' => 'account',
                'action' => 'profile',
                'msg' => 'success'
            ]);
        } else {
            $this->redirect([
                'controller' => 'account',
                'action' => 'profile',
                'msg' => 'error'
            ]);
        }
    }

    public function ajaxSavePreferenceAction()
    {
        $this->team_service = $this->container->get('test_plugin.test_service');
        $userId = $this->get('session')->get('sUserId');
        $team = $this->Request()->get('team');
        $player = $this->Request()->get('player');
        $rows = $this->team_service->updatePreference($userId, $team, $player);
        if ($rows >= 0) {
            $message = 'success';
        } else {
            $message = 'fail';
        }
        $this->Front()->Plugins()->ViewRenderer()->setNoRender();
        $this->Response()->setBody(
            json_encode(
                array("res"=>$message)
            )
        );
    }

    public function teamPlayersAction()
    {
            //TODO add post check?
        $teamId = $this->Request()->get('idteam');
        $teamplayers = $this->getBundleAPI()->getListPlayersTeam($teamId);
        $this->Front()->Plugins()->ViewRenderer()->setNoRender();
        $this->Response()->setBody(
            json_encode(
                $teamplayers
            )
        );
    }

    /** Returns an instance of the bundle API... */
    public function getBundleAPI()
    {
        /** @var Bundle $resource */
        $resource = \Shopware\Components\Api\Manager::getResource('Bundle');

        return $resource;
    }
}