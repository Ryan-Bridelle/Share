<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use App\Form\AjouterUtilisateurType;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;



class UtilisateurController extends AbstractController
{
    /**
     * @Route("/ajout_utilisateur", name="ajout_utilisateur")
     */
    public function ajoutUtilisateur(Request $request)
    {$utilisateur=new Utilisateur();
        $form = $this->createForm(AjouterUtilisateurType::class, $utilisateur);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
         
                    if ($form->isSubmitted() && $form->isValid()) {
                        $utilisateur->setDateInscription(new \DateTime());
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($utilisateur);
                        $em->flush();

                         $this->addFlash('notice','utilisateur envoyé');
                    }
                    return $this->redirectToRoute('ajout_utilisateur');
                }

            }
        

        return $this->render('utilisateur/ajout_utilisateur.html.twig', [

            'form'=>$form->createView()
           
        ]);
    }
}