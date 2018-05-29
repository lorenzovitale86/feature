<?php

use Doctrine\DBAL\Connection;
use TestPlugin\Models\Team;
use TestPlugin\Models\Player;

class Shopware_Controllers_Frontend_Preference extends Enlight_Controller_Action
{

    /**
     * @var sAdmin
     */
    protected $admin;

    protected $userData;

    protected $plugindir;

    protected $teamRepository;

    protected $playerRepository;

    public function init ()
    {
      /** @var \Shopware\Components\Model\ModelManager $shopwareModel */
      $shopwareModel=Shopware()->Models();
      $this->teamRepository = $shopwareModel->getRepository(Team::class);
    }

    public function preDispatch ()
    {
        $this->admin = Shopware()->Modules()->Admin();
        $this->userData = $this->admin->sGetUserData();
    }
    public function indexAction ()
    {
        $teams = $this->teamRepository->findAll();
        $this->View()->assign('teams', $teams);
        $msg  = $this->Request()->get('msg');
        $this->View()->assign('msg', $msg);
        $userCustomerGroupKey = Shopware()->Modules()->Admin()->sGetUserData()['additional']['user']['customergroup'];
        $this->View()->assign('groupCustomer',$userCustomerGroupKey);
    }

    public function createTeamAction ()
    {
        $team = $this->Request()->get('teamName');
        $logo = $this->Request()->get('teamLogo');

        $newTeam = new Team();
        $newTeam->setName(ucwords($team));
        $newTeam->setLogo(ucwords($logo));
        Shopware()->Models()->persist($newTeam);
        Shopware()->Models()->flush();
        try {
            $this->redirect([
                'controller' => 'account',
                'action' => 'preferences',
                'msg' => 'success'
            ]);
        } catch (Exception $e) {
        }
    }

    public function createPlayerAction ()
    {
        $playerName = $this->Request()->get('playerName');
        $playerSurname = $this->Request()->get('playerSurname');
        $playerAge = $this->Request()->get('playerAge');
        $playerTeam = $this->Request()->get('playerTeam');
        $newPlayer = new Player();
        $newPlayer->setName(ucwords($playerName));
        $newPlayer->setSurname(ucwords($playerSurname));
        $newPlayer->setEta(intval($playerAge));
        $team  = $this->teamRepository->find($playerTeam);
        $newPlayer->setIdteam($team);
        Shopware()->Models()->persist($newPlayer);
        Shopware()->Models()->flush();

        try {
            $this->redirect([
                'controller' => 'account',
                'action' => 'preferences',
                'msg' => 'success'
            ]);
        } catch (Exception $e) {
        }
    }

}