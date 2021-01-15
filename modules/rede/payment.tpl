<link rel="stylesheet" type="text/css" href="{$modules_dir}/rede/css/default.css"/>

<form id="payment-form" method="post" action="{$action}">
    <div class="row formulario">
        {if $rede_payment_method eq 'credit' or $rede_payment_method eq 'credit_debit'}
            <div class="payment_method rede">
                <legend>Pagar com Cartão de Crédito.</legend>

                <ul>
                    <input name="id_cart" id="id_cart" type="hidden" value="{$id_cart}">
                    <li><img src="{$modules_dir}/rede/images/rede_credit.jpg" style="max-width: 400px;" alt="Bandeiras">
                    </li>
                    <li>
                        <label>Número do cartão</label>
                        <input type="text" name="creditcard_number" id="creditcard_number" class="required input"/>
                    </li>

                    <li>
                        <label>Nome no Cartão</label>
                        <input type="text" name="creditcard_holder_name" id="holder_name" class="required input"/>
                    </li>

                    <li>
                        <label>Data de Validade</label>
                        <select name="creditcard_expiration_month" id="creditcard_expiration_month" required
                                class="required input">
                            <option>Mês</option>
                            {for $month=1 to 12}
                                <option value='{"%02d"|sprintf:$month}'>{"%02d"|sprintf:$month}</option>
                            {/for}
                        </select> /
                        <select name="creditcard_expiration_year" id="creditcard_expiration_year" required
                                class="required input">
                            <option>Ano</option>
                            {foreach from=$years item=year}
                                <option value="{$year}">{$year}</option>
                            {/foreach}
                        </select>
                    </li>

                    <li>
                        <label>Código de segurança (CVV)</label>
                        <input type="text" name="creditcard_cvv" id="creditcard_cvv" size="4" class="required input"/>
                    </li>

                    <li>
                        <label>Parcelas</label>
                        <select name="creditcard_installments" id="creditcard_installments">
                            {foreach from=$installments item=installment}
                                <option value="{$installment['value']}">{$installment['label']}</option>
                            {/foreach}
                        </select>
                    </li>
                </ul>
            </div>
        {/if}

        {if $rede_payment_method eq 'debit' or $rede_payment_method eq 'credit_debit'}
            <div class="payment_method rede">
                <legend>Pagar com Cartão de Débito.</legend>

                <ul>
                    <input name="id_cart" id="id_cart" type="hidden" value="{$id_cart}">
                    <li><img src="{$modules_dir}/rede/images/rede_debit.jpg" style="max-width: 400px;" alt="Bandeiras">
                    </li>
                    <li>
                        <label>Número do cartão</label>
                        <input type="text" name="debitcard_number" id="debitcard_number" class="required input"/>
                    </li>

                    <li>
                        <label>Nome no Cartão</label>
                        <input type="text" name="debitcard_holder_name" id="holder_name" class="required input"/>
                    </li>

                    <li>
                        <label>Data de Validade</label>
                        <select name="debitcard_expiration_month" id="debitcard_expiration_month" required
                                class="required input">
                            <option>Mês</option>
                            {for $month=1 to 12}
                                <option value='{"%02d"|sprintf:$month}'>{"%02d"|sprintf:$month}</option>
                            {/for}
                        </select> /
                        <select name="debitcard_expiration_year" id="debitcard_expiration_year" required
                                class="required input">
                            <option>Ano</option>
                            {foreach from=$years item=year}
                                <option value="{$year}">{$year}</option>
                            {/foreach}
                        </select>
                    </li>

                    <li>
                        <label>Código de segurança (CVV)</label>
                        <input type="text" name="debitcard_cvv" id="debitcard_cvv" size="4" class="required input"/>
                    </li>
                </ul>
            </div>
        {/if}
    </div>
</form>