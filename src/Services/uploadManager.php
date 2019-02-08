<?php
/**
 * Created by PhpStorm.
 * User: celine
 * Date: 28/01/19
 * Time: 08:58
 */

namespace App\Services;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class uploadManager extends AbstractController
{
    public function uploadFile($file)
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move($this->getParameter('upload_directory'), $fileName);
        return $fileName;
    }
}