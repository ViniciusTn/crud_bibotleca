<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autores</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <h1>Lista de Autores</h1>
    <div>
        <a href="create.php">Adicionar Autor</a>
    </div>
    <div id="autores-list">
        <!-- Autores will be populated here -->
    </div>
    <script>
        fetch('../../controllers/AutorController.php')
            .then(response => response.json())
            .then(data => {
                const autoresList = document.getElementById('autores-list');
                if (data.autores.length > 0) {
                    data.autores.forEach(autor => {
                        const autorItem = document.createElement('div');
                        autorItem.innerHTML = `
                            <p>${autor.nome} (${autor.nacionalidade}) - ${autor.ano_nascimento}</p>
                            <a href="update.php?id=${autor.id_autor}">Editar</a>
                            <a href="delete.php?id=${autor.id_autor}">Excluir</a>
                        `;
                        autoresList.appendChild(autorItem);
                    });
                } else {
                    autoresList.innerHTML = '<p>Nenhum autor encontrado.</p>';
                }
            })
            .catch(error => console.error('Erro:', error));
    </script>
</body>
</html>
