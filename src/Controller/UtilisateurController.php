<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use App\Form\AjoutUtilisateurType;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ModifUtilisateurType;



class UtilisateurController extends AbstractController
{




    /**
     * @Route("/modif_utilisateur/{id}", name="modif_utilisateur", requirements={"id"="\d+"})
     */
    public function modifUtilisateur(int $id,Request $request)
    {

        $em = $this->getDoctrine();
        $repoUtilisateur = $em->getRepository(Utilisateur::class);
        $utilisateur = $repoUtilisateur->find($id);
            if($utilisateur == null){
                $this->addFlash('notice',"Ce thème n'exsite pas");
                return $this->redirectToRoute('liste_utilisateurs');
            }

        $form = $this->createForm(modifUtilisateurType::class,$utilisateur);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $em = $this->getDoctrine()->getManager();              
                $em->persist($utilisateur);              
                $em->flush();
                
                if ($request->isMethod('POST')) {  
           
                    if ($form->isSubmitted() && $form->isValid()) {
                         $this->addFlash('notice','utilisateur modifié');
                    }
                    return $this->redirectToRoute('liste_utilisateurs');
                }
            }
        }
        return $this->render('utilisateur/modif_utilisateur.html.twig', [

            'form'=>$form->createView()
        ]);

        }
 


    /**
     * @Route("/ajout_utilisateur", name="ajout_utilisateur")
     */
    public function ajoutUtilisateur(Request $request)
    {$utilisateur=new Utilisateur();
        $form = $this->createForm(AjoutUtilisateurType::class, $utilisateur);

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

    /**
     * @Route("/liste_utilisateurs", name="liste_utilisateurs")
     */
    public function ListeUtilisateurs(Request $request)
    {
        $em = $this->getDoctrine();
        $repoUtilisateur = $em->getRepository(Utilisateur::class);
        $utilisateurs = $repoUtilisateur->findBy(array(),array('nom'=>'ASC'));
        
        return $this->render('utilisateur/liste_utilisateurs.html.twig', [
            'utilisateurs' => $utilisateurs

            
           
        ]);
    }
}