<?php
include('fpdf/fpdf.php');
class pdfFac extends FPDF{
   //Cabecera de página
   function Header()
   {
      header("Content-Type: text/html; charset=iso-8859-1 ");
     $this->Image('./imagenes/rufo.png',10,8,50);
     $this->Cell(60,35,'',0,0,'C');
     $this->SetFont('Arial','B',12);
   }
   function startPage($fecha){
     $this->AddPage();
     $this->Cell(60,35,"Fecha: ".$fecha,0,1,'C');
     $this->SetFont('Arial','B',16);
     $this->Cell(100,10,"Producto",0,0,"C");
     $this->Cell(30,10,"Precio",0,1,"C");
   }
   function insertaTupla($nombre,$precio){
     //TODO añadir la imagen a insertaTupla
       $this->Cell(100,10,$nombre,0,0,"C");
       $this->Cell(30,10,$precio,0,1,"C");
   }

   function insertaTotal($narticulos,$precio){
     $this->ln();
     $this->Cell(100,10,"Total de articulos: ".$narticulos,0,0,"C");
     $this->Cell(30,10,$precio." ".chr(128),0,1,"C");
   }
   function Footer()
   {
     $this->SetY(-10);
     $this->SetFont('Arial','I',8);
     $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
   }
}
?>
