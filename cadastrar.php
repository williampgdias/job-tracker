<?php
require_once 'src/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeEmpresa = trim($_POST['empresa']);
    $titulo = trim($_POST['titulo']);
    $link = trim($_POST['link']);
    $salario = $_POST['salario'] ?: 0;
    $status = $_POST['status'];

    if (!empty($nomeEmpresa) && !empty($titulo)) {
        try {
            $pdo->beginTransaction();

            // Lógica da empresa (verifica se já existe)
            $stmt = $pdo->prepare("SELECT id FROM empresas WHERE nome = ?");
            $stmt->execute([$nomeEmpresa]);
            $empresa = $stmt->fetch();

            if ($empresa) {
                // Empresa já existe, usamos a id dela
                $empresaId = $empresa['id'];
            } else {
                // Empresa nova, criamos agora
                $stmt = $pdo->prepare("INSERT INTO empresas (nome) VALUES (?)");
                $stmt->execute([$nomeEmpresa]);
                $empresaId = $pdo->lastInsertId();
            }

            // Salva a Vaga vinculada à Empresa
            $sql = "INSERT INTO vagas (empresa_id, titulo, link_vaga, salario_pretendido, status) 
                    VALUES (?,?,?,?,?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$empresaId, $titulo, $link, $salario, $status]);

            // Confirma tudo
            $pdo->commit();

            // Volta pra home
            header('Location: index.php');
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erro ao salvar:" . $e->getMessage());
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <title>Nova Vaga 🎯</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="form-container">
        <div class="card card-form">
            <h2>🚀 Nova Candidatura</h2>

            <form method="POST">
                <label>Nome da Empresa</label>
                <input type="text" name="empresa" placeholder="Ex: Google, Farfetch..." required autofocus>

                <label>Cargo / Título</label>
                <input type="text" name="titulo" placeholder="Ex: Backend PHP Developer" required>

                <label>Link da Vaga (Opcional)</label>
                <input type="url" name="link" placeholder="https://linkedin.com/...">

                <label>Salário Pretendido (€)</label>
                <input type="number" name="salario" step="0.01" placeholder="Ex: 1500.00">

                <label>Status Atual</label>
                <select name="status">
                    <option value="Aplicado">📩 Aplicado</option>
                    <option value="Entrevista">🗣️ Entrevista</option>
                    <option value="Teste">💻 Teste Técnico</option>
                    <option value="Oferta">🎉 Oferta Recebida</option>
                    <option value="Rejeitado">❌ Rejeitado</option>
                </select>

                <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Salvar Vaga</button>

                <a href="index.php" class="link-voltar">Voltar</a>
            </form>
        </div>
    </div>
</body>

</html>