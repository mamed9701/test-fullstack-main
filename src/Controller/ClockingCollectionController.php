<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Clocking;
use App\Entity\User;
use App\Form\CreateClockingType;
use App\Form\CreateUsersClockingType;
use App\Form\CreateUserType;
use App\Repository\ClockingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Clock\Clock;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/clockings')]
class ClockingCollectionController extends
    AbstractController
{

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('/create', name: 'app_Clocking_createUser', methods: [
        'GET',
        'POST',
    ])]
    public function createClocking(
        EntityManagerInterface $entityManager,
        Request                $request,
    ) : Response {

        // Création d'une nouvelle instance User
        $user = new User();
        $form = $this->createForm(CreateUserType::class, $user);
        $form->handleRequest($request);

        //Si le formulaire est soumis et valide
        if($form->isSubmitted() && $form->isValid()) {
            
            //Récupération de la donné du champ date
            $date = $form->get('date')->getData();

            // Génération d'un identifiant provisoire unique 
            $temporaryId = uniqid();

            //Définition de la matricule du collaborateur
            $user->setMatricule($user->getFirstName() . "_" . $user->getLastName() . "_" . $temporaryId);

            // Persistence l'objet user
            $entityManager->persist($user);

            // Création et persistence de l'objet Clocking
            foreach ($user->getClockings() as $clocking) {
                $clocking->setDate($date);
                $clocking->addClockingUser($user);
                $entityManager->persist($clocking);
            }

            //Enregistrement des données
            $entityManager->flush();
            
            // Maintenant que l'objet User est persisté avec un Vrai Id, on définit la matricule à nouveau 
            $user->setMatricule($user->getFirstName() . "_" . $user->getLastName() . "_" . $user->getId());
            
            // Mise à jour des données avec la nouvelle matricule
            $entityManager->flush();

            return $this->redirectToRoute('app_Clocking_list');
        }

        $formView = $form->createView();

        return $this->render('app/Clocking/create.html.twig', [
            'form' => $formView,
        ]);
    }


    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('/createUsers', name: 'app_Clocking_CreateUsers', methods: [
        'GET',
        'POST',
    ])]
    public function createClockingUsers(
        EntityManagerInterface $entityManager,
        Request                $request,
    ) : Response {


        $clocking = new Clocking();
        $form = $this->createForm(CreateUsersClockingType::class, $clocking);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            // Get date Data from the form
            $date = $form->get('date')->getData();

            // Setting Clocking date
            $clocking->setDate($date);

            //Setting Clocking duration to 7 hours for default
            $clocking->setDuration(7);

            // Manage Users to add Clocking
            foreach ($clocking->getClockingUsers() as $user) {
                $user->addClocking($clocking);
                $entityManager->persist($user);
            }
        
            $entityManager->persist($clocking);
            $entityManager->flush();


            return $this->redirectToRoute('app_Clocking_list');
        }

        $formView = $form->createView();

        return $this->render('app/Clocking/createUsers.html.twig', [
            'form' => $formView,
        ]);
    }

    /**
     * @param \App\Repository\ClockingRepository $clockingRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('/', name: 'app_Clocking_list', methods: ['GET'])]
    public function listClockings(ClockingRepository $clockingRepository) : Response
    {
        $clockings = $clockingRepository->findAll();

        return $this->render('app/Clocking/list.html.twig', [
            'clockings' => $clockings,
        ]);
    }
}
