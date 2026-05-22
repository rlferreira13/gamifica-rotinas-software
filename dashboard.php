<?php
require 'conexao.php';
$usuarios = $pdo->query("SELECT nome, xp_acumulado FROM usuarios ORDER BY xp_acumulado DESC")->fetchAll();
$statusTarefas = $pdo->query("SELECT status, COUNT(*) as total FROM tarefas GROUP BY status")->fetchAll();
$produtividade = ['pendente' => 0, 'concluida' => 0, 'atrasada' => 0];
foreach ($statusTarefas as $row) { $produtividade[$row['status']] = (int) $row['total']; }
$inflacao = $pdo->query("SELECT SUM(custo_xp) as custo_total FROM recompensas")->fetch();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gamificação de Rotinas | Business Intelligence</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --bg-base: #121212; --bg-card: #1e1e1e; --text-main: #e0e0e0; --accent-cyan: #00BCD4; --accent-green: #4CAF50; --accent-gold: #FFD700; }
        * { box-sizing: border-box; font-family: 'Segoe UI', system-ui, sans-serif; margin: 0; padding: 0; }
        body { background-color: var(--bg-base); color: var(--text-main); padding: 40px 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        .header-title { border-bottom: 2px solid var(--accent-cyan); padding-bottom: 10px; margin-bottom: 30px; color: var(--accent-cyan); font-weight: 300; }
        .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .kpi { background-color: var(--bg-card); padding: 25px; border-radius: 12px; text-align: center; border-bottom: 4px solid var(--accent-cyan); box-shadow: 0 4px 10px rgba(0,0,0,0.3); }
        .kpi h3 { margin: 0 0 10px 0; font-size: 38px; color: #fff; }
        .kpi p { margin: 0; color: #888; text-transform: uppercase; font-size: 13px; font-weight: 600; letter-spacing: 1px; }
        .chart-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px; }
        .card-grafico { background-color: var(--bg-card); padding: 20px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); display: flex; justify-content: center; align-items: center; min-height: 350px; }
        .nav { text-align: center; margin-top: 50px; }
        .nav a { text-decoration: none; font-weight: 600; margin: 0 15px; }
        .link-add { color: var(--accent-green); }
        .link-painel { color: var(--accent-green); }
        .link-loja { color: var(--accent-gold); }
    </style>
</head>
<body>
<div class="container">
    <h2 class="header-title">Inteligência de Dados (BI)</h2>
    
    <div class="kpi-grid">
        <div class="kpi" style="border-color: #4CAF50;">
            <h3><?= array_sum($produtividade) ?></h3>
            <p>Volume Total de Missões</p>
        </div>
        <div class="kpi" style="border-color: #FFD700;">
            <h3><?= $inflacao['custo_total'] ?? 0 ?> XP</h3>
            <p>Inflação do Catálogo (Custo para limpar a loja)</p>
        </div>
    </div>

    <div class="chart-grid">
        <div class="card-grafico">
            <canvas id="graficoStatus"></canvas>
        </div>
        <div class="card-grafico">
            <canvas id="graficoRanking"></canvas>
        </div>
    </div>

    <div class="nav">
        <a href="index.php" class="link-add">⬅ Nova Missão</a>
        <a href="painel.php" class="link-painel">Painel de Missões</a>
        <a href="loja.php" class="link-loja">Loja de Recompensas</a>
    </div>
</div>

<script>
    Chart.defaults.color = '#aaa';
    Chart.defaults.font.family = "'Segoe UI', sans-serif";

    new Chart(document.getElementById('graficoStatus').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Sucesso (No Prazo)', 'Falha (Penalidade)', 'Aguardando Execução'],
            datasets: [{
                data: [<?= $produtividade['concluida'] ?>, <?= $produtividade['atrasada'] ?>, <?= $produtividade['pendente'] ?>],
                backgroundColor: ['#4CAF50', '#f44336', '#FFC107'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: { responsive: true, plugins: { title: { display: true, text: 'Eficiência Operacional', color: '#fff', font: { size: 16 } }, legend: { position: 'bottom' } }, cutout: '70%' }
    });

    new Chart(document.getElementById('graficoRanking').getContext('2d'), {
        type: 'bar',
        data: {
            labels: [<?php foreach($usuarios as $u) echo "'" . htmlspecialchars($u['nome']) . "', "; ?>],
            datasets: [{
                label: 'XP Acumulado',
                data: [<?php foreach($usuarios as $u) echo $u['xp_acumulado'] . ", "; ?>],
                backgroundColor: '#00BCD4',
                borderRadius: 4
            }]
        },
        options: { responsive: true, plugins: { title: { display: true, text: 'Distribuição de Riqueza (Ranking)', color: '#fff', font: { size: 16 } }, legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#333' } }, x: { grid: { display: false } } } }
    });
</script>
</body>
</html>