<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Services\uploadManager;
use Doctrine\Common\Persistence\ObjectManager;
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

        $pf = new Experience();

        $title = $request->request->get('title');
        $dated = $request->request->get('dated');
        $icone = $request->files->get('icone');

        if ($request =! null && $request->isMethod('post')) {

            if (!empty($icone))
            {
                $file = $icone;
                $fileUploaded = $uploadManager->uploadFile($file);
                $pf->setIcone($fileUploaded);
            } else {
                $pf->setIcone('https://www.privacytech.fr/wp-content/uploads/professional_life-300x294.png');
            }

            $pf->setTitle($title);
            $pf->setDated($dated);
            $pf->setUser($user);

            $manager->persist($pf);
            $manager->flush();
            $this->addFlash('success', 'Tu as bien ajouté ton expérience pro !');
        }

        return $this->redirectToRoute('admin');
    }
}
