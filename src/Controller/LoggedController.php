<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LoggedController extends AbstractController
{
    /**
     * @Route("/Logged/Home", name="logged_home")
     */
    public function showLoggedHome(){
        return $this->render('logged/home.html.twig');
    }
}