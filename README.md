# Importante

Também é possível fazer o download da [última release](https://github.com/DevelopersRede/prestashop/releases/latest/download/prestashop.zip). Essa versão já contém as dependências, então basta descompactar o pacote e enviá-lo para o servidor da plataforma.

# Módulo Prestashop

Esse é o módulo da plataforma Prestashop.

## Instalação

Esse módulo utiliza o SDK PHP como dependência. Por isso é importante que, assim que o módulo for clonado, seja feita sua instalação:

```bash
composer install
```

## Chave de integração
Antes de iniciar a integração, é necessário gerar a chave de integração no portal da Rede.

1.	Acesse o portal use Rede e realize o login;
2.	Entre no menu e-commerce e selecione a opção chave de integração;
3.	Clique em gerar chave de integração para obtê-la.

Pronto! Chave de integração gerada.

### Instalação

Etapa 1 – Backup dos dados

Por questão de boas práticas realize o backup da loja e banco de dados antes de fazer qualquer tipo de instalação.


Etapa 2 – Instalando a extensão e.Rede via Github

1.	Acesse o repositório da Rede no Github;
2.	Baixe o módulo clicando no botão download;
3.	Descompacte o conteúdo do pacote dentro da pasta modules encontrada na raiz da instalação da loja PrestaShop;
4.	No painel administrativo da loja, vá em: Módulos > Módulos e Serviços, localize o módulo e.Rede e clique em instalar.

Etapa 3 – Configurando a extensão

Após realizar a instalação, será necessário informar os dados de configuração.


Etapa 3.1 - Configurações do método de pagamento

•	Filiação – Número de filiação do estabelecimento na Rede;
•	Senha – Token de autenticação na API do e.Rede adquirido no portal da Rede em: e.Rede > configurações > geração de token;
•	Ambiente – Seleciona o ambiente onde serão realizadas as transações (teste ou produção);
•	Tipo de transação – Seleciona se as transações serão com captura automática ou posterior;
•	Número máximo de parcelas – Quantidade de parcelas mostrada pela loja no ato da compra;
•	Valor mínimo para parcelamento - Valor mínimo do pedido para habilitar o parcelamento;
•	Valor mínimo da parcela – Limita um valor mínimo para cada parcela (parcela mínima 5.00);
•	Nome na fatura - Mensagem que será exibida ao lado do nome do estabelecimento na fatura do portador;


Etapa 4 – Tipos de transações

Nas configurações do módulo, é possível informar o tipo de transação a ser realizada.

•	Com captura automática – a transação é capturada automaticamente no momento da confirmação do pagamento.
•	Com captura posterior – a transação é autorizada, porém permite que a captura seja realizada posteriormente dentro da área administrativa da loja.


Etapa 4.1 - Captura

Após a realização de uma transação de autorização (com captura posterior) é possível capturá-la num prazo de até 29 dias através no painel administrativo da loja.

1.	Acesse Compras > eRede e localize a transação.
2.	Clique no botão Capturar para efetivar a captura da transação.

Etapa 4.3 – Estorno

O estorno só é possível para transações que tenham sido autorizadas ou capturadas no mesmo dia. Após esse prazo, o estorno deverá ser realizado via portal da Rede ou arquivo EDI.

1.	Acesse Compras > eRede e localize a transação.
2.	Clique no botão Estornar para efetivar o estorno da transação.


