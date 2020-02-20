<?php
    if ($_POST) {
        try {
            require "req/conexao.php";
           
            if (empty($_FILES["imagem"]["name"])) {
                $url_imagem = 'img/blog/default.jpg';
            } else if ($_FILES["imagem"]["error"] === 0) {
                $nomeArquivo = $_FILES["imagem"]["name"];
                $nomeTemp = $_FILES["imagem"]["tmp_name"];
                $url_imagem = "img/blog/" . $nomeArquivo;
        
                move_uploaded_file($nomeTemp, "./" . $url_imagem);
            }
            
            $consulta = $conexao->prepare("INSERT INTO noticias (titulo, descricao, categoria, url_imagem, data_criacao, id_escritor) VALUES (:titulo, :descricao, :categoria, :urlImagem, :dataAtual, :id_escritor)");
            $inseriu = $consulta->execute([
                ':titulo' => $_POST["titulo"],
                ':descricao' => $_POST["descricao"],
                ':categoria' => $_POST["categoria"],
                ':urlImagem' => $url_imagem,
                ':dataAtual' => date('Y-m-d'),
                ':id_escritor' => $_POST["id_escritor"],
            ]);
    
            $conexao = null;
    
            if ($inseriu) {
                header("Location: painel_admin.php");
            } 
        } catch (PDOException $erro) {
            echo $erro->getMessage();
        }
    }

    include 'head.php';
?>

<div class="container d-flex align-items-center justify-content-center" style="height: 90vh">
    <div class="col-8 border rounded p-4" style="background-color: #f8f8f8">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="tituloInput">Titulo</label>
                <input id="tituloInput" type="text" name="titulo" class="form-control">
            </div>
            <div class="form-group">
                <label for="descricaoInput">Descriçāo</label>
                <textarea name="descricao" id="descricaoInput" cols="30" rows="10" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <select name="categoria" id="categoriaInput" class="form-control">
                    <option disabled selected>Escolha uma categoria</option>
                    <option value="Moda">Moda</option>
                    <option value="Lazer">Lazer</option>
                    <option value="Saúde">Saúde</option>
                    <option value="Esporte">Esporte</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="imagemInput" class="btn btn-secondary col-12">Imagem de capa</label>
                <input type="file" name="imagem" id="imagemInput" class="d-none">
            </div>

            <input type="hidden" name="id_escritor" value="<?= $_SESSION["usuario"]["id"] ?>">
            <button type="submit" class="btn btn-success col-12 mt-2">Adicionar</button>
        </form>
    </div>
</div>
</body>
</html>