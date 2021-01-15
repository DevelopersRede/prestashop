<script type="text/javascript">
    (function ($) {
        const credit_panel = () => {
            let credit_panel = $('#credit_panel');

            switch ($('#rede_payment_method option:selected').val()) {
                case 'credit':
                case 'credit_debit':
                    credit_panel.show();
                    break;
                default:
                    credit_panel.hide();

            }
        };

        $(document).ready(function () {
            $('#rede_payment_method').on('change', credit_panel);

            credit_panel();
        });
    })(jQuery);
</script>

<form action="{$action}" method="post">
    <div class="panel">
        <div class="panel-heading">
            <i class="fa-cog"></i> Configurações Gerais
        </div>
        <div class="form-wrapper">
            <div class="form-group">
                <label class="control-label required">Ambiente:</label>
                <div>
                    <select name="rede_environment">
                        <option value="tests" {if $rede_environment == 'tests'}selected{/if}>Testes</option>
                        <option value="production" {if $rede_environment == 'production'}selected{/if}>Produção</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label required">PV:</label>
                <div>
                    <input type="text" required name="rede_pv" value="{$rede_pv}"/>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label required">Token:</label>
                <div>
                    <input type="text" required name="rede_token" value="{$rede_token}"/>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" value="1" id="save_rede_1" name="save_rede" class="btn btn-default pull-right">
                <i class="process-icon-save"></i>&nbsp;Salvar
            </button>
        </div>
    </div>

    <div class="panel">
        <div class="panel-heading">
            <i class="fa-cog"></i>&nbsp;Configurações de Pagamento
        </div>
        <div class="form-wrapper">
            <div class="form-group">
                <label class="control-label required">Método de pagamento:</label>
                <div>
                    <select id="rede_payment_method" name="rede_payment_method">
                        <option value="credit" {if $rede_payment_method == "credit"}selected{/if}>
                            Somente Crédito
                        </option>
                        <option value="debit" {if $rede_payment_method == "debit"}selected{/if}>
                            Somente Débito
                        </option>
                        <option value="credit_debit" {if $rede_payment_method == "credit_debit"}selected{/if}>
                            Crédito e Débito
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label">Soft Descriptor:</label>
                <div>
                    <input type="text" name="rede_soft_descriptor" value="{$rede_soft_descriptor}"/>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" value="1" id="save_rede_1" name="save_rede" class="btn btn-default pull-right">
                <i class="process-icon-save"></i>&nbsp;Salvar
            </button>
        </div>
    </div>

    <div id="credit_panel" class="panel">
        <div class="panel-heading">
            <i class="fa-cog"></i>&nbsp;Configurações de Cartão de Crédito
        </div>

        <div class="form-group">
            <label class="control-label required">Método de autorização:</label>
            <div>
                <select name="rede_authorization_method">
                    <option value="authorize" {if $rede_authorization_method == "authorize"}selected{/if}>Somente
                        autorizar
                    </option>
                    <option value="authorize_capture"
                            {if $rede_authorization_method == "authorize_capture"}selected{/if}>
                        Autorizar e Capturar
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label required">Máximo de parcelas permitidas</label>
            <div>
                <select name="rede_card_max_installments">
                    {for $i=1 to 12}
                        <option value="{{$i}}"{if $rede_card_max_installments == $i} selected{/if}>{{$i}}</option>
                    {/for}
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label required">Valor mínimo de cada parcela</label>
            <div>
                <input type="text" required name="rede_card_mim_installments_amount"
                       value="{$rede_card_mim_installments_amount}"/>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" value="1" id="save_rede_2" name="save_rede" class="btn btn-default pull-right">
                <i class="process-icon-save"></i>&nbsp;Salvar
            </button>
        </div>
    </div>

    <div class="panel">
        <div class="panel-heading">
            <i class="fa-cog"></i>&nbsp;Configurações de Parceiros
        </div>
        <div class="form-wrapper">
            <div class="form-group">
                <label class="control-label">Module:</label>
                <div>
                    <input type="text" name="rede_module" value="{$rede_module}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">Gateway:</label>
                <div>
                    <input type="text" name="rede_gateway" value="{$rede_gateway}"/>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" value="1" id="save_rede_3" name="save_rede" class="btn btn-default pull-right">
                <i class="process-icon-save"></i>&nbsp;Salvar
            </button>
        </div>
    </div>

</form>