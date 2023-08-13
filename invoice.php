<?php
include "core/autoload.php";
include "admin/core/app/model/BuyData.php";
include "admin/core/app/model/BuyProductData.php";
include "admin/core/app/model/ProductData.php";
include "admin/core/app/model/ClientData.php";
include "admin/core/app/model/UnitData.php";
include "admin/core/app/model/ConfigurationData.php";
include "admin/core/app/model/CouponData.php";
include "admin/core/app/model/ShipData.php";

require('fpdf/fpdf.php');






class PDF extends FPDF
{
// Cargar los datos
function LoadData($file)
{
    // Leer las líneas del fichero
}

function Header(){
$title = ConfigurationData::getByPreffix("general_main_title")->val;
    $service = BuyData::getByCode($_GET["code"]);
        $this->SetFont('Arial','B',15);
        $this->setX(20);

// $this->Line(20, 6, 195, 6); // 20mm from each edge

//$this->Line(20, 260.5, 200, 260.5); // 20mm from each edge
//$this->Line(20, 261.5, 200, 261.5); // 20mm from each edge
// $this->Line(20, 262.5, 200, 262.5); // 20mm from each edge

        $this->SetFont('Arial','B',20);
        $this->Cell( 180, 30, $title, 0, 0, 'L', false );

        $this->Ln();
        $this->setY(7);
        $this->SetFont('Arial','B',10);
        $this->setX(165-5);

}
// Tabla simple
function ImprovedTable($data)
{
$iva = ConfigurationData::getByPreffix("general_iva")->val;
$iva_txt = ConfigurationData::getByPreffix("general_iva_txt")->val;
$coin = ConfigurationData::getByPreffix("general_coin")->val;
if($coin=="€"){ $coin=chr(128); }
$service = BuyData::getByCode($_GET["code"]);
$ship = ShipData::getById($service->ship_id);
$products = BuyProductData::getAllByBuyId($service->id);
   $client = ClientData::getById($service->client_id);
   $image1 = "./admin/img/yafar.jpg";
        $this->setY(31);
        $this->setX(180);
        $this->Image($image1 , 140 ,10, 35 , 38,'JPG', './');
        $this->setY(31);
        $this->setX(20);
        $this->SetFont('Arial','B',14);
        $this->setY(33);
        $this->setX(20);
        $this->setY(40);
        $this->setX(20);
        $this->SetFont('Arial','B',14);
//         $this->Cell(0,35,"",1);
        $this->setY(38);
        $this->setX(20);
        $this->Cell(0,10," NOMBRE:           ".utf8_decode($client->getFullname()));
        $this->setY(43);
        $this->setX(20);
        $this->Cell(0,10," DIRECCION:       ".utf8_decode($client->address));
        $this->setY(48);
        $this->setX(20);
        $this->Cell(0,10," TEL:                    ".$client->phone);
        $this->setY(40);
        $this->setX(20);

        $this->setY(80);
        $this->setX(20);
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10," DETALLES DE LA COMPRA ");
        $this->SetFont('Arial','B',10);

        $starty=0;



$total = 0;


//    $inventary_item = InventaryData::getAllBySII($service->id,$ii->id);
         $this->setY(95+$starty);
         $this->setX(20);
//// omito el super trabajo
///         $this->Cell(0,10,strtoupper($w->getWork()->name));
         $this->setY(95+$starty);
         $this->setX(65);
//         $starty+=5;
//////////////////////// materiales
if(count($products)){
         $starty+=5;
    foreach($products as $itemx){
        $item = $itemx->getProduct();
        $mtx=null;
            
         $this->setY(95+$starty);
         $this->setX(20);
         $this->Cell(0,10,strtoupper($itemx->q),"LTRB");
         $this->setX(30);
         $this->Cell(0,10,strtoupper($item->getUnit()->name),"LTRB");
         $this->setX(50);
         $this->Cell(0,10,strtoupper($item->name),"LTRB");
         $this->setX(180);
         $this->Cell(0,10,$coin." ".number_format($itemx->price,2,".",","),"LTRB");
         $total +=$itemx->q*$itemx->price;
         $this->setY(95+$starty);
         $this->setX(65);
         $starty+=10;
    }
}
         $starty+=5;
        $this->SetFont('Arial','',12);
        $starty+=100;
         $this->setY($starty);
         $this->setX(20);
         $this->Cell(0,10,"Envio:");
         $this->setX(80);
         $this->Cell(0,10,$coin. " ".number_format($ship->price,2,".",","),"LTRB");
         $starty+=10;
///////////////////////////////////////////////////////////// Total
        $this->SetFont('Arial','',12);
         $this->setY($starty);
         $this->setX(20);
         $this->Cell(0,10,"SubTotal:");
         $this->setX(80);
         $this->Cell(0,10,$coin. " ".number_format($total,2,".",","),"LTRB");
         $starty+=10;
         $this->setY($starty);
         $this->setX(20);
         $this->Cell(0,10,"$iva_txt:");
         $this->setX(80);
         $this->Cell(0,10,$coin. " ".number_format($total*($iva/100),2,".",","),"LTRB");
         $starty+=10;
         $this->setY($starty);
         $this->setX(20);
         $this->Cell(0,10,"Total:");
         $this->setX(80);
         $this->Cell(0,10,$coin. " ".number_format($total+($total*($iva/100)),2,".",","),"LTRB");
if($service->coupon_id!=null){
    $coupon = CouponData::getById($service->coupon_id);
    $subtotal = $total+($total*($iva/100));
    $xdiscount = $subtotal*($coupon->val/100);
         $starty+=10;
         $this->setY($starty);
         $this->setX(20);
         $this->Cell(0,10,"Descuento :");
         $this->setX(80);
         $this->Cell(0,10,$coin. " ".number_format($xdiscount,2,".",","),"LTRB");
         $starty+=10;
         $this->setY($starty);
         $this->setX(20);
         $this->Cell(0,10,"Total Final:");
         $this->setX(80);
         $this->Cell(0,10,$coin. " ".number_format($subtotal-$xdiscount,2,".",","),"LTRB");
}
         $starty+=20;
        $this->SetFont('Arial','',12);
         $this->setY($starty);
         $this->setX(20);
         $this->Cell(0,10,"Persona que recibe:");
         $this->setX(80);
         $this->Cell(0,10,$service->person_name,"LTRB");
         $starty+=10;
        $this->SetFont('Arial','',12);
         $this->setY($starty);
         $this->setX(20);
         $this->Cell(0,10,"Telefono:");
         $this->setX(80);
         $this->Cell(0,10,$service->person_phone,"LTRB");
         $starty+=10;
        $this->SetFont('Arial','',12);
         $this->setY($starty);
         $this->setX(20);
         $this->Cell(0,10,"Domicilio/Direccion:");
         $this->setX(80);
         $this->Cell(0,10,$service->person_address,"LTRB");
         $starty+=10;
        $this->SetFont('Arial','',12);
         $this->setY($starty);
         $this->setX(20);
         $this->Cell(0,10,"Ciudad:");
         $this->setX(80);
         $this->Cell(0,10,$service->person_city,"LTRB");
         $starty+=10;
        $this->SetFont('Arial','',12);
         $this->setY($starty);
         $this->setX(20);
         $this->Cell(0,10,"Codigo Postal / ZIP:");
         $this->setX(80);
         $this->Cell(0,10,$service->person_zip,"LTRB");

/////////////////////////////////////////////////////////////


        ////////////////////////////////////////////////////
}


// Tabla coloreada
}

$pdf = new PDF();
$pdf->AddPage();
// $pdf->Header();
$pdf->ImprovedTable("hola");

//echo $name;
$pdf->Output();
//print "<script>window.location=\"".$name."\";</script>";
?>