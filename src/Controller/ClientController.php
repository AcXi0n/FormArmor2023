<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\Query\ResultSetMapping;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\SessionFormation;
use App\Entity\Inscription;

#[Route('/client')]
class ClientController extends AbstractController
{
    // A FAIRE -----------> quand plan de formation effectué interdire les inscriptions aux autres sessions de formation

    #[Route('/espaceClient', name: 'espaceClient')]
    public function espaceClient(ManagerRegistry $doctrine)
    {
        $user = $this->getUser();
        $em = $doctrine->getManager();
        $rep = $em->getRepository(SessionFormation::class);
        $repInscription = $em->getRepository(Inscription::class);
        $lesSessions = $rep->getSessionsFormationClient(); // récupère les sessions disponibles
        $lesSessionsPasInscrivables = $repInscription->getSessionFormationRealiseClient($user->getId()); // récupères les id des sessions que le client a déjà fait
        $lesSessionPossibles = $rep->getSessionsFormationDisponiblesClient($user->getId()); // récupère les id des sessions disponibles pour le client
        return $this->render('client/listeFormationsDisponibles.html.twig', Array('lesSessionPossibles' => $lesSessionPossibles, 'lesSessionsPasInscrivables'=> $lesSessionsPasInscrivables, "lesSessions"=> $lesSessions));
    }

    #[Route('/inscriptionClient', name: 'inscriptionClient')]
    public function inscriptionClient(ManagerRegistry $doctrine) // pour l'affichage des inscriptions du client
    {
        $user = $this->getUser();
        $em = $doctrine->getManager();
        $repInscription = $em->getRepository(Inscription::class);
        $lesSessions = $repInscription->getInscriptionSessionFormationClient($user->getId()); // récupère les inscriptions du client
        return $this->render('client/listeFormationsClients.html.twig', Array("lesSessions"=> $lesSessions));
    }

    #[Route('/inscriptionSessionClient/{id}', name: 'inscriptionSessionClient')]
    public function inscriptionSessionClient(ManagerRegistry $doctrine, $id) // pour s'inscrire à une session
    {
        $user = $this->getUser();
        $em = $doctrine->getManager();
        $repInscription = $em->getRepository(Inscription::class);
        $repSessionFormation = $em->getRepository(SessionFormation::class);        
        $inscription = new Inscription();
        $inscription->setClient($user);
        $inscription->setSessionformation($repSessionFormation->find($id));
        $inscription->setDateInscription(new \DateTime());
        $inscription->setEtat("Provisoir");
        $inscription->setMessage(null);
        $inscription->setPresent(True);
        $em->persist($inscription);
        $sessionFormation = $repSessionFormation->find($id); // récup la session de formation
        $sessionFormation->setNbInscrits($sessionFormation->getNbInscrits()+1); // on ajoute 1 au nombre d'inscrit        
        $em->flush();

        return $this->redirectToRoute('inscriptionClient');
    }

    #[Route('/desinscriptionSessionClient/{id}', name: 'desinscriptionSessionClient')]
    public function desinscriptionSessionClient(ManagerRegistry $doctrine, $id) // pour se désinscrire d'une session
    {
        $user = $this->getUser();
        $em = $doctrine->getManager();
        $repInscription = $em->getRepository(Inscription::class);
        $inscription = $repInscription->find($id);
        $em->remove($inscription);
        $sessionFormation = $inscription->getSessionformation(); // récup la session de formation
        $sessionFormation->setNbInscrits($sessionFormation->getNbInscrits()-1); // on enlève 1 au nombre d'inscrit    
        $em->flush();

        return $this->redirectToRoute('inscriptionClient');
    }
}
