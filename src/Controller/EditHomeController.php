<?php

namespace App\Controller;

use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditHomeController extends AbstractController
{
    /**
     * @Route("/edit/home", name="editHome" )
     * @param Request $request
     * @return Response
     */

    public function editAboutUs(Request $request)
    {
        $formAboutUs = $this->createForm(UserType::class);
        $formAboutUs->handleRequest($request);

        if ($formAboutUs->isSubmitted() && $formAboutUs->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home');
        }

        return $this->redirectToRoute('home', [
            'formAboutUs' => $formAboutUs->createView(),
        ]);
    }

}