<?php
/**
 * Created by PhpStorm.
 * User: camilo
 * Date: 30/01/19
 * Time: 16:26
 */

namespace App\Controller;


use App\Entity\Knowledge;
use App\Form\KnowledgeType;
use App\Form\UserType;
use App\Repository\KnowledgeRepository;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use App\Services\uploadManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\FormTypeInterface;


/**
 * @Route("admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin" )
     * @param UserRepository $userRepository
     * @param ProjectRepository $projectRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function admin(UserRepository $userRepository, ProjectRepository $projectRepository, KnowledgeRepository $knowledgeRepository)
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
     * @param UserInterface $user
     * @param uploadManager $uploadManager
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function editProfil(Request $request, UserInterface $user, uploadManager $uploadManager,
                               UserRepository $userRepository, EntityManagerInterface $em)
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

    /**
     * @Route("/add/knowledge", name="addKnowledge")
     * @param UserInterface $user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */

    public function addKnowledge(UserInterface $user, Request $request)
    {

        $user = $this->getUser();

        $knowledge = new Knowledge();

        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $rating = $request->request->get('rating');

        if ($request =! null && $request->isMethod('post')) {


            $uploadDir = 'uploads/';
            $filename = $_FILES['picture']['name'];
            if (!empty($filename)) {

                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                $filename = md5(uniqid()) . '.' . $extension;
                $uploadFile = $uploadDir . basename($filename);
                move_uploaded_file($_FILES['picture']['tmp_name'], $uploadFile);

                $knowledge->setPicture($filename);
                $knowledge->setDescription($description);
                $knowledge->setName($name);
                $knowledge->setRating($rating);
                $knowledge->setUser($user);

                $em = $this->getDoctrine()->getManager();
                $em->persist($knowledge);

                $em->flush();

                $this->addFlash('success', 'Tu as bien ajouté ta connaissance !');
                if (empty($filename)){
                    $this->addFlash('danger', 'Il manque la photo !');
                }
            }
        }
            return $this->redirectToRoute('admin');
    }

}