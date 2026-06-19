<?php

if (session_status() === PHP_SESSION_NONE) {
session_start();
}

// On supprime UNIQUEMENT les variables liées au combat en cours
unset($_SESSION['myMob']);
unset($_SESSION['opponentMob']);

// On ne touche PAS à $_SESSION['custom_pokemons'], comme ça ils restent sauvegardés

header('Content-Type: application/json');
echo json_encode(['success' => true]);