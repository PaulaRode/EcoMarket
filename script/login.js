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

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            // Primeiro vamos ver o que está sendo retornado
            const responseText = await response.text();
            console.log('Resposta do servidor:', responseText);

            let data;
            try {
                data = JSON.parse(responseText);
            } catch (parseError) {
                console.error('Erro ao fazer parse do JSON:', parseError);
                loginMessage.textContent = 'Erro no formato da resposta do servidor';
                return;
            }

            if (data.erro) {
                loginMessage.textContent = data.erro;
            } else if (data.sucesso) {
                loginMessage.style.color = 'lightgreen';
                loginMessage.textContent = 'Login realizado com sucesso!';
                // Redirecionar para o dashboard após login bem-sucedido
                setTimeout(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.href = 'dashboard.php';
                    }
                }, 1000);
            }
        } catch (error) {
            loginMessage.textContent = 'Erro na requisição: ' + error.message;
            console.error('Erro completo:', error);
        }
    });
});
