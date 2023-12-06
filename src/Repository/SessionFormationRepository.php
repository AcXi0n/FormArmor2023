<?php

namespace App\Repository;

use App\Entity\SessionFormation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<SessionFormation>
 *
 * @method SessionFormation|null find($id, $lockMode = null, $lockVersion = null)
 * @method SessionFormation|null findOneBy(array $criteria, array $orderBy = null)
 * @method SessionFormation[]    findAll()
 * @method SessionFormation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionFormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SessionFormation::class);
    }

	public function getSessionsFormationClient()
	{
		$queryBuilder = $this->createQueryBuilder('sf');
		$queryBuilder->select('sf.id, formation.libelle, formation.niveau, formation.description, sf.nbPlaces, sf.nbInscrits, sf.dateDebut');
		$queryBuilder->join('sf.formation', 'formation'); // Jointure avec formation
		$query = $queryBuilder->getQuery();
		return $query->getResult();
	}

	public function getSessionsFormationDisponiblesClient($idClient)
	{
		$queryBuilder = $this->createQueryBuilder('sf');	
		
		return $queryBuilder->select('sf.id')
						->join('sf.formation', 'formation')
						->join('formation.plans', 'planformation')
						->join('planformation.client', 'client')
						//->where('sf.id NOT IN (:notIn)')
						//->setParameter('notIn', $notIn)
						//->where($queryBuilder->expr()->notIn('sf.id', $notIn))
						->andWhere('sf.close = false')
						->andWhere('client.id = :IdClient')
						->setParameter('IdClient', $idClient)
						->getQuery()
						->getResult(Query::HYDRATE_ARRAY);		

		/*
			SELECT session_formation.id FROM session_formation
			join formation on formation.id = session_formation.formation_id
			join plan_formation on plan_formation.formation_id = formation.id
			where nb_places > nb_inscrits 
			and close = false
			and session_formation.id not in (SELECT sessionformation_id from inscription
												where client_id = 2)
			and client_id = 2
		*/
	}

    public function listeSessions() // Liste toutes les sessions
	{
		$queryBuilder = $this->createQueryBuilder('s');
		$query = $queryBuilder->getQuery();
		return $query->getResult();
	}

	public function suppSession($id) // Suppression de la session d'identifiant $id
	{
		$qb = $this->createQueryBuilder('s');
		$query = $qb->delete('App\Entity\SessionFormation', 's')
		  ->where('s.id = :id')
		  ->setParameter('id', $id);
		
		return $qb->getQuery()->getResult();
	}
}
