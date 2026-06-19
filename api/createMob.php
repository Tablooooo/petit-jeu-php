<?php

session_start();

$jsonBrut = file_get_contents('php://input');
$datas = json_decode($jsonBrut, true);

if (!$datas['name'] || !is_string($datas['name'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Le nom est obligatoire et doit être une chaine de caractères!']);
    exit;
}
if (!$datas['maxHp'] || !is_numeric($datas['maxHp']) || ($datas['maxHp']) < 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Les pv sont obligatoires et doivent être un entier positif !']);
    exit;
}
if (!$datas['atk'] || !is_numeric($datas['atk']) || ($datas['atk']) < 0) {
    http_response_code(400);
    echo json_encode(['error' => 'L\'atk est obligatoires et doivent être un entier positif !']);
    exit;
}

// Si la boîte n'existe pas encore en session
if (!isset($_SESSION['custom_pokemons'])) {
    // on la crée sous forme de tableau vide
    $_SESSION['custom_pokemons'] = [];
}

// Les petits crochets [] signifient "ajoute à la suite"
// pour ne pas écraser la données précédentes
$_SESSION['custom_pokemons'][] = $datas;

// Si on est arrivé jusque là, alors tout est good, on envoit un message de succès
http_response_code(200);
header('Content-Type: application/json');
echo json_encode(['message' => 'La création du pokémon a été réalisée avec succès.']);