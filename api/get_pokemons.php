<?php

function getCatalog(): array {
    return [
        'carapuce'   => ['name' => 'Carapuce', 'maxHp' => 100, 'atk' => 30],
        'salameche'  => ['name' => 'Salamèche', 'maxHp' => 80,  'atk' => 40],
        'bulbizarre' => ['name' => 'Bulbizarre', 'maxHp' => 120, 'atk' => 20],
    ];
}

// Uniquement si le fichier est appelé directement par le js pour le menu
if (basename($_SERVER['SCRIPT_FILENAME']) === 'get_pokemons.php') {
    header('Content-Type: application/json');
    echo json_encode(getCatalog());
}