<?php
session_start();
include "DLL.php";

if (!isset($_POST["cadastrar"])) {
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Cadastro</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <main class="container">
            <form action="cadastro.php" method="post">
                <h1>Cadastro de Usuário</h1>

                <div class="input-box">
                    <label>Nome:</label><br>
                    <input type="text" name="nome_usuario" required>
                </div>
            
                <div class="input-box">
                    <label>Email:</label><br>
                    <input type="text" name="email_usuario" required maxlength="100">
                </div>

                <div class="input-box">
                    <label>Telefone:</label><br>
                    <input type="text" name="telefone_usuario" maxlength="15">
                </div>

                <div class="input-box">
                    <label>Data de Nascimento:</label><br>
                    <input type="date" name="data_nascimento">
                </div>

                <div class="input-box">
                    <label>Senha:</label><br>
                    <input type="password" name="senha_usuario" required>
                </div>

                <div class="input-box">
                    <label>Confirmar Senha:</label><br>
                    <input type="password" name="confirma_senha" required>
                </div>

                <button type="submit" name="cadastrar">Cadastrar</button>
            </form>
        </main>
    </body>
    </html>
    <?php
    exit();
}

extract($_POST);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<main class="container">
<?php

if ($senha_usuario !== $confirma_senha) {
    echo "<p>As senhas não conferem. <a href='cadastro.php'>Tente novamente</a></p>";
    echo "</main></body></html>";
    exit();
}

$sql_verifica = "SELECT * FROM usuarios WHERE email = '$email_usuario'";
$resultado = banco($server, $user, $password, $db, $sql_verifica);

$usuario_existente = $resultado->fetch_assoc();

if ($usuario_existente) {
    echo "<p>O email <b>$email_usuario</b> já está cadastrado. <a href='cadastro.php'>Tente novamente</a></p>";
    echo "</main></body></html>";
    exit();
}

$sql_inserir = "
    INSERT INTO usuarios (nome, email, telefone, data_nascimento, senha)
    VALUES ('$nome_usuario', '$email_usuario', '$telefone_usuario', '$data_nascimento', '$senha_usuario')
";

if (banco($server, $user, $password, $db, $sql_inserir)) {
    echo "<p>Usuário <b>$nome_usuario</b> cadastrado com sucesso! <a href='index.php'>Fazer login</a></p>";
} else {
    echo "<p>Erro ao cadastrar usuário!</p>";
}

echo "</main></body></html>";
?>

