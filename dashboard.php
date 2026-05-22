<?php
require 'conexao.php';

// Coleta de Dados para o BI (Regras de Negócio Analíticas)
// 1. Ranking de XP
$sqlUsuarios = "SELECT nome, xp_acumulado FROM usuarios ORDER BY xp_acumulado DESC";
$usuarios = $pdo->query($sqlUsuarios)->fetchAll();

// 2. Produtividade Global (Taxa de Sucesso vs Falha)
$sqlStatus = "SELECT status, COUNT(*) as total FROM tarefas GROUP BY status";
$statusTarefas = $pdo->query($sqlStatus)->fetchAll();

$produtividade = ['pendente' => 0, 'concluida' => 0, 'atrasada' => 0];
foreach ($statusTarefas as $row) {
    $produtividade[$row['status']] = (int) $row['total'];
}

// 3. Custo total de XP estagnado no mercado (soma do catálogo)
$sqlInflacao = "SELECT SUM(custo_xp) as custo_total FROM recompensas";
$inflacao = $pdo->query($sqlInflacao)->fetch();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>BI - Gamificação de Rotinas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, sans-serif; }
        body { background-color: #121212; color: #e0e0e0; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; }
        h2 { border-bottom: 2px solid #00BCD4; padding-bottom: 10px; color: #00BCD4; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px; }
        .card-grafico { background-color: #1e1e1e; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.3); }
        .kpi { background-color: #1e1e1e; padding: 20px; border-radius: 8px; text-align: center; border-left: 5px solid #00BCD4; }
        .kpi h3 { margin: 0; font-size: 32px; color: #00BCD4; }
        .kpi p { margin: 5px 0 0 0; color: #aaa; text-transform: uppercase; font-size: 12px; font-weight: bold; }
        .nav { text-align: center; margin-top: 30px; }
        .nav a { color: #00BCD4; text-decoration: none; font-weight: bold; margin: 0 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Dashboard Analítico (Business Intelligence)</h2>
        
        <div class="grid">
            <div class="kpi">
                <h3><?= array_sum($produtividade) ?></h3>
                <p>Total de Missões Cadastradas</p>
            </div>
            <div class="kpi">
                <h3><?= $inflacao['custo_total'] ?? 0 ?> XP</h3>
                <p>Custo para limpar a Loja</p>
            </div>
        </div>

        <div class="grid">
            <div class="card-grafico">
                <canvas id="graficoStatus"></canvas>
            </div>
            <div class="card-grafico">
                <canvas id="graficoRanking"></canvas>
            </div>
        </div>

        <div class="nav">
            <a href="index.php">Nova Missão</a> | 
            <a href="painel.php">Painel</a> | 
            <a href="loja.php">Loja</a>
        </div>
    </div>

    <script>
        // Gráfico de Produtividade (Pizza)
        const ctxStatus = document.getElementById('graficoStatus').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Concluídas (No Prazo)', 'Atrasadas (Penalidade)', 'Pendentes'],
                datasets: [{
                    data: [<?= $produtividade['concluida'] ?>, <?= $produtividade['atrasada'] ?>, <?= $produtividade['pendente'] ?>],
                    backgroundColor: ['#4CAF50', '#f44336', '#FFC107'],
                    borderWidth: 0
                }]
            },
            options: { plugins: { title: { display: true, text: 'Taxa de Produtividade vs Falha', color: '#fff' }, legend: { labels: { color: '#fff' } } } }
        });

        // Gráfico de Ranking (Barras)
        const ctxRanking = document.getElementById('graficoRanking').getContext('2d');
        new Chart(ctxRanking, {
            type: 'bar',
            data: {
                labels: [<?php foreach($usuarios as $u) echo "'" . htmlspecialchars($u['nome']) . "', "; ?>],
                datasets: [{
                    label: 'XP Acumulado',
                    data: [<?php foreach($usuarios as $u) echo $u['xp_acumulado'] . ", "; ?>],
                    backgroundColor: '#00BCD4'
                }]
            },
            options: { plugins: { title: { display: true, text: 'Ranking de Usuários', color: '#fff' }, legend: { display: false } }, scales: { y: { ticks: { color: '#fff' } }, x: { ticks: { color: '#fff' } } } }
        });
    </script>
</body>
</html>