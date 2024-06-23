<?php
//Captura o modelo selecionado
$modelo = $_POST['modelo'];

//Verifica se o cliente selecionou uma foto no formulário ou deixou em branco
if($_FILES['foto']['size'] > 0){
    //Obtém o conteúdo da imagem (conteúdo binário)
    $conteudo = file_get_contents($_FILES['foto']['tmp_name']);
    
    //Obtém o tipo da imagem enviada (jpg, png)
    $tipo = pathinfo($_FILES['foto']['tmp_name'], PATHINFO_EXTENSION);
    
    //Gera a imagem em base64 para poder colocar na tag <img> do curriculo
    $foto = 'data:image/' . $tipo . ';base64,' . base64_encode($conteudo);
} else {
    //Se o cliente não selecionou uma foto, usamos a imagem padrão
    $foto = "img/avatar-1.png";
}
//Calcula a idade do candidato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data_nascimento = $_POST["data_nascimento"];
    $data_nascimento_timestamp = strtotime($data_nascimento);
    $hoje_timestamp = time();
    $idade = floor(($hoje_timestamp - $data_nascimento_timestamp) / (365.25 * 24 * 60 * 60));
    
    
}


//Gera um array com os dados a serem enviados para impressão no currículo
//Cada elemento do array recebe o valor de um campo do formulário
$dados = array(
    'foto' => $foto,
    'nome' => $_POST['nome'],
    'cargo' => $_POST['cargo'],
    'endereco' => $_POST['endereco'],
    'telefone' => $_POST['telefone'],
    'email' => $_POST['email'],
    'resumo' => $_POST['resumo'],
    'tecnologias' => $_POST['tecnologias'],
     //O campo data_nascimento é enviado no formato 'aaaa-mm-dd'
     //Adiciona a data de nascimento ao array de dados   
    'formacoes' => isset($_POST['formacao-curso']) ?
                   array(
                        'cursos' => $_POST['formacao-curso'],
                        'instituicoes' => $_POST['formacao-instituicao'],
                        'conclusoes' => $_POST['formacao-conclusao']
                    ) : null, //Se o usuário não adicionou nenhuma formação, esse elemento ficará nulo
    'experiencias' => isset($_POST['experiencia-cargo']) ? 
                      array(
                        'cargos' => $_POST['experiencia-cargo'],
                        'empresas' => $_POST['experiencia-empresa'],
                        'inicios' =>  $_POST['experiencia-inicio'],
                        'fins' =>  $_POST['experiencia-fim'],
                      ) : null //Se o usuário não adicionou nenhuma experiência, esse elemento ficará nulo
);


//Carrega o arquivo referente ao modelo de currículo selecionado
//Quando faz isso, poderemos imprimir o conteúdo da variável $dados no currículo
require_once("modelos/{$modelo}.php");