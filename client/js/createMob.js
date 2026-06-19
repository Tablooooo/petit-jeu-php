document.addEventListener('DOMContentLoaded', () => {
    inputName = document.getElementById('input-name');
    inputMaxHp = document.getElementById('input-maxHp');
    inputAtk = document.getElementById('input-atk');
    btnSubmit = document.getElementById('btn-submit');

    btnSubmit.addEventListener('click', async (e) => {
        e.preventDefault();

        const request = await fetch('../api/createMob.php', {
            method: "POST",
            headers: {
                'Content-Type': "application/json",
            },
            body: JSON.stringify({
                name : inputName.value,
                maxHp : inputMaxHp.value,
                atk : inputAtk.value
            })
        })

        if (request.ok) {
            alert('La création du pokémon a été réalisé avec succès.');
            window.location.href = 'play.html';
        } else {
            const errorData = await request.json();
            alert(errorData.error);
        }
    })
})