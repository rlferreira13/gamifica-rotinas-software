<?php
require 'conexao.php';
$sql = "SELECT t.*, u.nome AS responsavel_nome FROM tarefas t LEFT JOIN usuarios u ON t.responsavel_id = u.id WHERE t.status = 'pendente' ORDER BY t.data_limite ASC";
$tarefas = $pdo->query($sql)->fetchAll();
$users = $pdo->query("SELECT id, nome, xp_acumulado FROM usuarios")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gamificação de Rotinas | Painel</title>
    <style>
        :root { --bg-base: #121212; --bg-card: #1e1e1e; --text-main: #e0e0e0; --accent-green: #4CAF50; --accent-red: #f44336; --accent-gold: #FFD700; --accent-cyan: #00BCD4; }
        * { box-sizing: border-box; font-family: 'Segoe UI', system-ui, sans-serif; margin: 0; padding: 0; }
        body { background-color: var(--bg-base); color: var(--text-main); padding: 40px 20px; }
        .container { max-width: 900px; margin: 0 auto; }
        .header-title { border-bottom: 2px solid var(--accent-green); padding-bottom: 10px; margin-bottom: 20px; color: var(--text-main); font-weight: 300; }
        .stats { background-color: var(--bg-card); padding: 20px; border-radius: 8px; margin-bottom: 30px; display: flex; gap: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.3); border-left: 4px solid var(--accent-cyan); }
        .stats div { font-size: 16px; }
        .stats strong { color: var(--accent-cyan); }
        .card { background-color: var(--bg-card); padding: 20px; margin-bottom: 15px; border-radius: 8px; border-left: 6px solid var(--accent-green); display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.2); transition: transform 0.2s; }
        .card:hover { transform: translateX(5px); }
        .card.atrasada { border-left-color: var(--accent-red); }
        .info h3 { margin-bottom: 8px; color: #fff; font-size: 18px; }
        .info p { margin-bottom: 4px; color: #aaa; font-size: 14px; }
        .badge-xp { background: rgba(255,255,255,0.1); padding: 3px 8px; border-radius: 12px; font-weight: bold; color: var(--accent-gold); font-size: 12px; }
        .acao { display: flex; flex-direction: column; gap: 10px; min-width: 200px; }
        select { padding: 10px; border-radius: 6px; border: 1px solid #444; background-color: #2a2a2a; color: white; outline: none; }
        button { padding: 10px; background-color: var(--accent-green); color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; transition: background 0.3s; }
        button:hover { background-color: #45a049; }
        .card.atrasada button { background-color: var(--accent-red); }
        .card.atrasada button:hover { background-color: #d32f2f; }
        .nav { text-align: center; margin-top: 40px; }
        .nav a { text-decoration: none; font-weight: 600; margin: 0 15px; }
        .link-add { color: var(--accent-green); }
        .link-loja { color: var(--accent-gold); }
        .link-bi { color: var(--accent-cyan); }
    </style>
</head>
<body>
<div class="container">
    <h2 class="header-title">Ranking Atual de XP</h2>
    <div class="stats">
        <?php foreach ($users as $u): ?>
            <div><strong><?= htmlspecialchars($u['nome']) ?>:</strong> <?= $u['xp_acumulado'] ?> XP</div>
        <?php endforeach; ?>
    </div>

    <h2 class="header-title">Missões em Andamento</h2>
    <?php if (count($tarefas) === 0): ?>
        <div class="card"><div class="info"><p>Todas as missões foram concluídas. O painel está limpo.</p></div></div>
    <?php else: ?>
        <?php foreach ($tarefas as $t): 
            $estaAtrasada = (date('Y-m-d') > $t['data_limite']);
        ?>
            <div class="card <?= $estaAtrasada ? 'atrasada' : '' ?>">
                <div class="info">
                    <h3><?= htmlspecialchars($t['titulo']) ?></h3>
                    <p>Prazo: <?= date('d/m/Y', strtotime($t['data_limite'])) ?> | <span class="badge-xp"><?= $t['xp_recompensa'] ?> XP</span></p>
                    <p>Responsável: <?= $t['responsavel_nome'] ?? 'Ambos (Qualquer um pode concluir)' ?></p>
                </div>
                <div class="acao">
                    <form action="processa_conclusao.php" method="POST" style="display:flex; flex-direction:column; gap:8px; margin:0;">
                        <input type="hidden" name="tarefa_id" value="<?= $t['id'] ?>">
                        <select name="usuario_id" required>
                            <option value="">Quem executou?</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit"><?= $estaAtrasada ? 'Assumir Penalidade' : 'Concluir & Ganhar XP' ?></button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="nav">
        <a href="index.php" class="link-add">⬅ Nova Missão</a>
        <a href="loja.php" class="link-loja">Ir para a Loja ➔</a>
        <a href="dashboard.php" class="link-bi">Dashboard BI ➔</a>
    </div>
</div>
</body>
</html>