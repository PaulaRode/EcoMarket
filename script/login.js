document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('loginForm');
    const loginMessage = document.getElementById('login-message');

    form.addEventListener('submit', async function (event) {
        event.preventDefault(); // impede envio padrão do form

        const email = document.getElementById('email').value.trim();
        const senha = document.getElementById('senha').value.trim();

        if (!email || !senha) {
            loginMessage.textContent = 'Preencha todos os campos.';
            return;
        }

        try {
            const response = await fetch('./loginProcessa.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ email, senha })
            });

            const data = await response.json();

            if (data.erro) {
                loginMessage.textContent = data.erro;
            } else if (data.sucesso) {
                loginMessage.style.color = 'lightgreen';
                loginMessage.textContent = 'Login realizado com sucesso!';
                setTimeout(() => window.location.href = 'dashboard.php', 1000);
            }
        } catch (error) {
            loginMessage.textContent = 'Erro na requisição.';
            console.error(error);
        }
    });
});
