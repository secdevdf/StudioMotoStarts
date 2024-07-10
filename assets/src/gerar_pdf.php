<?php
require '../vendor/autoload.php';

use Dompdf\Dompdf;

// Função para sanitizar dados de entrada
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Verifica se o formulário foi enviado via POST ou GET e os campos estão definidos
$request_method = $_SERVER["REQUEST_METHOD"];
$data_received = false;
$nome_cliente = $numero_contato = $endereco = $quantidade = $descriminacao = $valor = "";

if (($request_method == "POST" || $request_method == "GET") &&
    isset($_REQUEST['nome_cliente']) &&
    isset($_REQUEST['numero_contato']) &&
    isset($_REQUEST['endereco']) &&
    isset($_REQUEST['quantidade']) &&
    isset($_REQUEST['descriminacao']) &&
    isset($_REQUEST['valor'])) {

    $nome_cliente = sanitize_input($_REQUEST['nome_cliente']);
    $numero_contato = sanitize_input($_REQUEST['numero_contato']);
    $endereco = sanitize_input($_REQUEST['endereco']);
    $data_servico = date('Y-m-d H:i:s'); // Formato adequado para MySQL datetime
    $quantidade = sanitize_input($_REQUEST['quantidade']);
    $descriminacao = sanitize_input($_REQUEST['descriminacao']);
    // Substitui vírgula por ponto no valor
    $valor = str_replace(',', '.', sanitize_input($_REQUEST['valor']));
    
    $data_received = true;
}

if ($data_received) {
    // Caminho absoluto para a imagem da logo
    $logo_path = '../imgs/motostars_logo.png';

    // Converte a imagem da logo para Base64
    $logo_data = base64_encode(file_get_contents($logo_path));
    $logo_src = 'data:image/png;base64,' . $logo_data;

    // Cria o objeto Dompdf
    $dompdf = new Dompdf();

    // Estilos CSS para o PDF
    $css = "
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            text-align: left;
        }
        .header {
            text-align: right;
            margin-bottom: 20px;
        }
        .logo {
            text-align: center;
            margin-top: -10px;
        }
        h1 {
            margin-bottom: 20px;
        }
        p {
            margin-bottom: 10px;
        }
        .content {
            margin-top: -160px;
        }
        .bottom-date {
            text-align: right;
        }
    ";

    // HTML para o PDF com estilos aplicados
    $html = "
        <style>$css</style>
        <div class='header'>
            <img src='$logo_src' alt='Logo da Empresa' width='150'>
        </div>
        <div class='content'>
            <h1>Nota de Serviço</h1>
            <p><strong>Nome do Cliente:</strong> $nome_cliente</p>
            <p><strong>Número de Contato:</strong> $numero_contato</p>
            <p><strong>Endereço:</strong> $endereco</p>
            <p><strong>Quantidade de Peças:</strong> $quantidade</p>
            <p><strong>Descrição do Serviço:</strong> $descriminacao</p>
            <p><strong>Valor:</strong> R$ $valor</p>
        </div>
        <div class='bottom-date'>
            <p>" . date('d/m/Y H:i:s') . "</p>
        </div>
    ";

    // Carrega o HTML no Dompdf
    $dompdf->loadHtml($html);

    // Define o papel e a orientação
    $dompdf->setPaper('A4', 'portrait');

    // Renderiza o PDF
    $dompdf->render();

    // Gera o nome do arquivo PDF
    $file_name = "nota_servico_$numero_contato.pdf";

    // Gera o PDF para download ou visualização
    $dompdf->stream($file_name);

    // Conexão com o banco de dados (exemplo com MySQL)
    $servername = "localhost";
    $username = "oficina";
    $password = "K4rr1nh0";
    $dbname = "oficina";

    // Cria conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Checa conexão
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insere dados no banco de dados usando prepared statement para evitar SQL Injection
    $stmt = $conn->prepare("INSERT INTO notas_servico (nome_cliente, numero_contato, endereco, data_servico, quantidade, descriminacao, valor) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssiss", $nome_cliente, $numero_contato, $endereco, $data_servico, $quantidade, $descriminacao, $valor);

    if ($stmt->execute()) {
        echo "Nota de serviço criada com sucesso";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

} else {
    // Caso o formulário não tenha sido enviado corretamente
    echo "Erro: Formulário incompleto ou dados inválidos.";
}
?>
