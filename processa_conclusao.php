<?php
require 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tarefa_id = (int) $_POST['tarefa_id'];
    $usuario_id = (int) $_POST['usuario_id'];
    $hoje = date('Y-m-d');

    if (!$tarefa_id || !$usuario_id) {
        die("Erro: Dados incompletos.");
    }

    try {
        // Inicia a transação bancária de dados
        $pdo->beginTransaction();

        // 1. Busca os detalhes da tarefa e trava a linha (FOR UPDATE)
        $stmt = $pdo->prepare("SELECT data_limite, xp_recompensa FROM tarefas WHERE id = :id FOR UPDATE");
        $stmt->execute([':id' => $tarefa_id]);
        $tarefa = $stmt->fetch();

        if (!$tarefa) {
            throw new Exception("Tarefa não encontrada.");
        }

        // 2. Motor de Regras (No prazo ganha XP, Atrasado perde XP)
        $pontos = (int) $tarefa['xp_recompensa'];
        $status_final = 'concluida';

        if ($hoje > $tarefa['data_limite']) {
            $pontos = -$pontos; // Transforma o ganho em penalidade
            $status_final = 'atrasada';
        }

        // 3. Atualiza o status da tarefa e quem efetivamente concluiu
        $updateTarefa = $pdo->prepare("UPDATE tarefas SET status = :status, responsavel_id = :uid WHERE id = :id");
        $updateTarefa->execute([
            ':status' => $status_final,
            ':uid' => $usuario_id,
            ':id' => $tarefa_id
        ]);

        // 4. Atualiza o saldo de XP do usuário
        $updateUser = $pdo->prepare("UPDATE usuarios SET xp_acumulado = xp_acumulado + :pontos WHERE id = :uid");
        $updateUser->execute([
            ':pontos' => $pontos,
            ':uid' => $usuario_id
        ]);

        // Confirma a transação
        $pdo->commit();

        header("Location: painel.php?status=ok");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Falha na regra de negócio: " . $e->getMessage());
    }
} else {
    die("Acesso negado.");
}
?>