<?php
namespace App\Controller;

use App\Entity\EuromillionsTirages;
use App\Entity\EuromillionsStats;
use App\Repository\EuromillionsStatsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

//@Security("is_granted('ROLE_VIP') and is_granted('ROLE_SUPER_VIP')")
/**
 *
 * @Route("/logged/games/euromillions")
 */
class EuromillionsController extends AbstractController
{

    /**
     * @Route("/probability",name="euromillions_proba")
     */
    public function probability(EuromillionsStatsRepository $repo){
        $tirages = $this->getDoctrine()->getRepository(EuromillionsTirages::class)->findAll();
        $array = ['sinceNumber12' => array_slice($tirages, 939),'beforeNumber12' => array_slice($tirages, 0, 939)];
        $ncProba = [];
        $nProba = [];
        foreach ($array['sinceNumber12'] as $objectTirage){
            array_push($ncProba, $objectTirage->getNumC1());
            array_push($ncProba, $objectTirage->getNumC2());
            array_push($nProba, $objectTirage->getNum1());
            array_push($nProba, $objectTirage->getNum2());
            array_push($nProba, $objectTirage->getNum3());
            array_push($nProba, $objectTirage->getNum4());
            array_push($nProba, $objectTirage->getNum5());
        };
        foreach ($array['beforeNumber12'] as $objectTirage){
            array_push($nProba, $objectTirage->getNum1());
            array_push($nProba, $objectTirage->getNum2());
            array_push($nProba, $objectTirage->getNum3());
            array_push($nProba, $objectTirage->getNum4());
            array_push($nProba, $objectTirage->getNum5());
        };

        $ncProba = array_count_values($ncProba);
        $nProba = array_count_values($nProba);
        $arrayNcprobaMoyenne = [];
        foreach ($ncProba as $number => $occ){
            $arrayNcprobaMoyenne[$number] = round($occ / 240, 3);
        }
        arsort($arrayNcprobaMoyenne);
        dd($arrayNcprobaMoyenne);
    }
    
    /**
     * @Route("/", name="euromillions_home_all")
     */
    public function showHomeAll(PaginatorInterface $paginator, Request $request)
    {
        $arrayYears = $this->getFirstAndLastYear();

        $tirages = $this->getDoctrine()->getRepository(EuromillionsTirages::class)->findAll();
        $originStats = $this->getDoctrine()->getRepository(EuromillionsStats::class)->findOneBy(['year' => 'ALL']);
        $stats = $this->addParamForReadable($originStats);

        $paginationTirages = $paginator->paginate(
            $tirages, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );
        $data = ['stats' => $stats, 'tirages' => $paginationTirages, 'firstYear' => $arrayYears['firstYear'], 'lastYear' => $arrayYears['lastYear']];
        return $this->render('private/euromillions/euromillions-home-all.html.twig', ['data' => $data]);
    }

    /**
     * @Route("/{year}", name="euromillions_year")
     */
    public function showYear($year, PaginatorInterface $paginator, Request $request)
    {
        $arrayYears = $this->getFirstAndLastYear();
        $arrayData = $this->getFirstAndLastMonth($year);

        $paginationTirages = $paginator->paginate(
            $arrayData['tirages'], /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        $originStats = $this->getDoctrine()->getRepository(EuromillionsStats::class)->findOneBy(['year' => $year]);
        $stats = $this->addParamForReadable($originStats);
        $data = ['stats' => $stats, 'tirages' => $paginationTirages,'currentYear' => $year, 'firstYear' => $arrayYears['firstYear'], 'lastYear' => $arrayYears['lastYear'], 'arrayMonth' => $arrayData['arrayMonth']];
        return $this->render('private/euromillions/tirages-year.html.twig', [
            'data' => $data
        ]);
    }

    /**
     * @Route("/{year}/{month}", name="euromillions_month")
     */
    public function showMonth($year, $month, PaginatorInterface $paginator, Request $request)
    {
        $arrayYears = $this->getFirstAndLastYear();
        $arrayData = $this->getFirstAndLastMonth($year);
        $monthLetter = $this->convertMonth($month);

        $tirages = $this->getDoctrine()
            ->getRepository(EuromillionsTirages::class)
            ->findBy(['year' => $year, 'month' => $month]);

        $paginationTirages = $paginator->paginate(
            $tirages, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        $originStats = $this->getDoctrine()->getRepository(EuromillionsStats::class)->findOneBy(['year' => $year, 'month' => $month]);
        $stats = $this->addParamForReadable($originStats);

        $data = ['stats' => $stats,'tirages'=>$paginationTirages, 'currentYear' => $year, 'currentMonthInLetter' => $monthLetter, 'firstYear' => $arrayYears['firstYear'], 'lastYear' => $arrayYears['lastYear'], 'arrayMonth' => $arrayData['arrayMonth']];
        return $this->render('private/euromillions/tirages-month.html.twig', ['data' => $data]);
    }

    public function addParamForReadable($stats)
    {
        $tabOcc = $stats->getOccurence();
        foreach ($tabOcc as $name => $array) {
            if ($name == 'fullTirage' || $name == 'tirageSansNc' || $name == 'tirageNc') {
                $tabOcc[$name] = ['tirages' => $array];
            }
            elseif ($name == 'fullNumber' || $name == 'numberSansNc' || $name == 'numberNc'){
                $tabOcc[$name] = ['numbers' => $array];
            }
        }
        foreach ($tabOcc as $name => $value) {
            if (array_key_exists("tirages", $value)) {
                foreach ($value["tirages"] as $tirage => $nbOcc) {
                    if ($nbOcc < 2) {
                        unset($tabOcc[$name]["tirages"][$tirage]);
                    } else {
                        $expl = explode(";", $tirage);
                        if (count($expl) == 7) {
                            $numbersC = array_slice($expl, -2);
                            $numbers = array_slice($expl, 0, 5);
                            $newTirage = ['numbers' => $numbers, 'numbersC' => $numbersC, 'occ' => $nbOcc];
                        }
                        if (count($expl) == 5) {
                            $newTirage = ['numbers' => $expl, 'occ' => $nbOcc];
                        }
                        if (count($expl) == 2) {
                            $newTirage = ['numbersC' => $expl, 'occ' => $nbOcc];
                        }
                        unset($tabOcc[$name]["tirages"][$tirage]);
                        array_push($tabOcc[$name]["tirages"], $newTirage);
                    }
                }
            }
            if ($name == 'allTiragesId'){
                unset($tabOcc[$name]);
            }
        }
        $stats->setOccurence($tabOcc);
        $stats->setArrayIdTirages([]);
        return $stats;
    }
    public function getFirstAndLastYear(){
        $tirages = $this->getDoctrine()->getRepository(EuromillionsTirages::class)->findBy([], array('id' => 'desc'));
        $firstYear = end($tirages)->getYear();
        $lastYear = $tirages[0]->getYear();
        $array = ['firstYear' => $firstYear, 'lastYear' => $lastYear];
        return $array;
    }

    public function getFirstAndLastMonth($year){
        $tirages = $this->getDoctrine()->getRepository(EuromillionsTirages::class)->findBy(['year' => $year], array('id' => 'desc'));
        $months = ["janvier" => "01", "février" => "02", "mars" => "03", "avril" => "04", "mai" => "05",
            "juin" => "06", "juillet" => "07", "août" => "08", "septembre" => "09", "octobre" => "10",
            "novembre" => "11", "décembre" => "12",];

        $startMonth = end($tirages)->getMonth();
        $endMonth = $tirages[0]->getMonth();
        $arrMonth = [];
        while ((int)$startMonth <= (int)$endMonth){
            foreach ($months as $monthLetter => $month) {
                if ($startMonth == $month) {
                    $arrayOneMonth = [$monthLetter => $month];
                    array_push($arrMonth, $arrayOneMonth);
                }
            }
            $startMonth ++;
        };
        $array = ['tirages' => $tirages,'arrayMonth' => $arrMonth];
        return $array;
    }

    public function convertMonth($month){
        $months = ["janvier" => "01", "février" => "02", "mars" => "03", "avril" => "04", "mai" => "05",
            "juin" => "06", "juillet" => "07", "août" => "08", "septembre" => "09", "octobre" => "10",
            "novembre" => "11", "décembre" => "12",];
        foreach ($months as $mLetter => $mChiffre){
            if ($month == $mLetter){
                $newMonth = $mChiffre;
            }
            if ($month == $mChiffre){
                $newMonth = $mLetter;
            }
        }
        return $newMonth;
    }



}