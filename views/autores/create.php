<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Autor</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <h1>Adicionar Autor</h1>
    <form id="add-author-form">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        
        <label for="nacionalidade">Nacionalidade:</label>
        <input type="text" id="nacionalidade" name="nacionalidade">
        
        <label for="ano_nascimento">Ano de Nascimento:</label>
        <input type="number" id="ano_nascimento" name="ano_nascimento">
        
        <button type="submit">Adicionar Autor</button>
    </form>
    <div id="message"></div>
    <script>
        document.getElementById('add-author-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            const data = {
                nome: formData.get('nome'),
                nacionalidade: formData.get('nacionalidade'),
                ano_nascimento: formData.get('ano_nascimento')
            };

            fetch('../../controllers/AutorController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('message').innerText = data.message;
                if (data.message === "Autor criado com sucesso.") {
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 2000);
                }
            })
            .catch(error => console.error('Erro:', error));
        });
    </script>
</body>
</html>
