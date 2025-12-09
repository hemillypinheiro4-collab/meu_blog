<?php
session_start();

if (!isset($_SESSION["status"]) || $_SESSION["status"] != "logado") {
    header("Location: index.php");
    exit();
}

include "DLL.php";

$id_usuario = (int) $_SESSION["id_usuario"];

$sql_usuario = "
    SELECT nome, email, data_de_cadastro
    FROM usuarios
    WHERE id_usuario = $id_usuario
";
$resultado_usuario = banco($server, $user, $password, $db, $sql_usuario);
$usuario = $resultado_usuario->fetch_assoc();

$sql_pub = "
    SELECT COUNT(*) AS total
    FROM publicacoes
    WHERE id_usuario = $id_usuario
";
$res_pub = banco($server, $user, $password, $db, $sql_pub);
$total = (int) $res_pub->fetch_assoc()["total"];

$sql_posts = "
    SELECT conteudo, data_publicacao
    FROM publicacoes
    WHERE id_usuario = $id_usuario
    ORDER BY id_publicacao DESC
    LIMIT 5
";
$res_posts = banco($server, $user, $password, $db, $sql_posts);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<main class="perfil-page">

    <div class="button">
        <a href="principal.php">← Voltar</a>
    </div>

    <?php if ($usuario): ?>

        <div class="perfil-header">
            <div class="perfil-avatar"></div>

            <h2 class="perfil-nome">
                <?= htmlspecialchars($usuario["nome"]) ?>
            </h2>

            <p class="perfil-meta">
                <strong>Email:</strong>
                <?= htmlspecialchars($usuario["email"]) ?>
            </p>

            <p class="perfil-meta">
                <strong>Membro desde:</strong>
                <?= databr(substr($usuario["data_de_cadastro"], 0, 10)) ?>
            </p>

            <p class="perfil-stats">
                <strong>Total de publicações:</strong>
                <?= $total ?>
            </p>
        </div>

        <div class="perfil-divider">Minhas últimas publicações</div>

        <?php if ($res_posts->num_rows == 0): ?>
            <p>Você ainda não publicou nada.</p>
        <?php else: ?>
            <?php while ($p = $res_posts->fetch_assoc()): ?>
                <div class="perfil-publicacao">
                    <p><?= nl2br(htmlspecialchars($p["conteudo"])) ?></p>
                    <small>
                        Data: <?= date("d/m/Y H:i", strtotime($p["data_publicacao"])) ?>
                    </small>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>

    <?php else: ?>
        <p>Usuário não encontrado.</p>
    <?php endif; ?>

</main>

</body>
</html>
