<?php
require 'conexao.php';
$users = $pdo->query("SELECT id, nome, xp_acumulado FROM usuarios")->fetchAll();
$recompensas = $pdo->query("SELECT * FROM recompensas ORDER BY custo_xp ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gamificação de Rotinas | Loja</title>
    <style>
        :root { --bg-base: #121212; --bg-card: #1e1e1e; --text-main: #e0e0e0; --accent-gold: #FFD700; --accent-green: #4CAF50; --accent-cyan: #00BCD4; }
        * { box-sizing: border-box; font-family: 'Segoe UI', system-ui, sans-serif; margin: 0; padding: 0; }
        body { background-color: var(--bg-base); color: var(--text-main); padding: 40px 20px; }
        .container { max-width: 900px; margin: 0 auto; }
        .header-title { border-bottom: 2px solid var(--accent-gold); padding-bottom: 10px; margin-bottom: 20px; color: var(--accent-gold); font-weight: 300; }
        .stats { background-color: var(--bg-card); padding: 20px; border-radius: 8px; margin-bottom: 30px; display: flex; gap: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.3); border-left: 4px solid var(--accent-green); }
        .stats div { font-size: 16px; }
        .stats strong { color: var(--accent-green); }
        .grid-loja { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; }
        .card { background-color: var(--bg-card); padding: 25px; border-radius: 12px; border: 1px solid #333; text-align: center; box-shadow: 0 8px 15px rgba(0,0,0,0.2); transition: transform 0.3s, border-color 0.3s; display: flex; flex-direction: column; justify-content: space-between; }
        .card:hover { transform: translateY(-5px); border-color: var(--accent-gold); }
        .card h3 { color: #fff; margin-bottom: 10px; font-size: 20px; }
        .preco { font-size: 24px; font-weight: bold; color: var(--accent-gold); margin-bottom: 20px; }
        select { width: 100%; padding: 12px; margin-bottom: 15px; border-radius: 6px; border: 1px solid #444; background-color: #2a2a2a; color: white; outline: none; font-size: 14px; }
        button { width: 100%; padding: 12px; background-color: transparent; border: 2px solid var(--accent-gold); color: var(--accent-gold); border-radius: 6px; font-weight: bold; text-transform: uppercase; cursor: pointer; transition: all 0.3s; }
        button:hover { background-color: var(--accent-gold); color: #000; }
        .nav { text-align: center; margin-top: 50px; }
        .nav a { text-decoration: none; font-weight: 600; margin: 0 15px; }
        .link-add { color: var(--accent-green); }
        .link-painel { color: var(--accent-green); }
        .link-bi { color: var(--accent-cyan); }
    </style>
</head>
<body>
<div class="container">
    <h2 class="header-title">Seu Caixa de XP</h2>
    <div class="stats">
        <?php foreach ($users as $u): ?>
            <div><strong><?= htmlspecialchars($u['nome']) ?>:</strong> <?= $u['xp_acumulado'] ?> XP</div>
        <?php endforeach; ?>
    </div>

    <h2 class="header-title">Catálogo de Resgate</h2>
    <div class="grid-loja">
        <?php foreach ($recompensas as $r): ?>
            <div class="card">
                <div>
                    <h3><?= htmlspecialchars($r['titulo']) ?></h3>
                    <div class="preco"><?= $r['custo_xp'] ?> XP</div>
                </div>
                <form action="processa_compra.php" method="POST" style="margin:0;">
                    <input type="hidden" name="recompensa_id" value="<?= $r['id'] ?>">
                    <input type="hidden" name="custo" value="<?= $r['custo_xp'] ?>">
                    <select name="usuario_id" required>
                        <option value="">Quem vai resgatar?</option>
                        <?php foreach ($users as $u): ?>
                            <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nome']) ?> (Saldo: <?= $u['xp_acumulado'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Resgatar Benefício</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="nav">
        <a href="index.php" class="link-add">⬅ Nova Missão</a>
        <a href="painel.php" class="link-painel">Painel de Missões</a>
        <a href="dashboard.php" class="link-bi">Dashboard BI ➔</a>
    </div>
</div>
</body>
</html>