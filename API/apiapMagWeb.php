<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
include("db_connect.php");

function getListMensuelle() {
    $resultat = [];
    $bdd = connect();

    $sql = "SELECT DISTINCT idmovie FROM opinion";
    //var_dump($sql);
    $state = $bdd->query($sql);
    $i = 0;
    while($row = $state->fetch()) {
        $resultat[$i] = (array)$row;
        $i++;
    }

    $idmovie = [];
    foreach($resultat as $item) {
        $idmovie[] = $item['idmovie'];
    }

    $resultMin = [];
    $resultMax = [];
    for ($i=0 ; $i < sizeof($idmovie); $i++) {
        $sql = "SELECT idmovie, MIN(rate), review FROM opinion WHERE idmovie = '$idmovie[$i]' GROUP BY  review LIMIT 1";
        $state = $bdd->query($sql);
        while($row = $state->fetch()) {
            $resultMin[$i] = (array)$row;
        }   
    }

    for ($i=0 ; $i < sizeof($idmovie); $i++) {
        $sql = "SELECT idmovie, MAX(rate), review FROM opinion WHERE idmovie = '$idmovie[$i]' GROUP BY  review LIMIT 1";
        $state = $bdd->query($sql);
        while($row = $state->fetch()) {
            $resultMax[$i] = (array)$row;
        }   
    }
    $resultatMensuelle = [];
    $resultatMensuelle = array_merge($resultMin, $resultMax);



    print_r($resultatMensuelle);

    //print_r(json_encode($resultat[0]));
    header('Content-Type: application/json');
    return json_encode($resultat, JSON_PRETTY_PRINT);
}

getListMensuelle();
