<?php
namespace TestPlugin\Components\Api\Resource;

use Doctrine\ORM\Tools\SchemaTool;

class TestService
{
    /**
     * @var container
     */
    protected  $container;

    public function __construct($container )
    {
        $this->container=$container;
    }

    public function getAllTeams()
    {
        $connection = $this->container->get('dbal_connection');
        $teams = $connection->executeQuery('SELECT * FROM test_team ORDER BY name ASC')
            ->fetchAll(\PDO::FETCH_ASSOC);
        return $teams;
    }

    public function getAllPlayers()
    {
        $connection = $this->container->get('dbal_connection');
        $players = $connection->executeQuery('SELECT * FROM test_player ORDER BY name,surname ASC')
            ->fetchAll(\PDO::FETCH_ASSOC);
        return $players;
    }

    public function findTeamNameById($id)
    {
    $connection = $this->container->get('dbal_connection');
    $conn = $connection->executeQuery('SELECT name FROM test_team WHERE id=? ORDER BY name ASC',array($id));
    $teamName= $conn->fetchColumn();
    return $teamName;
    }

    public function findPlayerNameById($id)
    {
        $connection = $this->container->get('dbal_connection');
        $conn = $connection->executeQuery('SELECT name,surname FROM test_player WHERE id=? ORDER BY name,surname ASC',array($id));
        $playerName= $conn->fetchAll();
        return $playerName;
    }

    public function updatePreference($id, $team, $player)
    {
        $connection = $this->container->get('dbal_connection');
        $conn = $connection->executeUpdate('UPDATE s_user_attributes SET team=?,player=? WHERE userID=?',array($team,$player,$id));
        return $conn;
    }
}