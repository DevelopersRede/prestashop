<div class="panel">
    <div class="panel-heading">
        <i class="icon-money"></i>&nbsp;Detalhes do pagamento na Rede
    </div>

    <div class="row">
        <table class="rede table table-striped table-bordered">
            <caption>Detalhes da transação</caption>
            <thead>
            <tr>
                <th>Nome</th>
                <th>Valor</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="left key">Transaction ID</td>
                <td class="right value">{$transaction_id}</td>
            </tr>
            <tr>
                <td class="left key">Método de autorização</td>
                <td class="right value">{$payment_method_text}</td>
            </tr>
            <tr>
                <td class="left key">ID Pedido</td>
                <td class="right value">{$id_order}</td>
            </tr>
            <tr>
                <td class="left key">Parcelas</td>
                <td class="right value">{$installments}</td>
            </tr>
            <tr>
                <td class="left key">BIN</td>
                <td class="right value">{$bin}</td>
            </tr>
            <tr>
                <td class="left key">Últimos 4 dígitos</td>
                <td class="right value">{$last4}</td>
            </tr>
            </tbody>
        </table>

        <table class="rede table table-striped table-bordered">
            <caption>Detalhes da autorização</caption>
            <thead>
            <tr>
                <th>Nome</th>
                <th>Valor</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="left key">Data e hora da autorização</td>
                <td class="right value">{$authorization_datetime}</td>
            </tr>

            {if $authorization_code}
                <tr>
                    <td class="left key">Código da autorização</td>
                    <td class="right value">{$authorization_code}</td>
                </tr>
            {/if}

            {if $payment_method == "authorize_capture"}
                <tr>
                    <td class="left key">Nsu</td>
                    <td class="right value">{$nsu}</td>
                </tr>
            {/if}

            <tr>
                <td class="left key">Código de Retorno</td>
                <td class="right value">{$return_code_authorization}</td>
            </tr>

            <tr>
                <td class="left key">Mensagem de Retorno</td>
                <td class="right value">{$return_message_authorization}</td>
            </tr>
            </tbody>
        </table>

        {if $capture_datetime}
            <table class="rede table table-striped table-bordered">
                <caption>Detalhes da captura</caption>
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>Valor</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="left key">Data e hora da captura</td>
                    <td class="right value">{$capture_datetime}</td>
                </tr>

                <tr>
                    <td class="left key">Nsu</td>
                    <td class="right value">{$nsu}</td>
                </tr>

                <tr>
                    <td class="left key">Código de Retorno</td>
                    <td class="right value">{$return_code_capture}</td>
                </tr>

                <tr>
                    <td class="left key">Mensagem de Retorno</td>
                    <td class="right value">{$return_message_capture}</td>
                </tr>
                </tbody>
            </table>
        {/if}

        {if $cancelment_datetime}
            <table class="rede table table-striped table-bordered">
                <caption>Detalhes do cancelamento</caption>
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>Valor</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="left key">Data e hora do cancelamento</td>
                    <td class="right value">{$cancelment_datetime}</td>
                </tr>

                <tr>
                    <td class="left key">ID Cancelamento</td>
                    <td class="right value">{$refund_id}</td>
                </tr>

                <tr>
                    <td class="left key">Código de Retorno</td>
                    <td class="right value">{$return_code_cancelment}</td>
                </tr>

                <tr>
                    <td class="left key">Mensagem de Retorno</td>
                    <td class="right value">{$return_message_cancelment}</td>
                </tr>
                </tbody>
            </table>
        {/if}

    </div>

    <style type="text/css">
        table.rede caption {
            line-height: 2em;
            font-weight: bold;
            margin-top: 10px;
        }

        table.rede td.left.key {
            width: 200px;
        }
    </style>
</div>