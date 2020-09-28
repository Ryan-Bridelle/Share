<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Theme;
use App\Form\AjoutThemeType;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ModifThemeType;



class ThemeController extends AbstractController
{
/**
     * @Route("/modif_theme/{id}", name="modif_theme", requirements={"id"="\d+"})
     */
    public function modifTheme(int $id,Request $request)
    {

        $em = $this->getDoctrine();
        $repoTheme = $em->getRepository(Theme::class);
        $theme = $repoTheme->find($id);
            if($theme == null){
                $this->addFlash('notice',"Ce thème n'exsite pas");
                return $this->redirectToRoute('liste_themes');
            }

        $form = $this->createForm(modifThemeType::class,$theme);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $em = $this->getDoctrine()->getManager();              
                $em->persist($theme);              
                $em->flush();
                
                if ($request->isMethod('POST')) {  
           
                    if ($form->isSubmitted() && $form->isValid()) {
                         $this->addFlash('notice','thème modifié');
                    }
                    return $this->redirectToRoute('liste_themes');
                }
            }
        }
        return $this->render('theme/modif_theme.html.twig', [

            'form'=>$form->createView()
        ]);

        }
        


    /**
     * @Route("/ajout_theme", name="ajout_theme")
     */
    public function ajoutTheme(Request $request)
    {
        $theme=new Theme();
        $form = $this->createForm(AjoutThemeType::class, $theme);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $em = $this->getDoctrine()->getManager();              
                $em->persist($theme);              
                $em->flush();
                
                if ($request->isMethod('POST')) {  
           
                    if ($form->isSubmitted() && $form->isValid()) {
                         $this->addFlash('notice','thème envoyé');
                    }
                    return $this->redirectToRoute('ajout_theme');
                }

            }
        }

        return $this->render('theme/ajout_theme.html.twig', [

            'form'=>$form->createView()
           
        ]);
    }
        /**
     * @Route("/liste_themes", name="liste_themes")
     */
    public function ListeThemes(Request $request)
    {
        $em = $this->getDoctrine();
        $repoTheme = $em->getRepository(Theme::class);

        if($request->get('supp')!=null){
            $theme = $repoTheme->find($request->get('supp'));
            if($theme!=null){
                $em->getManager()->remove($theme);
                $em->getManager()->flush();
            }

        }

        $themes = $repoTheme->findBy(array(),array('libelle'=>'ASC'));
        
        return $this->render('theme/liste_themes.html.twig', [
            'themes' => $themes

            
           
        ]);
    }
}
