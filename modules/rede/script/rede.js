(function ($) {
    $(document).ready(
        function () {
            $.validator.addMethod("holdername", function (value, element) {
                return this.optional(element) || /^[a-zA-Z\s]*$/.test(value);
            });

            $(".formulario").validate({
                rules: {
                    card_number: {
                        required: true,
                        creditcard: true,
                        number: true
                    },
                    holder_name: {
                        required: true,
                        holdername: true
                    },
                    card_expiration_month: {
                        required: true
                    },
                    card_expiration_year: {
                        required: true
                    },
                    card_cvv: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    card_number: "Por favor, informe o número do cartão",
                    holder_name: "Por favor, informe o nome como exibido no cartão",
                    card_expiration_month: "Por favor, informe o mês de expiracão do cartão",
                    card_expiration_year: "Por favor, informe o ano de expiracão do cartão",
                    card_cvv: "Por favor, informe o código de segurança do cartão",
                },
                errorClass: "validation-error",
                errorElement: "span"
            });

            $('.formulario').submit(function (e) {
                e.preventDefault();

                $('#credit-card').attr("disabled", "disabled");
                $('#credit-card').toggleClass('disabled');
                $('#submit-spinner').show();

                $.ajax({
                    type: "POST",
                    url: $('.formulario').attr('action'),
                    dataType: 'json',
                    data: {
                        id_cart: $('#id_cart').val(),
                        card_number: $('#card_number').val(),
                        holder_name: $('#holder_name').val(),
                        card_expiration_year: $('#card_expiration_year').val(),
                        card_expiration_month: $('#card_expiration_month').val(),
                        card_cvv: $('#card_cvv').val(),
                        card_installments: $('#card_installments').val()
                    },
                    success: function (data) {
                        $('#credit-card').removeAttr("disabled");
                        $('#credit-card').toggleClass('disabled');
                        $('#submit-spinner').hide();

                        if (data.error !== undefined) {
                            alert(data.error);
                        }

                        if (data.redirect !== undefined) {
                            window.location.href = data.redirect;
                        }
                    }
                });
            });
        });

})(jQuery);
