<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(): Response
    {
        // On récupère l'utilisateur authentifié
		$user = $this->getUser();
        
        if($user && (in_array("ROLE_ADMIN", $user->getRoles()))) // verif si admin 
        {
            return $this->redirectToRoute('admin');
        }
        return $this->render('accueil/index.html.twig');
    }
}
