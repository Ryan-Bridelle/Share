<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Theme;
use App\Form\AjoutThemeType;
use Symfony\Component\HttpFoundation\Request;


class ThemeController extends AbstractController
{
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
        $themes = $repoTheme->findBy(array(),array('libelle'=>'ASC'));
        
        return $this->render('theme/liste_themes.html.twig', [
            'themes' => $themes

            
           
        ]);
    }
}
