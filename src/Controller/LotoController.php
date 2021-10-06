<?php
namespace App\Controller;

use App\Entity\Euromillions;
use App\Entity\Loto;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @Route("/loto")
 */
class LotoController extends AbstractController
{

    public function showHome(){
        return $this->render('public/loto/loto-home.html.twig');
    }

    /**
     * @Route("/tirages/{year}", name="loto-year-tirage")
     */
    public function showTirage($year, PaginatorInterface $paginator, Request $request){

        $resultByYears = [];
        $tirages = $this->getDoctrine()
            ->getRepository(Loto::class)
            ->findBy(array(), array('id' => 'asc'));
        foreach ($tirages as $tirage){
            $date = $tirage->getDate();
            if ($year == $date->format('Y')){
                array_push($resultByYears, $tirage);
            }
        }
        $pagination = $paginator->paginate(
            $resultByYears, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );
        return $this->render('public/loto/tirages-year.html.twig', [
            'tirages' => $pagination,
            'year' => $year
        ]);
    }
}