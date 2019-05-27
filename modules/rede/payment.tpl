<link rel="stylesheet" type="text/css" href="{$modules_dir}/rede/css/default.css"/>

<div class="row">
    <div class="col-xs-12">
        <div class="oayment_method rede">
            <form class="formulario" id="formulario" method="post"
                  action="{$modules_dir}rede/validation.php?type=redirect">
                <legend>Pagar com Cartão de Crédito.</legend>

                <ul>
                    <input name="id_cart" id="id_cart" type="hidden" value="{$id_cart}">
                    <li><img src="{$modules_dir}/rede/images/rede.jpg" style="max-width: 400px;" alt="Bandeiras"></li>
                    <li>
                        <label>Número do cartão</label>
                        <input type="text" name="card_number" id="card_number" required class="required input"/>
                    </li>

                    <li>
                        <label>Nome no Cartão</label>
                        <input type="text" name="holder_name" id="holder_name" required class="required input"/>
                    </li>

                    <li>
                        <label>Data de Validade</label>
                        <select name="card_expiration_month" id="card_expiration_month" required
                                class="required input">
                            <option>Mês</option>
                            {for $month=1 to 12}
                                <option value='{"%02d"|sprintf:$month}'>{"%02d"|sprintf:$month}</option>
                            {/for}
                        </select> /
                        <select name="card_expiration_year" id="card_expiration_year" required
                                class="required input">
                            <option>Ano</option>
                            {foreach from=$years item=year}
                                <option value="{$year}">{$year}</option>
                            {/foreach}
                        </select>
                    </li>

                    <li>
                        <label>Código de segurança (CVV)</label>
                        <input type="text" name="card_cvv" id="card_cvv" size="4" required class="required input"/>
                    </li>

                    <li>
                        <label>Parcelas</label>
                        <select name="card_installments" id="card_installments">
                            {foreach from=$installments item=installment}
                                <option value="{$installment['value']}">{$installment['label']}</option>
                            {/foreach}
                        </select>
                    </li>

                    <li>
                        <button id="credit-card" name="submit" type="submit">Pagar</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
</div>