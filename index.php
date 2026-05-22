<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gamificação de Rotinas | Nova Missão</title>
    <style>
        :root { --bg-base: #121212; --bg-card: #1e1e1e; --bg-input: #2a2a2a; --text-main: #e0e0e0; --text-muted: #9e9e9e; --accent-green: #4CAF50; --accent-hover: #45a049; --accent-gold: #FFD700; --accent-cyan: #00BCD4; }
        * { box-sizing: border-box; font-family: 'Segoe UI', system-ui, sans-serif; margin: 0; padding: 0; }
        body { background-color: var(--bg-base); color: var(--text-main); display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
        .container { background-color: var(--bg-card); padding: 40px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); width: 100%; max-width: 450px; border-top: 4px solid var(--accent-green); }
        h2 { text-align: center; color: var(--accent-green); margin-bottom: 25px; font-weight: 600; letter-spacing: 0.5px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-size: 13px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px; letter-spacing: 0.5px; }
        input, select, textarea { width: 100%; padding: 12px 15px; border: 1px solid #333; border-radius: 6px; background-color: var(--bg-input); color: #fff; font-size: 15px; transition: border 0.3s, box-shadow 0.3s; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: var(--accent-green); box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2); }
        button { width: 100%; padding: 14px; background-color: var(--accent-green); color: white; border: none; border-radius: 6px; font-size: 16px; font-weight: bold; text-transform: uppercase; cursor: pointer; transition: background 0.3s, transform 0.1s; margin-top: 10px; }
        button:hover { background-color: var(--accent-hover); transform: translateY(-1px); }
        button:active { transform: translateY(1px); }
        .sucesso { background-color: rgba(76, 175, 80, 0.1); border: 1px solid var(--accent-green); color: var(--accent-green); padding: 12px; border-radius: 6px; text-align: center; margin-bottom: 20px; font-weight: 500; display: <?php echo isset($_GET['status']) && $_GET['status'] == 'sucesso' ? 'block' : 'none'; ?>; }
        .nav { text-align: center; margin-top: 25px; font-size: 14px; }
        .nav a { text-decoration: none; font-weight: 600; transition: opacity 0.3s; margin: 0 8px; }
        .nav a:hover { opacity: 0.8; }
        .link-painel { color: var(--accent-green); }
        .link-loja { color: var(--accent-gold); }
        .link-bi { color: var(--accent-cyan); }
    </style>
</head>
<body>
<div class="container">
    <h2>Nova Missão</h2>
    <div class="sucesso">Missão registrada com sucesso!</div>
    <form action="processa_tarefa.php" method="POST">
        <div class="form-group">
            <label>Título da Tarefa</label>
            <input type="text" name="titulo" required placeholder="Ex: Fazer a caminhada diária">
        </div>
        <div class="form-group">
            <label>Descrição (Opcional)</label>
            <textarea name="descricao" rows="2" placeholder="Detalhes da missão..."></textarea>
        </div>
        <div class="form-group">
            <label>Responsável</label>
            <select name="responsavel_id">
                <option value="">Ambos (Casa)</option>
                <option value="1">Ricardo</option>
                <option value="2">Wanessa</option>
            </select>
        </div>
        <div class="form-group">
            <label>Data Limite</label>
            <input type="date" name="data_limite" required>
        </div>
        <div class="form-group">
            <label>Recompensa (XP)</label>
            <input type="number" name="xp_recompensa" required value="50">
        </div>
        <button type="submit">Gravar Missão</button>
    </form>
    <div class="nav">
        <a href="painel.php" class="link-painel">Painel ➔</a> | 
        <a href="loja.php" class="link-loja">Loja ➔</a> | 
        <a href="dashboard.php" class="link-bi">BI ➔</a>
    </div>
</div>
</body>
</html>