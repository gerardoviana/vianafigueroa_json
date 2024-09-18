<?php
require("conn.php");


$arreglo = array(
    "sucess"=>false,
    "status"=>400,
    "data"=>"",
    "message"=>"",
    "cant"=> 0
);

if($_SERVER["REQUEST_METHOD"] === "GET"){
    //EL METODO ES GET
   if(isset($_GET["type"]) && $_GET["type"] != ""){
    //SI ENVIO EL PARAMETRO TYPE

    $conexion = new conexion;
    $conn = $conexion->conectar();

    $datos = $conn->query('SELECT * FROM empleado;');
    $resultados = $datos->fetchAll();

    switch($_GET["type"]){
        case "json":
            result_json($resultados);
            break;
            case "xml":
                result_xml($resultados);
                break;
                default:
                echo("Por favor, defina el tipo de resultado");
                break;
    }
   }else{
    //NO HAY VALORES PARA EL PARAMETRO TYPE
    $arreglo = array(
        "sucess"=>false,
        "status"=>array("status_code"=>412,"status_text"=> "Precondtion Failed"),
        "data"=>"",
        "message"=>"Se esperaba el parametro 'type' ",
        "cant"=> 0
    );
   }
}else{
    //NO SE ACEPTA EL METODO
    $arreglo = array(
        "sucess"=>false,
        "status"=>array("status_code"=>405,"status_text"=> "METHOD NOT ALLOWED"),
        "data"=>"",
        "message"=>"No se acepta el metodo",
        "cant"=> 0
    );
}


function result_json($resultado){
    $arreglo = array(
        "sucess"=>true,
        "status"=>array("status_code"=>200,"status_text"=> "Ok"),
        "data"=>$resultado,
        "message"=>"",
        "cant"=> sizeof($resultado)
    );

    header("HTTP/1.1".$arreglo["status"]["status_code"]," ".$arreglo["status"]["status_text"]);
    header("Content-Type: Application/json");
    echo(json_encode($arreglo));
}

function result_xml($resultado){
    $xml = new SimpleXMLElement("<empleados />");
    foreach($resultado as $i => $v){
        $subnodo = $xml->addChild("empleado");
        $invertir = array_flip($v);
        array_walk_recursive($invertir,array($subnodo,'addChild'));
    }
    header("Content-Type: text/xml");
    echo($xml->asXML());
}


?>