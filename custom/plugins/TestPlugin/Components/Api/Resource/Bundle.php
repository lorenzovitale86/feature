<?php
namespace TestPlugin\Components\Api\Resource;

use Shopware\Components\Api\Exception as ApiException;
use Shopware\Components\Api\Resource\Resource;
use TestPlugin\Models\Team as TeamModel;
use TestPlugin\Models\Player as PlayerModel;

class Bundle extends Resource
{
    /** @param $offset ...*/
    public function getList($offset, $limit, $filter, $sort)
    {
        //QueryBuilder To Read teams
        $builder = $this->getBaseQuery();

        $builder->setFirstResult($offset);

        $builder->setMaxResults($limit);
        if(!empty($filter)){
            $builder->addFilter($filter);
        }
        if(!empty($sort)){
            $builder->addOrderBy($sort);
        }

        $query = $builder->getQuery();

        $query->setHydrationMode($this->getResultMode());

        // PAGINATOR PER NON CONTARE le subquery nel limit
        $paginator = $this->getManager()->createPaginator($query);

        $teams = $paginator->getIterator()->getArrayCopy();

        $totalResult = $paginator->count();

        //Return the list as object or array depending on $this->resultMode

        return ['data' => $teams, 'total' => $totalResult];
    }

    /**@param id **/
    public function getOne($id)
    {
        $builder = $this->getBaseQuery();
        $builder->where('team.id = :id')
            ->setParameter(':id',$id);
        $team = $builder->getQuery()->getOneOrNullResult($this->getResultMode());
        if (!$team) {
            throw new ApiException\NotFoundException("No Team Found Associated To this user");
        }
        return $team['name'];
    }

    /*Return List Of All Player*/
    public function getListPlayers($offset, $limit, $filter, $sort)
    {
        //QueryBuilder To Read teams
        $builder = $this->getBaseQueryPlayer();

        $builder->setFirstResult($offset);

        $builder->setMaxResults($limit);

        if(!empty($filter)){
            $builder->addFilter($filter);
        }
        if(!empty($sort)){
            $builder->addOrderBy($sort);
        }

        $query = $builder->getQuery();
        $query->setHydrationMode($this->getResultMode());

        // PAGINATOR PER NON CONTARE le subquery nel limit
        $paginator = $this->getManager()->createPaginator($query);

        $players = $paginator->getIterator()->getArrayCopy();
        $totalResult = $paginator->count();

        //Return the list as object or array depending on $this->resultMode

        return ['data' => $players, 'total' => $totalResult];
    }

    /*Return List Of All Player*/
    public function getListPlayersTeam($id =null, $offset =0, $limit = null, $filter =null, $sort =null)
    {
        //QueryBuilder To Read teams
        $builder = $this->getBaseQueryPlayer();
        if($id!=null)
        $builder->where('player.idteam = :id')
            ->setParameter(':id',$id);

        $builder->setFirstResult($offset);

        $builder->setMaxResults($limit);

        if(!empty($filter)){
            $builder->addFilter($filter);
        }
        if(!empty($sort)){
            $builder->addOrderBy($sort);
        }

        $query = $builder->getQuery();
        $query->setHydrationMode($this->getResultMode());

        // PAGINATOR PER NON CONTARE le subquery nel limit
        $paginator = $this->getManager()->createPaginator($query);

        $players = $paginator->getIterator()->getArrayCopy();
        $totalResult = $paginator->count();

        //Return the list as object or array depending on $this->resultMode

        return ['data' => $players];
    }

    private function getBaseQuery()
    {
        $builder = $this->getManager()->createQueryBuilder();

        $builder->addSelect(['team','players'])
            ->from(\TestPlugin\Models\Team::class, 'team')
            ->leftJoin('team.players','players');
        return $builder;
    }

    private function getBaseQueryPlayer()
    {
        $builder = $this->getManager()->createQueryBuilder();

        $builder->addSelect(['player'])
            ->from(\TestPlugin\Models\Player::class, 'player');
        return $builder;
    }

}