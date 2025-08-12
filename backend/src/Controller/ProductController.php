<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\SupplierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/products')]
#[IsGranted('ROLE_USER')]
class ProductController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository,
        private SupplierRepository $supplierRepository
    ) {}

    #[Route('/', name: 'app_products')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $search = $request->query->get('search');
        $categoryId = $request->query->get('category');
        $stockStatus = $request->query->get('stock_status');
        
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        // Récupération des produits avec filtres
        $criteria = ['isActive' => true];
        
        if ($search) {
            $criteria['search'] = $search;
        }
        
        if ($categoryId) {
            $criteria['category'] = $categoryId;
        }
        
        if ($stockStatus) {
            $criteria['stockStatus'] = $stockStatus;
        }
        
        $products = $this->productRepository->findByFilters($criteria, $limit, $offset);
        $totalProducts = $this->productRepository->countByFilters($criteria);
        
        // Calcul de la pagination
        $totalPages = ceil($totalProducts / $limit);
        $pagination = [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalProducts,
            'startItem' => $offset + 1,
            'endItem' => min($offset + $limit, $totalProducts),
            'pages' => range(max(1, $page - 2), min($totalPages, $page + 2))
        ];
        
        // Récupération des catégories pour les filtres
        $categories = $this->categoryRepository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_products_new')]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request): Response
    {
        $product = new Product();
        
        if ($request->isMethod('POST')) {
            $product->setName($request->request->get('name'));
            $product->setDescription($request->request->get('description'));
            $product->setSku($request->request->get('sku'));
            $product->setBarcode($request->request->get('barcode'));
            $product->setUnitPrice((float) $request->request->get('unitPrice', 0));
            $product->setCostPrice((float) $request->request->get('costPrice', 0));
            $product->setMinStockLevel((int) $request->request->get('minStockLevel', 0));
            $product->setMaxStockLevel((int) $request->request->get('maxStockLevel', 1000));
            $product->setCurrentStock((int) $request->request->get('currentStock', 0));
            $product->setUnit($request->request->get('unit', 'pièce'));
            
            if ($request->request->get('category')) {
                $category = $this->categoryRepository->find($request->request->get('category'));
                $product->setCategory($category);
            }
            
            if ($request->request->get('supplier')) {
                $supplier = $this->supplierRepository->find($request->request->get('supplier'));
                $product->setSupplier($supplier);
            }
            
            $this->entityManager->persist($product);
            $this->entityManager->flush();
            
            $this->addFlash('success', 'Produit créé avec succès !');
            return $this->redirectToRoute('app_products');
        }
        
        $categories = $this->categoryRepository->findAll();
        $suppliers = $this->supplierRepository->findAll();

        return $this->render('product/new.html.twig', [
            'categories' => $categories,
            'suppliers' => $suppliers,
        ]);
    }

    #[Route('/{id}', name: 'app_products_show')]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_products_edit')]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Product $product): Response
    {
        if ($request->isMethod('POST')) {
            $product->setName($request->request->get('name'));
            $product->setDescription($request->request->get('description'));
            $product->setSku($request->request->get('sku'));
            $product->setBarcode($request->request->get('barcode'));
            $product->setUnitPrice((float) $request->request->get('unitPrice', 0));
            $product->setCostPrice((float) $request->request->get('costPrice', 0));
            $product->setMinStockLevel((int) $request->request->get('minStockLevel', 0));
            $product->setMaxStockLevel((int) $request->request->get('maxStockLevel', 1000));
            $product->setCurrentStock((int) $request->request->get('currentStock', 0));
            $product->setUnit($request->request->get('unit', 'pièce'));
            
            if ($request->request->get('category')) {
                $category = $this->categoryRepository->find($request->request->get('category'));
                $product->setCategory($category);
            }
            
            if ($request->request->get('supplier')) {
                $supplier = $this->supplierRepository->find($request->request->get('supplier'));
                $product->setSupplier($supplier);
            }
            
            $this->entityManager->flush();
            
            $this->addFlash('success', 'Produit modifié avec succès !');
            return $this->redirectToRoute('app_products');
        }
        
        $categories = $this->categoryRepository->findAll();
        $suppliers = $this->supplierRepository->findAll();

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'categories' => $categories,
            'suppliers' => $suppliers,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_products_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Product $product): Response
    {
        $product->setIsActive(false);
        $this->entityManager->flush();
        
        $this->addFlash('success', 'Produit supprimé avec succès !');
        return $this->redirectToRoute('app_products');
    }
}
