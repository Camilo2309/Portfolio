<?php

namespace App\Controller;

use App\Entity\Knowledge;
use App\Entity\Project;
use App\Repository\KnowledgeRepository;
use App\Repository\ProjectRepository;
use App\Services\uploadManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Cache\CacheException;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("admin")
 */
class ProjectController1 extends AbstractController
{
    /**
     * @Route("/add/project/", name="addProject", methods={"GET","POST"})
     * @param Project|null $project
     * @param Request $request
     * @param ObjectManager $manager
     * @param uploadManager $uploadManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addProject(Project $project = null, Request $request, ObjectManager $manager, uploadManager $uploadManager)
    {

        $project = new Project();

        $title = $request->request->get('title');
        $content = $request->request->get('content');
        $url = $request->request->get('url');
        $newPicture = $request->files->get("picture");

//        $knowledges = $request->request->get('technology');

//        dd( $request->request->all());

//        dd($knowledges);
//        $manager = $this->getDoctrine()->getManager();
//        $knowledges = $manager->getRepository(Knowledge::class)
//            ->findAll();

      if (!empty($request) && $request->isMethod('post')){

            if (!empty($newPicture))
            {
                $file = $newPicture;
                $fileUploaded = $uploadManager->uploadFile($file);
                $project->setPicture($fileUploaded);
            }

            $project->setTitle($title);
            $project->setContent($content);
            $project->setUrl($url);

            $manager->persist($project);
            $manager->flush();
            $this->addFlash('success', 'Tu as bien ajouté ton projet !');

        }

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/edit/project/{id}", name="editProject")
     * @param Project $id
     * @param Request $request
     * @param ProjectRepository $projectRepository
     * @param uploadManager $uploadManager
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editProject(Project $id, Request $request,
                                ProjectRepository $projectRepository, uploadManager $uploadManager,
                                EntityManagerInterface $em)
    {

        $project = $projectRepository->find($id);
        $oldPicture = $project->getPicture();
        $newPicture = $request->files->get("picture");

        $title = $request->request->get('title');
        $content = $request->request->get('content');
        $url = $request->request->get('url');

        if (isset($request) && $request->isMethod('post'))
        {
            if (isset($newPicture))
            {
                $fileUploaded = $uploadManager->uploadFile($newPicture);
                $project->setPicture($fileUploaded);
            } else {
                $project->setPicture($oldPicture);
            }

            $project->setTitle($title);
            $project->setContent($content);
            $project->setUrl($url);

            $em->flush();

            $this->addFlash('success', "Tu as bien modifié ton projet !");
        }
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/delete/project/{id}", name="deleteProject")
     * @param Project $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteProject(Project $id) {

        $em = $this->getDoctrine()->getManager();

        $project = $em->getRepository(Project::class)->find($id);

        $em->remove($project);
        $em->flush();

        return $this->redirectToRoute('admin');
    }
}
