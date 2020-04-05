<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
include("db_connect.php");

$name = "";
$password = "";
$review = "";
$rate = 0;
$iduser = 0;
$idmovie = 0;

if ($_GET){
    if (isset($_GET['name'])){
        $name = $_GET['name'];
    }
    if (isset($_GET['password'])){
        $password = $_GET['password'];
    }
    if (isset($_GET['review'])){
        $review = $_GET['review'];
    }
    if (isset($_GET['rate'])){
        $rate = $_GET['rate'];
    }
    if (isset($_GET['iduser'])){
        $iduser = $_GET['iduser'];
    }
    if (isset($_GET['idmovie'])){
        $idmovie = $_GET['idmovie'];
    }
}

function requestUser($name, $password)
{
    $resultat = [];
    $bdd = connect();

    $sql = "SELECT * FROM users WHERE name LIKE '$name' and password LIKE '$password'";
    //var_dump($sql);
    $state = $bdd->query($sql);
    $i = 0;
    while($row = $state->fetch()) {
        $resultat[$i] = (array)$row;
        $i++;
    }
    print_r(json_encode($resultat));
    header('Content-Type: application/json');
    return json_encode($resultat, JSON_PRETTY_PRINT);
}

function insertOpinion($review, $rate, $iduser, $idmovie)
{
    $resultat = [];
    $bdd = connect();

    $sql = "INSERT INTO opinion(review, rate, idusers, idmovie) VALUES ('$review', '$rate', '$iduser', '$idmovie')";
    //var_dump($sql);
    $state = $bdd->query($sql);
}

function getOpinions($iduser){
    $resultat = [];
    $bdd = connect();

    $sql = "SELECT * FROM opinion WHERE idusers = '$iduser'";
    //var_dump($sql);
    $state = $bdd->query($sql);
    $i = 0;
    while($row = $state->fetch()) {
        $resultat[$i] = (array)$row;
        $i++;
    }

    print_r(json_encode($resultat));
    header('Content-Type: application/json');
    return json_encode($resultat, JSON_PRETTY_PRINT);
}

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

//getListMensuelle();

if($iduser !== 0 && $rate === 0 && $idmovie === 0 && $name === "" && $password === "") {
    getOpinions($iduser);
} else if($rate===0){
    requestUser($name,$password);
}else{
    insertopinion($review, $rate, $iduser, $idmovie);
}