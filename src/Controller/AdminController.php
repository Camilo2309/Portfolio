<?php
/**
 * Created by PhpStorm.
 * User: camilo
 * Date: 30/01/19
 * Time: 16:26
 */

namespace App\Controller;


use App\Repository\KnowledgeRepository;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin" )
     * @param UserRepository $userRepository
     * @param ProjectRepository $projectRepository
     * @param KnowledgeRepository $knowledgeRepository
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function admin(UserRepository $userRepository,
                          ProjectRepository $projectRepository,
                          KnowledgeRepository $knowledgeRepository)
    {

        $user = $userRepository->findUser('bolanos.camilo2309@gmail.com');
        $projects = $projectRepository->findAll();
        $knowledge = $knowledgeRepository->findAll();

        return $this->render('adminHome/index.html.twig', [
            'user' => $user,
            'projects' => $projects,
            'knowledge' => $knowledge,
        ]);
    }

    /**
     * @Route("/update/profil", name="editProfil", methods={"POST"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function editProfil(Request $request, UserRepository $userRepository, EntityManagerInterface $em)
    {

        $user = $userRepository->findUser('bolanos.camilo2309@gmail.com');

        $lastName = $request->request->get('lastName');
        $firstName = $request->request->get('firstName');
//        $picture = $request->request->get('picture');
        $titlePresentation = $request->request->get('titlePresentation');
        $presentation = $request->request->get('presentation');


        $uploadDir = 'uploads/';
        $filename = $_FILES['picture']['name'];
        if (!empty($filename)) {

            $extension = pathinfo($filename, PATHINFO_EXTENSION); // on récupère l'extension, exemple "pdf"
            $filename = md5(uniqid()) . '.' . $extension; // concatène nom de fichier unique avec l'extension
            // on génère un nom de fichier à partir du nom de fichier sur le poste du client (mais vous pouvez générer ce nom autrement si vous le souhaitez)
            $uploadFile = $uploadDir . basename($filename);
            // on déplace le fichier temporaire vers le nouvel emplacement sur le serveur. Ca y est, le fichier est uploadé
            move_uploaded_file($_FILES['picture']['tmp_name'], $uploadFile);

            $user->setPicture($filename);

        }

        $user->setLastName($lastName);
        $user->setFirstName($firstName);
        $user->setTitlePresentation($titlePresentation);
        $user->setPresentation($presentation);

        $em->flush();

        return $this->redirectToRoute('admin');
    }

}