<?php
require 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $responsavel_id = !empty($_POST['responsavel_id']) ? $_POST['responsavel_id'] : NULL;
    $data_limite = $_POST['data_limite'];
    $xp_recompensa = (int) $_POST['xp_recompensa'];

    // Validação bruta: Não permite envio de lixo vazio
    if (empty($titulo) || empty($data_limite) || $xp_recompensa <= 0) {
        die("Erro de validação: Dados obrigatórios ausentes ou XP inválido.");
    }

    $sql = "INSERT INTO tarefas (titulo, descricao, responsavel_id, data_limite, xp_recompensa) 
            VALUES (:titulo, :descricao, :responsavel_id, :data_limite, :xp_recompensa)";

    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([
            ':titulo' => $titulo,
            ':descricao' => $descricao,
            ':responsavel_id' => $responsavel_id,
            ':data_limite' => $data_limite,
            ':xp_recompensa' => $xp_recompensa
        ]);
        
        // Retorna para o formulário após o sucesso
        header("Location: index.php?status=sucesso");
        exit;
    } catch (PDOException $e) {
        die("Falha ao gravar no banco de dados: " . $e->getMessage());
    }
} else {
    die("Acesso negado. Rota exclusiva para POST.");
}
?>