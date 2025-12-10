<?php

require_once 'src/conexao.php';

// CORREÇÃO: Troquei o "-" pelo "." na linha do ON
$sql = "SELECT 
            vagas.*, 
            empresas.nome AS nome_empresa 
        FROM vagas 
        JOIN empresas ON vagas.empresa_id = empresas.id 
        ORDER BY data_aplicacao DESC";

try {
    $stmt = $pdo->query($sql);
    $vagas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao listar vagas: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <title>Caçador de Vagas 🎯</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🎯 Minhas Candidaturas</h1>
            <a href="cadastrar.php" class="btn btn-success">+ Nova Vaga</a>
        </div>

        <?php foreach ($vagas as $vaga): ?>
        <div class="card card-vaga status-<?php echo $vaga['status']; ?>">
            <div style="display:flex; justify-content:space-between;">
                <div>
                    <div class="vaga-titulo"><?php echo htmlspecialchars($vaga['titulo']); ?></div>
                    <div class="empresa-nome">🏢 <?php echo htmlspecialchars($vaga['nome_empresa']); ?></div>
                </div>
                <div style="text-align:right;">
                    <span class="badge">
                        <?php echo $vaga['status']; ?>
                    </span>
                    <div style="font-size:0.8rem; color:#aaa; margin-top:5px;">
                        <?php echo date('d/m/Y', strtotime($vaga['data_aplicacao'])); ?>
                    </div>
                </div>
            </div>

            <div class="detalhes">
                💰 Previsão: €<?php echo number_format((float)$vaga['salario_pretendido'], 2, ',', '.'); ?> <br>
                <?php if (!empty($vaga['link_vaga'])): ?>
                🔗 <a href="<?php echo $vaga['link_vaga']; ?>" target="_blank">Ver anúncio original</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if (empty($vagas)): ?>
        <p style="text-align:center; color:#999; margin-top: 40px;">Nenhuma vaga cadastrada ainda. Vamos à caça! 🏹</p>
        <?php endif; ?>

    </div>
</body>

</html>