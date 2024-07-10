# Instalar Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '93b5444ff8158b9a5f159b1ec4017a2bdf4ffaf0b3b9df2e153fb5f0e5a8e4dc') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
sudo mv composer.phar /usr/local/bin/composer

# Preparar projeto PHP
mkdir oficina-projeto
cd oficina-projeto
composer init

# Instalar Dompdf
composer require dompdf/dompdf

# Criar arquivo PHP para gerar PDF
echo "<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;
\$dompdf = new Dompdf();
\$html = \"<h1>Nota de Serviço</h1><p>Exemplo de nota de serviço gerada com Dompdf</p>\";
\$dompdf->loadHtml(\$html);
\$dompdf->setPaper('A4', 'portrait');
\$dompdf->render();
\$dompdf->stream('nota_servico_exemplo.pdf');
?>" > gerar_pdf.php

# Executar arquivo PHP
php gerar_pdf.php
