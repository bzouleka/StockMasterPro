<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // Si l'utilisateur est connecté, rediriger vers le tableau de bord
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard');
        }

        // Sinon, rediriger vers la page de connexion
        return $this->redirectToRoute('app_login');
    }
}
