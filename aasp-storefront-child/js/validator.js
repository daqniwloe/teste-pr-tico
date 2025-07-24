document.addEventListener('DOMContentLoaded', function () {
    const validateBtn = document.getElementById('aasp-validate-btn');
    const spinner = document.getElementById('aasp-validation-spinner');
    const messageDiv = document.getElementById('aasp-validation-message');

    if (!validateBtn) {
        return;
    }

    validateBtn.addEventListener('click', function () {
        spinner.style.display = 'block';
        messageDiv.innerHTML = '';
        messageDiv.style.color = '';
        validateBtn.disabled = true;

        // Dados que seriam enviados
        const postData = {
            product_id: aaspProductData.productId,
            complexity: aaspProductData.complexity,
            estimated_time: aaspProductData.estimatedHours,
        };
        console.log('Enviando dados (simulação):', postData);

        // --- SIMULAÇÃO DE CHAMADA DE API ---
        new Promise((resolve, reject) => {
            setTimeout(() => {
                // Simula um resultado aleatório de sucesso ou erro
                const isSuccess = Math.random() > 0.5;

                if (isSuccess) {
                    resolve({ success: true, message: 'Conteúdo validado com sucesso pelo sistema externo. (Simulação)' });
                } else {

                    reject({ success: false, message: 'Erro de comunicação com o sistema de validação. (Simulação)' });
                }
            }, 2000); 
        })
        .then(data => {
            // Exibe a mensagem de SUCESSO
            messageDiv.style.color = 'green';
            messageDiv.textContent = data.message;
        })
        .catch(error => {
            // Exibe a mensagem de ERRO
            messageDiv.style.color = 'red';
            messageDiv.textContent = error.message || 'Ocorreu um erro de rede. Tente novamente.';
        })
        .finally(() => {
            spinner.style.display = 'none';
            validateBtn.disabled = false;
        });
    });
});
