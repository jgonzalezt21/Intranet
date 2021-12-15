<?php

namespace App\Controller;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Bienvenido',
        ]);
    }

    /**
     * @Route("/links", name="links")
     */
    public function links(): Response
    {
        return $this->render('home/links.html.twig', [
            'controller_name' => 'Links',
        ]);
    }

    /**
     * @Route("/directorio", name="directorio")
     */
    public function directorio(): Response
    {
        $data = $this->read_xlsx('upload/directorio_fijo.xlsx');

        return $this->render('home/directorio.html.twig', [
            'controller_name' => 'Directorio Telefónico',
            'data' => $data
        ]);
    }

    public function read_xlsx($inputFileName)
    {
        $reader = new Xlsx();
        //        $reader->setInputEncoding('CP1252');
        $reader->setReadDataOnly(true);
        //$reader->setSheetIndex(0);
        $spreadsheet = $reader->load($inputFileName);

        $data = [];
        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
                $fila = [];
                foreach ($cellIterator as $cell) {
                    if ($cell !== null) {
                        $fila[] = $cell->getValue();
                    }
                }
                $data[] = $fila;
            }
        }
        return $data;
    }
}
