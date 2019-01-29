<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditHomeController extends AbstractController
{
    /**
     * @Route("/edit/home", name="edit_home")
     */
    public function editHome(Request $request, User $user): Response
    {
//        modification du user
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('edit_home', [
                'id' => $user->getId(),
            ]);
        }
//        fin de modification du user

        return $this->render('editHome/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

}