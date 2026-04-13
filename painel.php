<?php
require 'conexao.php';

// Busca as tarefas pendentes
$sql = "SELECT t.*, u.nome AS responsavel_nome 
        FROM tarefas t 
        LEFT JOIN usuarios u ON t.responsavel_id = u.id 
        WHERE t.status = 'pendente' 
        ORDER BY t.data_limite ASC";
$stmt = $pdo->query($sql);
$tarefas = $stmt->fetchAll();

// Busca os usuários para o dropdown de quem está concluindo
$sqlUsers = "SELECT id, nome, xp_acumulado FROM usuarios";
$users = $pdo->query($sqlUsers)->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel de Missões</title>
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, sans-serif; }
        body { background-color: #121212; color: #e0e0e0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        h2 { border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
        .card { background-color: #1e1e1e; padding: 20px; margin-bottom: 15px; border-radius: 8px; border-left: 5px solid #4CAF50; display: flex; justify-content: space-between; align-items: center; }
        .card.atrasada { border-left-color: #f44336; }
        .info h3 { margin: 0 0 5px 0; }
        .info p { margin: 0; color: #aaa; font-size: 14px; }
        .acao { text-align: right; }
        select, button { padding: 8px; border-radius: 4px; border: none; background-color: #2c2c2c; color: white; margin-top: 5px; }
        button { background-color: #4CAF50; cursor: pointer; font-weight: bold; }
        button:hover { background-color: #45a049; }
        .stats { background-color: #2c2c2c; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; gap: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Ranking de XP</h2>
        <div class="stats">
            <?php foreach ($users as $u): ?>
                <div><strong><?= htmlspecialchars($u['nome']) ?>:</strong> <?= $u['xp_acumulado'] ?> XP</div>
            <?php endforeach; ?>
        </div>

        <h2>Missões Pendentes</h2>
        <?php if (count($tarefas) === 0): ?>
            <p>Nenhuma missão pendente.</p>
        <?php else: ?>
            <?php foreach ($tarefas as $t): 
                $hoje = date('Y-m-d');
                $estaAtrasada = ($hoje > $t['data_limite']);
                $classeCard = $estaAtrasada ? 'card atrasada' : 'card';
            ?>
                <div class="<?= $classeCard ?>">
                    <div class="info">
                        <h3><?= htmlspecialchars($t['titulo']) ?></h3>
                        <p>Prazo: <?= date('d/m/Y', strtotime($t['data_limite'])) ?> | XP: <?= $t['xp_recompensa'] ?></p>
                        <p>Responsável Original: <?= $t['responsavel_nome'] ?? 'Ambos' ?></p>
                    </div>
                    <div class="acao">
                        <form action="processa_conclusao.php" method="POST">
                            <input type="hidden" name="tarefa_id" value="<?= $t['id'] ?>">
                            <select name="usuario_id" required>
                                <option value="">Quem concluiu?</option>
                                <?php foreach ($users as $u): ?>
                                    <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <br>
                            <button type="submit">Concluir Missão</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div style="text-align: center; margin-top: 20px;">
    <a href="index.php" style="color: #4CAF50; text-decoration: none; font-weight: bold;">⬅ Cadastrar Nova Missão</a>
</div>
</body>
</html>