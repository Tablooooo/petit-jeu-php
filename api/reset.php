<?php

session_start();

// On vide et on détruit la session
$_SESSION = [];
session_destroy();

// On renvoie une réponse vide ou un succès en JSON
header('Content-Type: application/json');
echo json_encode(['success' => true]);