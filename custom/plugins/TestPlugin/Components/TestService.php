<?php

namespace TestPlugin\Components;


use Doctrine\ORM\Tools\SchemaTool;
use Shopware\Components\Plugin;


class TestService
{
public function sum($a,$b){
return $a+$b;
}


public function getTeams(){

    $connection = $this->container->get('dbal_connection');
    $teams = $connection->executeQuery('SELECT * FROM s_team ORDER BY name ASC ')
        ->fetchAll(\PDO::FETCH_ASSOC);
    return $teams;

}
}