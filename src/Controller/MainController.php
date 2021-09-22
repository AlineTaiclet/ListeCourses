<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(ItemRepository $repo, Request $request): Response
    {

        $items = $repo->findAll();
        //dd($items);
        $item = new Item();

        //associe objet item au form
        $formItem = $this->createForm(ItemType::class,$item);

        //hydrater item
        $formItem->handleRequest($request);

        if($formItem->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();
            // je redirige
            return $this->redirectToRoute('home');
        }

        return $this->render('main/index.html.twig',[
            'formItem' => $formItem->createView(),
            'items' => $items
        ]);

    }
}
