<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VipController extends AbstractController
{

    public function showPublicHome(){
        return $this->render('public/home.html.twig');
    }
}