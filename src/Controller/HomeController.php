<?php

namespace App\Controller;


use App\Entity\Contact;
use App\Entity\User;
use App\Form\ContactType;
use App\Form\UserType;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use App\Services\EditHomeService;
use Doctrine\ORM\EntityManager;
use ReCaptcha\ReCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(UserRepository $userRepository, ProjectRepository $projectRepository,
                          Request $request, \Swift_Mailer $mailer) : Response
    {
        $user = $userRepository->findUser('bolanos.camilo2309@gmail.com');
        $projects = $projectRepository->findAll();

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        $name = $form->getData()->getFullName();


        if ($form->isSubmitted() && $form->isValid())
        {

            $recaptcha = new ReCaptcha("6LddQ40UAAAAALGuMKbwCuPg7LeP2qC7Saoy54ut");
            $resp = $recaptcha->verify($request->get("g-recaptcha-response"));

            if ($resp->isSuccess()) {

                $message = (new \Swift_Message("Prise de contact de la part de $name"))
                    ->setFrom(getenv('MAILER_USERNAME'))
                    ->setTo(getenv('MAILER_USERNAME'))
                    ->setBody($this->render('email/contact.html.twig', [
                        'contact' => $contact
                    ]), 'text/html');
                $mailer->send($message);

                $this->addFlash('success', 'Votre courriel a bien été envoyé, je vous répondrai dans les plus brefs délais.');

            } else {

                // Dans le cas de l'erreur.
                if(in_array("missing-input-response", $resp->getErrorCodes())){
                    $this->addFlash('danger', "Valadation robot non bonne");
                }

            }

        };


        return $this->render('home/index.html.twig', [
            'user' => $user,
            'projects' => $projects,
            'form' => $form->createView(),

        ]);
    }



}
