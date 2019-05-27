<h2>Obrigado por seu pedido</h2>

<table style="width:80%; margin: 20px auto;">
    {if $return_code_authorization == '00'}
        <caption>Seu pagamento foi concluído e seu pedido encaminhado para processamento. Por favor, anote os dados
            abaixo para futura consulta:
        </caption>
        <tr>
            <td>Seu Pedido:</td>
            <td>{$id_order}</td>
        </tr>
        <tr>
            <td>ID Transação:</td>
            <td>{$transaction_id}</td>
        </tr>
        <tr>
            <td>Código da autorização:</td>
            <td>{$authorization_code}</td>
        </tr>
    {else}
        <caption>
            Percebemos algum problema com seu pedido, mas ele foi enviado para processamento e entraremos em contato em
            breve.
            Caso tenha alguma dúvida, não hesite em entrar em <a
                    href="{$link->getPageLink('contact', true)|escape:'html'}">contato conosco</a>.
        </caption>
    {/if}
</table>