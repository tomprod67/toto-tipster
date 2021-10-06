<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/logged/fdjGames")
 */
class PrivateController extends AbstractController
{
    /**
     * @Route("/", name="private_game")
     */
    public function showPublicHome(){
        return $this->render('private/jeuxFdj.html.twig');
    }
}