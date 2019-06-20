<?php
/**
 * Created by PhpStorm.
 * User: camilo
 * Date: 08/02/19
 * Time: 20:57
 */

namespace App\Controller;

use App\Entity\Knowledge;
use App\Repository\KnowledgeRepository;
use App\Services\uploadManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @Route("admin")
 */
class KnowledgeController extends AbstractController
{

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


    /**
     * @Route("/edit/knowledge/{id}", name="editKnowledge")
     * @param Knowledge $id
     * @param Request $request
     * @param KnowledgeRepository $knowledgeRepository
     * @param uploadManager $uploadManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */

    public function editKnowledge(Knowledge $id, Request $request, KnowledgeRepository $knowledgeRepository, uploadManager $uploadManager, EntityManagerInterface $em)
    {

        $knowledge = $knowledgeRepository->find($id);

        $oldPicture = $knowledge->getPicture();
        $newPicture = $request->files->get("picture");
        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $rating = $request->request->get('rating');

//        $request->query->get($oldPicture, $newPicture);

        if (!empty($request) && $request->isMethod('post'))
        {

            if (!empty($newPicture))
            {
                $file = $newPicture;
                $fileUploaded = $uploadManager->uploadFile($file);
                $knowledge->setPicture($fileUploaded);
            } else {
                $knowledge->setPicture($oldPicture);
            }

            $knowledge->setDescription($description);
            $knowledge->setName($name);
            $knowledge->setRating($rating);

            $em->flush();

            $this->addFlash('success', 'Tu as bien modifié ta connaissance !');
        }
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/delete/knowledge/{id}", name="deleteKnowledge")
     * @param Knowledge $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteKnowledge(Knowledge $id) {

        $em = $this->getDoctrine()->getManager();

        $knowledge = $em->getRepository(Knowledge::class)->find($id);

        $em->remove($knowledge);
        $em->flush();

        return $this->redirectToRoute('admin');

    }
}