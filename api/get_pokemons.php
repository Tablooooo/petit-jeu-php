<?php

session_start();

function getCatalog(): array {
    $catalog = [
        'carapuce'   => ['name' => 'Carapuce', 'maxHp' => 100, 'atk' => 30],
        'salameche'  => ['name' => 'Salamèche', 'maxHp' => 80,  'atk' => 40],
        'bulbizarre' => ['name' => 'Bulbizarre', 'maxHp' => 120, 'atk' => 20],
    ];

    // Si la boîte existe, alors on fusionne. Sinon, on ne fait rien.
    if (isset($_SESSION['custom_pokemons'])) {
        foreach ($_SESSION['custom_pokemons'] as $customPokemon) {
            // On transforme le nom en minuscules pour avoir une clé propre (ex: "Mew" devient "mew")
            $key = strtolower($customPokemon['name']);

            // On l'injecte dans le catalogue avec sa clé textuelle
            $catalog[$key] = $customPokemon;
        }
    }

    return $catalog;
}



// Uniquement si le fichier est appelé directement par le js pour le menu
if (basename($_SERVER['SCRIPT_FILENAME']) === 'get_pokemons.php') {
    header('Content-Type: application/json');
    echo json_encode(getCatalog());
}