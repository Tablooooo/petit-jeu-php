<?php

require_once __DIR__ . '/src/Mob.php';
require_once __DIR__ . '/get_pokemons.php';
use src\Mob;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$catalog = getCatalog();

// Si une session existe déjà, on renvoie simplement l'état actuel
if (isset($_SESSION['myMob']) || isset($_SESSION['opponentMob']))
{
    header('Content-Type: application/json');
    echo json_encode([
        'player' => $_SESSION['myMob'],
        'opponent' => $_SESSION['opponentMob']
    ]);
    exit;
}

// Sinon, on regarde si le JS nous a envoyé un choix de Pokémon (ex: ?choice=salameche)
$choice = $_GET['choice'] ?? null;

// Si le choix n'existe pas dans le catalogue, on remet carapuce
if (!$choice || !array_key_exists($choice, $catalog)) {
    header('Content-Type: application/json');
    http_response_code(400); // 400 = Bad Request (Erreur du client)
    echo json_encode([
        'error' => 'InvalidSelection',
        'message' => 'Le Pokémon sélectionné n\'existe pas dans le catalogue.'
    ]);
    exit;
}

// On récupère les données du Pokémon choisi par le joueur
$playerData = $catalog[$choice];

// On sélectionne un adversaire aléatoire dans le catalogue
$catalogKeys = array_keys($catalog);
$randomKey = $catalogKeys[array_rand($catalogKeys)];
$opponentData = $catalog[$randomKey];

// On instancie les deux monstres et on les stocke en Session
$_SESSION['myMob'] = new Mob($playerData['name'], $playerData['maxHp'], $playerData['atk']);
$_SESSION['opponentMob'] = new Mob($opponentData['name'], $opponentData['maxHp'], $opponentData['atk']);

// On prépare la réponse pour le JavaScript
$data = [
    'player' => $_SESSION['myMob'],
    'opponent' => $_SESSION['opponentMob']
];

header('Content-Type: application/json');
echo json_encode($data);