<?php

/*
 * Ce fichier joue le rôle de plan pour la fabrication d'un monstre
 */

namespace src;

class Mob implements \JsonSerializable
{
    // ==========================================
    // PROPRIÉTÉS
    // ==========================================
    private string $name;
    private int $currentHp;
    private int $maxHp;
    private int $atk;

    // ==========================================
    // CONSTRUCTEUR
    // ==========================================
    public function __construct(string $name, int $maxHp, int $atk)
    {
        $this->name = $name;
        $this->maxHp = $maxHp ?? 0;
        $this->atk = $atk;
        $this->currentHp = $maxHp;
    }

    // ==========================================
    // SÉRIALISATION JSON
    // ==========================================
    /**
     * Permet d'envoyer les données privées
     * d'un objet au format JSON.
     */
    public function jsonSerialize(): mixed
    {
        return [
            'name'      => $this->name,
            'maxHp'     => $this->maxHp,
            'atk'       => $this->atk,
            'currentHp' => $this->currentHp
        ];
    }

    // ==========================================
    // MÉTHODES MÉTIER (LOGIQUE DE JEU)
    // ==========================================
    public function takeDamage(int $damage): void
    {
        $this->currentHp -= $damage;

        if ($this->currentHp <= 0) {
            $this->currentHp = 0;
        }
    }

    public function isAlive(): bool
    {
        return $this->currentHp > 0;
    }

    // ==========================================
    // GETTERS & SETTERS
    // ==========================================
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        if (is_string($name)) {
            $this->name = $name;
        } else {
            return 'erreur';
        }
    }

    public function getCurrentHp(): int
    {
        return $this->currentHp;
    }

    public function setCurrentHp(int $currentHp): void
    {
        $this->currentHp = $currentHp;
    }

    public function getMaxHp(): int
    {
        return $this->maxHp;
    }

    public function setMaxHp(int $maxHp): void
    {
        $this->maxHp = $maxHp;
    }

    public function getAtk(): int
    {
        return $this->atk;
    }

    public function setAtk(int $atk): void
    {
        $this->atk = $atk;
    }
}