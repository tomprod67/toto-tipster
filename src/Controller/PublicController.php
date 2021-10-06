<?php
namespace App\Controller;

use App\Entity\Euromillions;
use App\Entity\Loto;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class PublicController extends AbstractController
{

    /**
     * @Route ("/", name="index")
     */
    public function showPublicHome(Request $request)
    {
        return $this->render('public/home.html.twig');
    }
}