<?php
require_once __DIR__ . '/src/Mob.php';
use src\Mob;

session_start();

if (!isset($_SESSION['myMob']) || !isset($_SESSION['opponentMob']))
{
   header('Content-Type: application/json');
   echo json_encode(['error' => 'Aucun combat en cours']);
   exit;
} else
{
    $myMob = $_SESSION['myMob'];
    $opponentMob = $_SESSION['opponentMob'];

    $playerDamage = $myMob->getAtk();
    $opponentMob->takeDamage($playerDamage);

    if ($opponentMob->isAlive()) {
        $opponentDamage = $opponentMob->getAtk();
        $myMob->takeDamage($opponentDamage);
    }

    $_SESSION['myMob'] = $myMob;
    $_SESSION['opponentMob'] = $opponentMob;

    $data = [
        'player' => $myMob,
        'opponent' => $opponentMob
    ];

    header('Content-Type: application/json');
    echo json_encode($data);
}


