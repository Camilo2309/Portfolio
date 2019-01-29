<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditHomeController extends AbstractController
{
    /**
     * @Route("/edit/home", name="edit_home")
     */
    public function editHome(Request $request)
    {
//        modification du user
        $formUser = $this->createForm(UserType::class);
        $formUser->handleRequest($request);

        if ($formUser->isSubmitted() && $formUser->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home');
        }
//        fin de modification du user

        return $this->render('editHome/index.html.twig', [
            'formUser' => $formUser->createView(),
        ]);
    }

}