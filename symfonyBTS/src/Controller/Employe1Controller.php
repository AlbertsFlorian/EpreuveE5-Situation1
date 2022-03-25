<?php

namespace App\Controller;
use App\Form\LoginType;
use App\Entity\Employe;
use App\Repository\InscriptionRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\FormationRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

class Employe1Controller extends AbstractController
{
    /**
    * @Route("/employe1", name="employe1")
    */
    public function index(): Response
    {
        return $this->render('employe1/index.html.twig', [
            'controller_name' => 'Employe1Controller',
        ]);
    }

    /**
    * @Route("/test", name="test")
    */
    public function loginDure(Request $request, FormationRepository $formationRepository, InscriptionRepository $inscriptionRepository)
    {  
        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $myData = $form->getData();
            // mise en variable de session de l'employé
            $session = new Session();
           if ((($myData["login"]=="toto")&& ($myData["mdp"]=="toto")) || (($myData["login"]=="titi")&& ($myData["mdp"]=="titi"))){ 
               //login employe
                if (($myData["login"]=="toto")){
                    //login avec toto
                    $session->set('employeId', 1);
                }
                else {
                    //login avec titi
                    $session->set('employeId', 3);
                }
                    $idEmp = $this->get('session')->get('employeId');
                    $employe = $this->getDoctrine()->getRepository(Employe::class)->find($idEmp);
                    return $this->render('formation/employe.html.twig', [
                        'formations' => $formationRepository->findAll(),
                        'inscriptions' =>$inscriptionRepository->findByExampleField($idEmp),
                    ]);
            }
           if (($myData["login"]=="tata")&& ($myData["mdp"]=="tata")){  
               //login DRH
                $session->set('employeId', 2);              
                return $this->redirectToRoute('inscription_index');                  
            }
        }
       return $this->render('employe1/loginDure.html.twig', array('form'=>$form->createView()));
    }

    /**
     * @Route("/new", name="employe_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $employe = new Employe();
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($employe);
            $entityManager->flush();

            return $this->redirectToRoute('employe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('employe/new.html.twig', [
            'employe' => $employe,
            'form' => $form,
        ]);
    }
}
