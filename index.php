<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gamificação de Rotinas</title>
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #121212; color: #e0e0e0; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background-color: #1e1e1e; padding: 30px; border-radius: 10px; box-shadow: 0 8px 16px rgba(0,0,0,0.5); width: 100%; max-width: 400px; }
        h2 { text-align: center; color: #4CAF50; margin-bottom: 20px; }
        label { font-size: 14px; font-weight: bold; margin-bottom: 5px; display: block; color: #aaa; }
        input, select, textarea { width: 100%; padding: 10px; margin-bottom: 15px; border: none; border-radius: 5px; background-color: #2c2c2c; color: #fff; font-size: 14px; }
        input:focus, select:focus, textarea:focus { outline: 2px solid #4CAF50; }
        button { width: 100%; padding: 12px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background 0.3s; }
        button:hover { background-color: #45a049; }
        .sucesso { background-color: #1b5e20; color: #c8e6c9; padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 15px; display: <?php echo isset($_GET['status']) && $_GET['status'] == 'sucesso' ? 'block' : 'none'; ?>; }
    </style>
</head>
<body>

<div class="container">
    <h2>Nova Missão</h2>
    
    <div class="sucesso">Tarefa registrada com sucesso!</div>

    <form action="processa_tarefa.php" method="POST">
        <label>Título da Tarefa</label>
        <input type="text" name="titulo" required placeholder="Ex: Fazer a caminhada diária">

        <label>Descrição (Opcional)</label>
        <textarea name="descricao" rows="2" placeholder="Detalhes da missão..."></textarea>

        <label>Responsável</label>
        <select name="responsavel_id">
            <option value="">Ambos</option>
            <option value="1">Ricardo</option>
            <option value="2">Wanessa</option>
        </select>

        <label>Data Limite</label>
        <input type="date" name="data_limite" required>

        <label>Recompensa (XP)</label>
        <input type="number" name="xp_recompensa" required value="50">

        <button type="submit">Gravar Missão no Banco</button>
    </form>
    <div style="text-align: center; margin-top: 15px;">
    <a href="painel.php" style="color: #4CAF50; text-decoration: none; font-weight: bold;">Ir para o Painel de Missões ➔</a>
</div>
</div>

</body>
</html>