<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends AbstractController
{
    /**
     * @Route("/form", name="form")
     */
    public function index(Request $request)
    {
        $product = new Products();//модель д.б. в единств числе: Product()
        //$product->setName('Добавляемый продукт 1');
        //$product->setSlug('added-product-1');
        //$product->setDescription('Описание добавляемого продкта');
        //$product->setPrice(120);

        $form = $this->createForm(ProductType::class, $product, [
            'action' => $this->generateUrl('form'),
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() ) //&& $form->isValid()
        {
            $uploads_directory = $this->getParameter('uploads_directory');

            //это массив имен файлов (картинок)
            $files = $request->files->get('product')['img'];

            //перемещаем все из $files в $uploads_directory
            foreach ($files as $file)
            {
                $filename = 'img_' . md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($uploads_directory, $filename);
            }

            //dd($filename,$request, $product);

            /////  save to DB
            //$em = $this->getDoctrine()->getManager();
            //$em->persist($product);
            //$em->flush();

            return $this->redirectToRoute('show_aploads');
        }

        return $this->render('form/index.html.twig', [
            'product_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/uploads", name="show_aploads")
     */
    public function showUploadsImages()
    {
        $uploads_directory = $this->getParameter('uploads_directory');
        //$images = glob($uploads_directory. '/*.*');
        $images = scandir($uploads_directory, 1);

        //удалили элементы "." ".." из $images (в php7.2 они всегда последние)
        array_pop($images);
        array_pop($images);

        for($i=0;  $i < count($images); $i++)
        {
            $images[$i] = 'uploads/' . $images[$i];
        }

        $count = count($images)-1;
        //dd($images, $count);

        return $this->render('form/show_images.html.twig', [
            'images' => $images,
            'count' => $count,
        ]);
    }
}
