<?php
require('tfpdf.php');
require('DatabaseManager.php'); 

class PDF extends tFPDF
{
    protected $db;

    function Header()
    {/*
        $this->AliasNbPages();
        $this->AddFont('DejaVu', '', 'DejavuSans.ttf', true);
        $this->AddFont('DejaVuB', '', 'DejavuSans-Bold.ttf', true);
        $this->AddFont('DejaVuI', '', 'DejavuSans-Oblique.ttf', true);
        $this->AddFont('DejaVu', '', 'DejavuSans-BoldOblique.ttf', true);
        $this->SetFont('Dejavu', '', 8);
        $this->AddPage();
*/
        $this->Image('img/UN256.jpg', 10, 6, 30);
        $this->SetFont('DejaVu', '', 12);
        $this->Cell(65);
        $this->Cell(30, 10, 'Boltok', 1, 0, 'C');
        $this->Ln(40);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('DejaVuI', '', 8);
        $this->Cell(0, 10, 'Oldal ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function BasicTable()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "shop";
        $databaseManager = new DatabaseManager($servername, $username, $password, $dbname);
        $databaseManager->connect();
        $data = $databaseManager->getPpfData();


        $this->SetFont('DejaVuB', '', 12);
        $this->Cell(40, 10, 'Termék neve', 1, 0, 'C');
        $this->Cell(35, 10, 'Mennyiség', 1, 0, 'C');
        $this->Cell(45, 10, 'Áruház', 1, 0, 'C');
        $this->Cell(40, 10, 'Ár (Ft)', 1, 1, 'C');
        $this->SetFont('DejaVu', '', 10);
        foreach ($data as $index => $row) {
            ($index % 2) ? $this->SetTextColor(124, 235, 205) : $this->SetTextColor(255, 0, 0);
            $this->Cell(40, 10, $row['product_name'], 1, 0);
            $this->Cell(35, 10, $row['quantity'], 1, 0, 'C');
            $this->Cell(45, 10, $row['store_name'], 1, 0);
            $this->Cell(40, 10, number_format($row['price']), 1, 1, 'R');
        }
    }
}
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddFont('DejaVu', '', 'DejavuSans.ttf', true);
$pdf->AddFont('DejaVuB', '', 'DejavuSans-Bold.ttf', true);
$pdf->AddFont('DejaVuI', '', 'DejavuSans-Oblique.ttf', true);
$pdf->AddFont('DejaVu', '', 'DejavuSans-BoldOblique.ttf', true);
$pdf->SetFont('Dejavu', '', 8);
$pdf->AddPage();
$pdf->BasicTable();
$pdf->Output('F', 'C:\xampp\htdocs\project2\pdf\report.pdf');
header("Location: index.php");