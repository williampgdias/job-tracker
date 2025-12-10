<?php
require_once 'src/conexao.php';

// Verificar se tem ID na URL
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

// Processar a Atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeEmpresa = trim($_POST['empresa']);
    $titulo = trim($_POST['titulo']);
    $link = trim($_POST['link']);
    $salario = $_POST['salario'] ?: 0;
    $status = $_POST['status'];

    if (!empty($nomeEmpresa) && !empty($titulo)) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("SELECT id FROM empresas WHERE nome = ?");
            $stmt->execute([$nomeEmpresa]);
            $empresa = $stmt->fetch();

            if ($empresa) {
                $empresaId = $empresa['id'];
            } else {
                $stmt = $pdo->prepare("INSERT INTO empresas (nome) VALUES (?)");
                $stmt->execute([$nomeEmpresa]);
                $empresaId = $pdo->lastInsertId();
            }

            $sql = "UPDATE vagas SET
                        empresa_id = ?,
                        titulo = ?,
                        link_vaga = ?,
                        salario_pretendido = ?,
                        status = ?
                    WHERE id = ?";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$empresaId, $titulo, $link, $salario, $status, $id]);

            $pdo->commit();
            header('Location: index.php');
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erro ao atualizar: " . $e->getMessage());
        }
    }
}

// Buscar os dados para preencher o formulário
$sql = "SELECT vagas.*, empresas.nome AS nome_empresa
        FROM vagas
        JOIN empresas ON vagas.empresa_id = empresas.id
        WHERE vagas.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$vaga = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vaga) {
    die("Vaga não encontrada!");
}
?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <title>Editar Vaga ✏️</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="form-container">
        <div class="card card-form">
            <h2>✏️ Editar Candidatura</h2>

            <form method="POST">
                <label>Nome da Empresa</label>
                <input type="text" name="empresa" value="<?php echo htmlspecialchars($vaga['nome_empresa']); ?>"
                    required>

                <label>Cargo / Título</label>
                <input type="text" name="titulo" value="<?php echo htmlspecialchars($vaga['titulo']); ?>" required>

                <label>Link da Vaga</label>
                <input type="url" name="link" value="<?php echo htmlspecialchars($vaga['link_vaga']); ?>">

                <label>Salário Pretendido (€)</label>
                <input type="number" name="salario" step="0.01"
                    value="<?php echo htmlspecialchars($vaga['salario_pretendido']); ?>">

                <label>Status Atual</label>
                <select name="status">
                    <option value="Aplicado" <?php echo $vaga['status'] == 'Aplicado' ? 'selected' : ''; ?>>📩 Aplicado
                    </option>
                    <option value="Entrevista" <?php echo $vaga['status'] == 'Entrevista' ? 'selected' : ''; ?>>🗣️
                        Entrevista</option>
                    <option value="Teste" <?php echo $vaga['status'] == 'Teste' ? 'selected' : ''; ?>>💻 Teste Técnico
                    </option>
                    <option value="Oferta" <?php echo $vaga['status'] == 'Oferta' ? 'selected' : ''; ?>>🎉 Oferta
                        Recebida</option>
                    <option value="Rejeitado" <?php echo $vaga['status'] == 'Rejeitado' ? 'selected' : ''; ?>>❌
                        Rejeitado</option>
                </select>

                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>

                <a href="index.php" class="link-voltar">Cancelar</a>
            </form>
        </div>
    </div>
</body>

</html>