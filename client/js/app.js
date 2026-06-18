/*

Ce fichier sert d'intermédiaire entre l'api et le client.

 */

document.addEventListener('DOMContentLoaded', () => {

    // --- ÉLÉMENTS DE INTERFACE GLOBALE ---
    const battleLog = document.getElementById('battle-log');
    const btnAttack = document.getElementById('btn-attack');
    const btnReset = document.getElementById('btn-reset');

    // --- ÉLÉMENTS DU JOUEUR (PLAYER) ---
    const playerName = document.getElementById('player-name');
    const playerHpBar = document.getElementById('player-hp-bar');
    const playerCurrentHp = document.getElementById('player-hp');
    const playerMaxHp = document.getElementById('player-max-hp');

    // --- ÉLÉMENTS DE L'ADVERSAIRE (OPPONENT) ---
    const opponentName = document.getElementById('opponent-name');
    const opponentHpBar = document.getElementById('opponent-hp-bar');
    const opponentCurrentHp = document.getElementById('opponent-hp');
    const opponentMaxHp = document.getElementById('opponent-max-hp');

    async function loadSelectionMenu() {
        let response = await fetch('../api/get_pokemons.php');
        let pokemons = await response.json();

        const characterList = document.getElementById('character-list');
        characterList.innerHTML = '';

        // Boucle sur les données reçues pour créer le html
        Object.keys(pokemons).forEach(index => {
            const pokemon = pokemons[index];

            const button = document.createElement('button');
            button.className = 'btn-choice';
            button.innerHTML = `
                <span class="pokemon-name">${pokemon.name}</span>
                <span class="pokemon-stats">${pokemon.maxHp} HP / ${pokemon.atk} ATK</span>
            `;

            characterList.appendChild(button);

            button.addEventListener('click', () => {
                startBattle(index);
            })
        });
    }

    loadSelectionMenu();

    async function startBattle(index) {

        try {

            let response = await fetch (`../api/get_current_state.php?choice=${index}`);

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || "Une erreur est survenue");
            }

            // Si tout est bon, on lit le json et on affiche l'arène
            let data = await response.json();

            // On masque le menu de sélection et on affiche l'arène
            document.getElementById('selection-screen').style.display = 'none';
            document.getElementById('battle-arena').style.display = 'block';

            updateInterface(data)
        } catch (error) {
            alert (`Impossible de lancer le combat : ${error.message}`);
            console.error('Détails de l\'erreur', error);

            // L'écran de sélection reste visible si ça a foiré
            document.getElementById('selection-screen').style.display = 'block';
            document.getElementById('battle-arena').style.display = 'none';
        }

    }

    // Fonction unique pour mettre à jour TOUT le HTML d'un coup
    function updateInterface(data) {
        // Remplissage Joueur
        playerName.textContent = data.player.name;
        playerCurrentHp.textContent = data.player.currentHp;
        playerMaxHp.textContent = data.player.maxHp;

        // Remplissage Adversaire
        opponentName.textContent = data.opponent.name;
        opponentCurrentHp.textContent = data.opponent.currentHp;
        opponentMaxHp.textContent = data.opponent.maxHp;

        // Calcul et mise à jour des barres de vie en %
        const playerPct = (data.player.currentHp / (data.player.maxHp) * 100) + '%';
        const opponentPct = (data.opponent.currentHp / (data.opponent.maxHp) * 100) + '%';

        playerHpBar.style.width = playerPct;
        opponentHpBar.style.width = opponentPct;

        // Si quelqu'un est mort, on laisse le bouton désactivé
        if (data.player.currentHp <= 0 || data.opponent.currentHp <= 0) {
            btnAttack.disabled = true;
            battleLog.textContent = "Le combat est terminé !";
        } else {
            btnAttack.disabled = false;
        }
    }

    async function getDataFromAPI() {
        let response =  await fetch('../api/get_current_state.php');
        let data = await response.json();

        updateInterface(data)
    }

    btnAttack.addEventListener('click', async()=> {
        btnAttack.disabled = true;

        let response = await fetch('../api/attack.php');
        let data = await response.json();

        updateInterface(data);
    })

    // Écouteur pour le bouton de réinitialisation
    btnReset.addEventListener('click', async () => {
        // On appelle l'API pour détruire la session
        await fetch('../api/reset.php');

        // On réaffiche le menu et on cache l'arène
        document.getElementById('selection-screen').style.display = 'block';
        document.getElementById('battle-arena').style.display = 'none';

        // On réactive le bouton d'attaque pour le prochain combat
        btnAttack.disabled = false;

        battleLog.textContent = "En attente du début du combat...";
    });
})