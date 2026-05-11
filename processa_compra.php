<?php
require 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = (int) $_POST['usuario_id'];
    $custo = (int) $_POST['custo'];

    if (!$usuario_id || !$custo) {
        die("Erro: Dados de resgate corrompidos.");
    }

    try {
        $pdo->beginTransaction();

        // 1. Busca o saldo atual do usuário travando a linha contra concorrência
        $stmt = $pdo->prepare("SELECT xp_acumulado FROM usuarios WHERE id = :uid FOR UPDATE");
        $stmt->execute([':uid' => $usuario_id]);
        $user = $stmt->fetch();

        if (!$user) {
            throw new Exception("Usuário inválido.");
        }

        // 2. Validação de Regra de Negócio (Impede saldo negativo)
        if ($user['xp_acumulado'] < $custo) {
            throw new Exception("Saldo de XP insuficiente para este resgate.");
        }

        // 3. Deduz o XP do banco de dados
        $updateUser = $pdo->prepare("UPDATE usuarios SET xp_acumulado = xp_acumulado - :custo WHERE id = :uid");
        $updateUser->execute([
            ':custo' => $custo,
            ':uid' => $usuario_id
        ]);

        $pdo->commit();
        header("Location: loja.php?status=sucesso");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Transação negada: " . $e->getMessage());
    }
} else {
    die("Acesso restrito.");
}
?>