<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/', name: 'client')]
    public function index(): Response
    {
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }


    #[Route('/client/new', name: 'client.create')]
    public function create(): Response
    {
        //creation de l'objet Client
        $client = new Client();

        //creation du formulaire
        $form = $this->createForm(ClientType::class,$client);

        return $this->render('client/new.html.twig',[
            'form' => $form->createView()
        ]);

    }
}
