<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{

    //constructeur
    public function __construct(private EntityManagerInterface $em){}

    #[Route('/', name: 'client.index')]
    public function index(): Response
    {
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }


    #[Route('/client/new', name: 'client.create')]
    public function create(Request $request): Response
    {
        //creation de l'objet Client
        $client = new Client();

        //creation du formulaire
        $form = $this->createForm(ClientType::class,$client);

        //analyse la requete
        $form->handleRequest($request);

        //verifie si le formulaire est envoyé et qu'il est valide
        if($form->isSubmitted() && $form->isValid())
        {
            //
            $this->em->persist($client);
            $this->em->flush();

            //redirection
            return $this->redirectToRoute('client.index');
        }

        return $this->render('client/new.html.twig',[
            'form' => $form->createView()
        ]);

    }
}
