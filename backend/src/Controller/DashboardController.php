<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\StockMovementRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dashboard')]
#[IsGranted('ROLE_USER')]
class DashboardController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
        private StockMovementRepository $stockMovementRepository,
        private CategoryRepository $categoryRepository
    ) {}

    #[Route('/', name: 'app_dashboard')]
    public function index(): Response
    {
        // Récupération des données pour les KPI
        $totalProducts = $this->productRepository->count(['isActive' => true]);
        $lowStockCount = $this->productRepository->countLowStockProducts();
        $totalValue = $this->productRepository->getTotalInventoryValue();
        
        // Calcul de la croissance mensuelle (exemple simplifié)
        $monthlyGrowth = $this->calculateMonthlyGrowth();
        
        // Activités récentes (exemple simplifié)
        $recentActivities = $this->getRecentActivities();

        return $this->render('dashboard/index.html.twig', [
            'totalProducts' => $totalProducts,
            'lowStockCount' => $lowStockCount,
            'totalValue' => $totalValue,
            'monthlyGrowth' => $monthlyGrowth,
            'recentActivities' => $recentActivities,
        ]);
    }

    private function calculateMonthlyGrowth(): float
    {
        // Logique simplifiée pour calculer la croissance mensuelle
        // En production, vous devriez comparer avec les données réelles
        return 12.5; // Exemple fixe
    }

    private function getRecentActivities(): array
    {
        // Exemple d'activités récentes
        // En production, vous devriez récupérer ces données depuis la base
        return [
            [
                'icon' => 'plus',
                'description' => 'Nouveau produit ajouté : Ordinateur portable Dell',
                'date' => new \DateTime('-1 hour')
            ],
            [
                'icon' => 'exchange-alt',
                'description' => 'Mouvement de stock : +50 unités pour Smartphone Samsung',
                'date' => new \DateTime('-3 hours')
            ],
            [
                'icon' => 'shopping-cart',
                'description' => 'Nouvelle commande créée : #CMD-2024-001',
                'date' => new \DateTime('-5 hours')
            ],
            [
                'icon' => 'exclamation-triangle',
                'description' => 'Alerte stock faible : Écrans LCD (5 unités restantes)',
                'date' => new \DateTime('-1 day')
            ]
        ];
    }
}
