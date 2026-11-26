<?php
require __DIR__ . '/config/database.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $status = $_POST['status'] ?? '';


    if (empty($titulo)) {
        $erro = 'O título é obrigatório.';
    } elseif (strlen($titulo) > 100) {
        $erro = 'O título deve ter no máximo 100 caracteres.';
    } elseif (!in_array($status, ['pendente', 'em_andamento', 'concluida'])) {
        $erro = 'Selecione um status válido.';
    } else {
      
        $stmt = $pdo->prepare('INSERT INTO tarefas (titulo, descricao, status) VALUES (?, ?, ?)');
        $stmt->execute([$titulo, $descricao, $status]);
        
        $sucesso = 'Tarefa cadastrada com sucesso!';
     
        $titulo = $descricao = '';
        $status = '';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Tarefa</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main class="container">
        <h1>Nova Tarefa</h1>

        <?php if ($erro): ?>
            <div class="mensagem erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="mensagem sucesso">
                <?= htmlspecialchars($sucesso) ?>
                <a href="index.php">Ver lista</a>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="grupo-form">
                <label for="titulo">Título *</label>
                <input type="text" id="titulo" name="titulo" 
                       value="<?= htmlspecialchars($titulo ?? '') ?>" required maxlength="100">
            </div>

            <div class="grupo-form">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" rows="4"><?= htmlspecialchars($descricao ?? '') ?></textarea>
            </div>

            <div class="grupo-form">
                <label for="status">Status *</label>
                <select id="status" name="status" required>
                    <option value="">Selecione...</option>
                    <option value="pendente" <?= ($status ?? '') === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                    <option value="em_andamento" <?= ($status ?? '') === 'em_andamento' ? 'selected' : '' ?>>Em andamento</option>
                    <option value="concluida" <?= ($status ?? '') === 'concluida' ? 'selected' : '' ?>>Concluída</option>
                </select>
            </div>

            <div class="acoes">
                <a class="botao secundario" href="index.php">Voltar</a>
                <button type="submit" class="botao">Salvar</button>
            </div>
        </form>
    </main>
</body>
</html>
