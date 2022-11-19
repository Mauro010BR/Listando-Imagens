<?php

include "database.php";

if (isset($_FILES['arquivo'])) {
    $arquivo = $_FILES['arquivo'];

    if ($arquivo['error']) {
        die("Falha ao enviar arquivo");
    }
    if ($arquivo['size'] > 2097152)
        die("Arqivo muito Grande, Máximo 2MB");

    $pasta = "arquivos/";
    $nomeDoArquivo = $arquivo['name'];
    $novoNomeDoArquivo = uniqid();
    $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));

    if ($extensao != "jpg" && $extensao != "png")
        die("Tipo de Arquivo não Aceito");

    $path = $pasta . $novoNomeDoArquivo . "." . $extensao;
    $deu_certo = move_uploaded_file($arquivo["tmp_name"], $path);
    if ($deu_certo) {
        $insert = ("INSERT INTO arquivos (nome,path) VALUES ('$nomeDoArquivo','$path')");
        $sql = mysqli_query($conexao, $insert);
        echo "<p>Arquivo enviado com Sucesso!</p>";
    } else
        echo "Falha ao enviar arquivo";
}

$sql_arquivos = ("SELECT * FROM arquivos");

$sql_consulta = mysqli_query($conexao, $sql_arquivos);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Arquivo</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <style>
        h1 {
            text-align: center;
        }

        div {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Envie seu Arquivo</h1>
    <br>
    <div>
        <form enctype="multipart/form-data" action="" method="POST">
            <label for="">Selecione o Arquivo</label>
            <input name="arquivo" type="file">

            <button class="btn btn-success" name="upload" type="submit">Enviar o Arquivo</button>
        </form>
    </div>
    <br>
    <hr>

    <h1>Lista de Arquivos</h1>
    <?php while ($dados = mysqli_fetch_assoc($sql_consulta)) { ?>
        <table class="table">
            <tr>
                <th>Preview</th>
                <th>Arquivo</th>
                <th>Data de Envio</th>
            </tr>
            <tr>
                <td><img height="50" src="<?php echo $dados['path']; ?>" alt=""></td>
                <td><a target="_blank" href="<?php echo $dados['path']; ?>"><?php echo $dados['nome']; ?></a></td>
                <td><?php echo date("d/m/Y H:i", strtotime($dados['data_upload'])); ?></td>
            </tr>
        </table>

    <?php } ?>

</body>

</html>