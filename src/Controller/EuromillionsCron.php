<?php
namespace App\Controller;

use App\Entity\EuromillionsStats;
use App\Entity\EuromillionsTirages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EuromillionsCron extends AbstractController
{

// CRON 2x par semaine
// On recupere le dernier tirage  sur le site fdj et on l'ajoute au bas du fichier txt
    /**
     * @Route("/em-cron", name="em-cron")
     */
    public function EuromillionCron()
    {
        $validator = $this->getLastTirageFromFdj();
        if ($validator === true) {
            $arrayTirage = $this->updateTiragesByFile();
            $this->updateStatsByTirages($arrayTirage);
            $this->addFlash('test', "mis à jour avec succès!");
            return $this->render('public/home.html.twig');
        }
        $this->addFlash('test', "Déjà à jour !");
        return $this->render('public/home.html.twig');
    }

    public function getLastTirageFromFdj()
    {
        $validator = false;
        $file = file_get_contents("file/euromillions.txt");
        $page = file('https://www.fdj.fr/jeux-de-tirage/euromillions-my-million/resultats');
        $fileArray = file('file/euromillions.txt');
        $tabNumber = [];
        $tabNc = [];
        $arr = ["janvier" => "01", "fevrier" => "02", "mars" => "03", "avril" => "04", "mai" => "05",
            "juin" => "06", "juillet" => "07", "aout" => "08", "septembre" => "09", "octobre" => "10",
            "novembre" => "11", "decembre" => "12",];
        $tabDate = [];
        foreach ($page as $index => $line) {
            $newLine = trim($line);
            $search1 = "numbers-item_num";
            $search2 = "star-num";
            $sedate = "drawing-infos_title";
            if (strpos($newLine, $search1)) {
                $out = preg_replace('~\D~', '', $newLine);
                array_push($tabNumber, $out);
            }
            if (strpos($newLine, $search2)) {
                $out = preg_replace('~\D~', '', $newLine);
                array_push($tabNc, $out);
            }
            if (strpos($newLine, $sedate)) {
                array_push($tabDate, $newLine);
            }
        }
        $d = explode(">", $tabDate[1]);
        $d = explode("<", $d[1]);
        $d = explode(" ", $d[0]);
        foreach ($arr as $monthInLetter => $monthInChiffre) {
            $month = str_replace(["é", "û", "ü"], ["e", "u", "u"], strtolower($d[4]));
            if ($month == $monthInLetter) {
                $m = $monthInChiffre;
            }
        }
        $idFile = explode(";", end($fileArray));
        $idEnd = (int)$idFile[0] + 1;
        $day = $d[3] < 10 ? "0" . $d[3] : $d[3];
        $date = $day . "/" . $m . "/" . $d[5];
        // si le tirage est deja la
        if ($date !== $idFile[1]) {
            $newTirage = $tabNumber[0] . "-" . $tabNumber[1] . "-" . $tabNumber[2] . "-" . $tabNumber[3] . "-" . $tabNumber[4] . ";" . $tabNc[0] . "-" . $tabNc[1];
            $newLi = "\n" . $idEnd . ";" . $date . ";" . $newTirage;
            $file .= $newLi;
            file_put_contents("file/euromillions.txt", $file);
            $validator = true;
        }
        return $validator;
        //return $this->redirectToRoute('euromillions_update_tirages_by_file');
    }

// On met a jour la base avec le fichier txt et la nouvelle ligne ajouté
    public function updateTiragesByFile()
    {
        $tirages = $this->getDoctrine()->getRepository(EuromillionsTirages::class)->findAll();
        $entityManager = $this->getDoctrine()->getManager();
        $file = file('file/euromillions.txt');
        $id = !empty($tirages) ? end($tirages)->getId() - 1 : 0;
        $arrayTiragesForUpdate = array_slice($file, $id);
        $arrayTirages = [];
        foreach ($arrayTiragesForUpdate as $line) {
            $newLine = str_replace("\n", "", $line);
            $expl = explode(";", $newLine);
            $date = explode("/", $expl['1']);
            $month = $date['1'];
            $year = $date['2'];
            $newDate = date_create_from_format("d/m/Y", $expl['1']);
            $numbers = explode("-", $expl['2']);
            $nc = explode("-", $expl['3']);
            $tirage = new EuromillionsTirages();
            $tirage->setNum1($numbers['0']);
            $tirage->setNum2($numbers['1']);
            $tirage->setNum3($numbers['2']);
            $tirage->setNum4($numbers['3']);
            $tirage->setNum5($numbers['4']);
            $tirage->setNumC1($nc['0']);
            $tirage->setNumC2($nc['1']);
            $tirage->setMonth($month);
            $tirage->setYear($year);
            $tirage->setDate($newDate);
            $entityManager->persist($tirage);
            array_push($arrayTirages, $tirage);
        }
        $entityManager->flush();
        return $arrayTirages;
    }

// Puis on met à jour les stats
    public function updateStatsByTirages($arrayTirages)
    {
        $em = $this->getDoctrine()->getManager();
        $occurence = array('fullTirage' => [], 'tirageSansNc' => [], 'tirageNc' => [], 'fullNumber' => [], 'numberSansNc' => [], 'numberNc' => [], 'allTiragesId' => []);
        $tirages = [];

        $tiragesAllYear = $this->getDoctrine()->getRepository(EuromillionsStats::class)->findOneBy(['year' => 'ALL']);
        if ($tiragesAllYear === null) {
            $tiragesAllYear = new EuromillionsStats();
            $tiragesAllYear->setYear('ALL');
            $tiragesAllYear->setOccurence($occurence);
            $em->persist($tiragesAllYear);
            $em->flush();
        }
        foreach ($arrayTirages as $tirage) {
            //Creation  ou récuperation year -> month ALL
            $year = $tirage->getYear();
            $month = $tirage->getMonth();
            $allTiragesYear = $this->getDoctrine()->getRepository(EuromillionsStats::class)->findOneBy(['year' => $year, 'month' => "ALL"]);
            if ($allTiragesYear === null) {
                $allTiragesYear = new EuromillionsStats();
                $allTiragesYear->setYear($year);
                $allTiragesYear->setMonth('ALL');
                $allTiragesYear->setOccurence($occurence);
                $em->persist($allTiragesYear);
                $em->flush();
                $allTiragesYear = $this->getDoctrine()->getRepository(EuromillionsStats::class)->findOneBy(['year' => $year, 'month' => "ALL"]);
            }
            $allTiragesMonth = $this->getDoctrine()->getRepository(EuromillionsStats::class)->findOneBy(['year' => $year, 'month' => $month]);
            if ($allTiragesMonth === null) {
                $allTiragesMonth = new EuromillionsStats();
                $allTiragesMonth->setYear($year);
                $allTiragesMonth->setMonth($month);
                $allTiragesMonth->setOccurence($occurence);
                $em->persist($allTiragesMonth);
                $em->flush();
                $allTiragesMonth = $this->getDoctrine()->getRepository(EuromillionsStats::class)->findOneBy(['year' => $year, 'month' => $month]);
            }

            $arrayId = $allTiragesMonth->getArrayIdTirages();
            array_push($arrayId, $tirage->getId());
            $allTiragesMonth->setArrayIdTirages($arrayId);
            $em->persist($allTiragesMonth);
            $arrayId = $allTiragesYear->getArrayIdTirages();
            array_push($arrayId, $tirage->getId());
            $allTiragesYear->setArrayIdTirages($arrayId);
            $em->persist($allTiragesYear);
            $arrayId = $tiragesAllYear->getArrayIdTirages();
            array_push($arrayId, $tirage->getId());
            $tiragesAllYear->setArrayIdTirages($arrayId);
            $em->persist($tiragesAllYear);
            $tirages[$tirage->getId()] = $tirage;
        }
        $em->flush();
        return $this->updateOccurenceStats($tirages);
    }

//Et on remet a jour les occurences


    public function updateOccurenceStats($tirages)
    {
        $allStats = $this->getDoctrine()->getRepository(EuromillionsStats::class)->findAll();
        $em = $this->getDoctrine()->getManager();
        foreach ($allStats as $stats) {
            $occurence = $stats->getOccurence();
            $arrayIdTirage = $stats->getArrayIdTirages();
            foreach ($arrayIdTirage as $id) {
                $tirage = $tirages[$id];
                $fullNumbersTirage = $tirage->getNum1() . ";" . $tirage->getNum2() . ";" . $tirage->getNum3() . ";" . $tirage->getNum4() . ";" . $tirage->getNum5() . ";" . $tirage->getNumC1() . ";" . $tirage->getNumC2();
                $numbersTirage = $tirage->getNum1() . ";" . $tirage->getNum2() . ";" . $tirage->getNum3() . ";" . $tirage->getNum4() . ";" . $tirage->getNum5();
                $numbersComplementaireTirage = $tirage->getNumC1() . ";" . $tirage->getNumC2();
                foreach (explode(";", $fullNumbersTirage) as $oneNumber) {
                    array_push($occurence['fullNumber'], $oneNumber);
                }
                foreach (explode(";", $numbersTirage) as $oneNumber) {
                    array_push($occurence['numberSansNc'], $oneNumber);
                }
                foreach (explode(";", $numbersComplementaireTirage) as $oneNumber) {
                    array_push($occurence['numberNc'], $oneNumber);
                }
                array_push($occurence['fullTirage'], $fullNumbersTirage);
                array_push($occurence['tirageSansNc'], $numbersTirage);
                array_push($occurence['tirageNc'], $numbersComplementaireTirage);
                array_push($occurence['allTiragesId'], $id);
            }
            $occurence['fullNumber'] = array_count_values($occurence['fullNumber']);
            $occurence['numberSansNc'] = array_count_values($occurence['numberSansNc']);
            $occurence['numberNc'] = array_count_values($occurence['numberNc']);
            $occurence['fullTirage'] = array_count_values($occurence['fullTirage']);
            $occurence['tirageSansNc'] = array_count_values($occurence['tirageSansNc']);
            $occurence['tirageNc'] = array_count_values($occurence['tirageNc']);
            arsort($occurence['fullNumber']);
            arsort($occurence['numberSansNc']);
            arsort($occurence['numberNc']);
            arsort($occurence['fullTirage']);
            arsort($occurence['tirageSansNc']);
            arsort($occurence['tirageNc']);
            $stats->setOccurence($occurence);
            $nb = count($occurence['allTiragesId']);
            $stats->setNbTirages($nb);
            $em->persist($stats);
        }
        $em->flush();
    }

// parse fichier loto de base téléchargeable
    public function parseFile()
    {
        $file = file('file/euro1.txt');
        foreach ($file as $index => $value) {
            $expl = explode(";", $value);
            $newValue = str_replace("\n", "", $value);
            $file[$expl['1']] = $newValue;
            unset($file[$index]);
        }
        ksort($file);
        $i = 1;
        foreach ($file as $line) {
            $ex = (explode(";", $line));
            $newLine = $i . ";" . $ex['2'] . ";" . $ex['3'] . ";" . $ex['4'];
            $current = file_get_contents("file/neweuro.txt");
            $current .= $newLine;
            file_put_contents("file/neweuro.txt", $current . "\n");
            $i++;
        }
        return $this->render('public/home.html.twig');
    }
}
