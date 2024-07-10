<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Notas de Serviço</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        body{
            background-image: url("assets/imgs/bg.png");
            background-size: cover; /* Faz a imagem cobrir todo o elemento */
    background-repeat: no-repeat; /* Evita que a imagem se repita */
    background-position: center center; /* Centraliza a imagem */
    background-attachment: fixed; /* Mantém a imagem fixa enquanto a página rola */
        }
        .nota-item {
            cursor: pointer;
            margin-bottom: 10px;
            background-color: #383838;
            border-radius: 8px;
            padding: 15px;
            color: white;
            transition: background-color 0.3s, box-shadow 0.3s;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .nota-item:hover {
            background-color: rgb(82, 82, 82);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .modal-body p {
            margin-bottom: 5px;
        }

        .logo {
            text-align: center;
        }

        .notas {
            overflow: auto;
            height: 450px;
        }

        @media (max-width: 600px) {
            .notas {
                overflow: auto;
                height: 500px;
            }
        }

        .painel-href {
            background-color: #007bff;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .painel-href:hover {
            background-color: #0056b3;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .painel-href a {
            color: #fff;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .index-href {
            background-color: #007bff;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            position: fixed;
            bottom: 20px;
            right: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .index-href:hover {
            background-color: #0056b3;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .index-href a {
            color: #fff;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .painel-header {
            color: white;
            background-color: #383838;
            border-radius: 90px;
            padding: 10px 20px; /* Ajuste o padding conforme necessário */
            text-align: center; /* Centraliza o texto */
            font-size: 1.5rem; /* Tamanho da fonte */
            font-family: Arial, sans-serif; /* Fonte */
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.75); /* sombra */
        }
    </style>
</head>
<body>
    <div class="container">
    <h2 class="painel-header" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">Painel de Notas de Serviço</h2>    
    <div id="notas" class="notas">
            <?php
            // Conexão com o banco de dados
            $conn = new mysqli("localhost", "oficina", "K4rr1nh0", "oficina");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM notas_servico";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="nota-item" data-toggle="modal" data-target="#modalDetalhesNota" data-id="' . $row['id'] . '">';
                    echo '<p><strong>Nome do Cliente:</strong> ' . htmlspecialchars($row['nome_cliente']) . '</p>';
                    echo '</div>';
                }
            } else {
                echo "Nenhuma nota de serviço encontrada.";
            }

            $conn->close();
            ?>
        </div>
    </div>

    <!-- Modal para exibir detalhes da nota de serviço -->
    <div class="modal fade" id="modalDetalhesNota" tabindex="-1" role="dialog" aria-labelledby="modalDetalhesNotaLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetalhesNotaLabel">Detalhes da Nota de Serviço</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detalhesNota">
                    <div class="logo">
                        <img src="assets/imgs/motostars_logo.png" width="100">
                    </div>
                    <p><strong>Nome do Cliente:</strong> <span id="modalCliente"></span></p>
                    <p><strong>Número de Contato:</strong> <span id="modalContato"></span></p>
                    <p><strong>Endereço:</strong> <span id="modalEndereco"></span></p>
                    <p><strong>Data do Serviço:</strong> <span id="modalData"></span></p>
                    <p><strong>Quantidade de Peças:</strong> <span id="modalQuantidade"></span></p>
                    <p><strong>Descrição do Serviço:</strong> <span id="modalDescricao"></span></p>
                    <p><strong>Valor:</strong> R$ <span id="modalValor"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btnGerarPDF">Gerar PDF</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="painel-href">
        <a href="index.html"><i class="fas fa-home"></i></a>
    </div>


    <script>
        $(document).ready(function() {
            $('.nota-item').click(function() {
                var idNota = $(this).data('id');
                $.ajax({
                    url: 'detalhes_nota.php',
                    type: 'GET',
                    data: { id: idNota },
                    success: function(response) {
                        $('#modalCliente').text(response.nome_cliente);
                        $('#modalContato').text(response.numero_contato);
                        $('#modalEndereco').text(response.endereco);
                        $('#modalData').text(response.data_servico);
                        $('#modalQuantidade').text(response.quantidade);
                        $('#modalDescricao').text(response.descriminacao);
                        $('#modalValor').text(response.valor);

                        $('#btnGerarPDF').data('nota', response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro ao carregar detalhes da nota:', error);
                    }
                });
            });

            $('#btnGerarPDF').click(function() {
                var nota = $(this).data('nota');
                if (nota) {
                    var queryString = $.param({
                        nome_cliente: nota.nome_cliente,
                        numero_contato: nota.numero_contato,
                        endereco: nota.endereco,
                        quantidade: nota.quantidade,
                        descriminacao: nota.descriminacao,
                        valor: nota.valor
                    });
                    window.open('assets/src/doc.php?' + queryString, '_blank');
                }
            });
        });
    </script>
</body>
</html>
