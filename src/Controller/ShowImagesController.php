<?php

namespace App\Controller;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ShowImagesController extends AbstractController
{
    /**
     * @Route("/show/images", name="show_images")
     */
    public function index()
    {
        $repository = $this->getDoctrine()
            ->getRepository(Products::class);

        $products = $repository->findAll();
        //dd($products); //е-ссc в симе тотже dd() что и в ларе :)

        return $this->render('show_images/index.html.twig', [
            'products' => $products
        ]);
    }
}
