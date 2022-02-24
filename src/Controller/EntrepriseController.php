<?php

namespace App\Controller;

use App\Form\ClientType;
use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EntrepriseController extends AbstractController
{
    //constructeur 
    public function __construct(private EntityManagerInterface $em, private EntrepriseRepository $entrepriseRepo){}


    #[Route('/entreprise', name: 'entreprise.index')]
    public function index(): Response
    {
        //recuperer toutes les entreprises se trouvant dans la BDD
        $entreprises = $this->entrepriseRepo->findAll();

        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises,
        ]);
    }

    #[Route('/entreprise/new', name: 'entreprise.create')]
    public function create(Request $request): Response
    {
        //creation de l'objet \App\Entity\Entreprise
        $entreprise = new Entreprise();

        //creation du formulaire
        $form = $this->createForm(EntrepriseType::class,$entreprise);

        //analyse la requete
        $form->handleRequest($request);

        //verifie si le formulaire est envoyé et qu'il est valide
        if($form->isSubmitted() && $form->isValid())
        {
            //
            $this->em->persist($entreprise);
            $this->em->flush();

            //redirection
            return $this->redirectToRoute('entreprise.index');
        }
 
        return $this->render('entreprise/new.html.twig',[
            'form' => $form->createView()
        ]);

    }


    #[Route('/entreprise/edit/{id?0}', name: 'entreprise.edit')]
    public function edit(Entreprise $entreprise = null, Request $request): Response
    {
        //verifie si l'entreprise est trouvé
        if(!$entreprise)
        {
            //redirection
            return $this->redirectToRoute('entreprise.index');
        }
        
        //creation du formulaire
        $form = $this->createForm(EntrepriseType::class,$entreprise);

        //analyse la requete
        $form->handleRequest($request);

        //verifie si le formulaire est envoyé et qu'il est valide
        if($form->isSubmitted() && $form->isValid())
        {
            //
            $this->em->flush();

            //redirection
            return $this->redirectToRoute('entreprise.index');
        }
 
        return $this->render('entreprise/edit.html.twig',[
            'form' => $form->createView(),
            'entreprise' => $entreprise
        ]);

    }


    #[Route('/entreprise/delete/{id?0}', name: 'entreprise.delete')]
    public function delete(Entreprise $entreprise = null, Request $request): Response
    {
        //verification si le csrf_token est valide
        if($this->isCsrfTokenValid('delete'.$entreprise->getId(),$request->get('_token')))
        {
            //suppression du client
            $this->em->remove($entreprise);
            $this->em->flush();
        }
        
        return $this->redirectToRoute("entreprise.index");

    }
}
