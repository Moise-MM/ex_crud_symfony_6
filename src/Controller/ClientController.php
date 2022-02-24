<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{

    //constructeur
    public function __construct(private EntityManagerInterface $em, private ClientRepository $clientRepo){}

    #[Route('/', name: 'client.index')]
    public function index(): Response
    {
        //recupere tous les clients se trouvant dans la BDD
        $clients = $this->clientRepo->findAll();
        
        return $this->render('client/index.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/client/{id?0}', name: 'client.show')]
    public function show(Client $client): Response
    {
        
        return $this->render('client/show.html.twig', [
            'client' => $client,
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


    #[Route('/client/edit/{id?0}', name: 'client.edit')]
    public function edit(Client $client = null, Request $request): Response
    {
        //creation du formulaire
        $form = $this->createForm(ClientType::class,$client);

        //analyse la requete
        $form->handleRequest($request);

        //verifie si le formulaire est envoyé et qu'il est valide
        if($form->isSubmitted() && $form->isValid())
        {
            //
            $this->em->flush();

            //redirection
            return $this->redirectToRoute('client.index');
        }
 
        return $this->render('client/edit.html.twig',[
            'form' => $form->createView(),
            'client' => $client
        ]);

    }


    #[Route('/client/delete/{id?0}', name: 'client.delete')]
    public function delete(Client $client = null, Request $request): Response
    {
        //verification si le csrf_token est valide
        if($this->isCsrfTokenValid('delete'.$client->getId(),$request->get('_token')))
        {
            //suppression du client
            $this->em->remove($client);
            $this->em->flush();
        }
        
        return $this->redirectToRoute("client.index");

    }


}
