<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Repository\ExperienceListRepository;
use App\Repository\ExperienceRepository;
use App\Services\uploadManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @Route("admin")
 */
class ProfessionalExperiencesController extends AbstractController
{
    /**
     * @Route("add/professional-experiences", name="professional_experiences")
     * @param UserInterface $user
     * @param ObjectManager $manager
     * @param Request $request
     * @param uploadManager $uploadManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addProfessionalExperience(ObjectManager $manager,
                                              Request $request, uploadManager $uploadManager)
    {

        $user = $this->getUser();

        $pfe = new Experience();

        $title = $request->request->get('title');
        $dated = $request->request->get('dated');
        $icone = $request->files->get('icone');

        if ($request =! null && $request->isMethod('post')) {

            if (!empty($icone))
            {
                $file = $icone;
                $fileUploaded = $uploadManager->uploadFile($file);
                $pfe->setIcone($fileUploaded);
            } else {
                $pfe->setIcone('https://www.privacytech.fr/wp-content/uploads/professional_life-300x294.png');
            }

            $pfe->setTitle($title);
            $pfe->setDated($dated);
            $pfe->setUser($user);

            $manager->persist($pfe);
            $manager->flush();
            $this->addFlash('success', 'Tu as bien ajouté ton expérience pro !');
        }

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/edit/professional-experience/{id}", name="editProfessionalExperience")
     * @param Experience $id
     * @param ExperienceRepository $experienceRepository
     * @param Request $request
     * @param uploadManager $uploadManager
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editProfessionalExperience(Experience $id,
                                               ExperienceRepository $experienceRepository,
                                               Request $request,
                                               uploadManager $uploadManager,
                                               EntityManagerInterface $em) {

        $pfe = $experienceRepository->find($id);
        $oldIcone = $pfe->getIcone();
        $newIcone = $request->files->get("icone");

        $title = $request->request->get('title');
        $dated = $request->request->get('dated');

        if (isset($request) && $request->isMethod('post')) {

            if (isset($newIcone)) {
                $fileUploaded = $uploadManager->uploadFile($newIcone);
                $pfe->setIcone($fileUploaded);
            } else {
                $pfe->setIcone($oldIcone);
            }

            $pfe->setTitle($title);
            $pfe->setDated($dated);
            $em->flush();

            $this->addFlash('success', "Tu as bien modifié ton experience pro !");
        }
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/delete/experience/{id}", name="deleteExperience")
     * @param Experience $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteExperience(Experience $id) {

        $em = $this->getDoctrine()->getManager();

        $experience = $em->getRepository(Experience::class)->find($id);

        $em->remove($experience);
        $em->flush();

        return $this->redirectToRoute('admin');
    }
}
