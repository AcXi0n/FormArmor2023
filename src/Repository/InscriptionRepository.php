<?php

namespace App\Repository;

use App\Entity\Inscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<Inscription>
 *
 * @method Inscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inscription[]    findAll()
 * @method Inscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inscription::class);
    }

	public function getInscriptionParSession($idSession) // pour récup les inscriptions à une session
	{
		$queryBuilder = $this->createQueryBuilder('i');

		return $queryBuilder->join('i.sessionformation', 'sf') // Jointure avec session_formation
									->where('sf.id = :IdSession')
									->setParameter('IdSession', $idSession)
									->getQuery()
									->getResult();
	}


    public function getSessionFormationRealiseClient($idClient)
	{
		$queryBuilder = $this->createQueryBuilder('i');

		return $queryBuilder->select('sf.id')
                                    ->join('i.sessionformation', 'sf') // Jointure avec session_formation
                                    ->join('i.client', 'c') // Jointure avec client
									->where('c.id = :IdClient')
									->setParameter('IdClient', $idClient)
									->getQuery()
									->getResult();
	}

	public function getInscriptionSessionFormationClient($idClient) // pour récap des inscriptions du client
	{
		$queryBuilder = $this->createQueryBuilder('i');

		return $queryBuilder->select('sf.id, formation.libelle, formation.niveau, formation.description, i.dateInscription, i.etat, i.id as idInscription')									
									->join('i.sessionformation', 'sf') // Jointure avec session_formation
									->join('i.client', 'c') // Jointure avec client
									->join('sf.formation', 'formation') // Jointure avec formation                                    
									->where('c.id = :IdClient')
									->setParameter('IdClient', $idClient)
									->getQuery()
									->getResult();
	}
}
