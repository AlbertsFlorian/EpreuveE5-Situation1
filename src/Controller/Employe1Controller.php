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
    * @Route("/", name="authentification")
    */
    public function loginDure(Request $request, FormationRepository $formationRepository, InscriptionRepository $inscriptionRepository)
    {  
        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // mise en variable de session de l'employé
            $session = new Session();
            $login = $form->get('login')->getViewData(); //Récupère le login rentrer
            $mdp = $form->get('mdp')->getViewData(); // Récupère le mot de passe rentrer
            $mdp = md5($mdp); // Hash le mot de passe
            $user = $this->getDoctrine()->getRepository(Employe::class)->FindBy( // Trouve si un employé correspond avec le login et le mot de passe rentrer
                [
                    'login' => $login,
                    'mdp' => $mdp
                ],
                [],
                1
            );
            if($user != null){
                $session->set('employeId', $user[0]->getId()); //Enrregistre l'id de l'employé dans la variable de session
                if($user[0]->getStatut()==2){
                    //login DRH
                   return $this->redirectToRoute('inscription_index');
                }
                else{
                    // Renvoie sur la liste des formations disponibles si le c'est un employé normal
                    return $this->render('formation/employe.html.twig', [
                        'formations' => $formationRepository->findAll(),
                        'inscriptions' =>$inscriptionRepository->findByExampleField($user[0]->getId()),
                    ]);
                }
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
