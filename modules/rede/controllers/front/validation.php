<?php
require __DIR__ . '/../../vendor/autoload.php';

class RedeValidationModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        $context = Context::getContext();
        $cart = $context->cart;
        $rede = new Rede();

        try {
            $post = Tools::getAllValues();
            $rede->validate($post);
            $rede->pay($post);

            /** @var CustomerCore $customer */
            $customer = new Customer($cart->id_customer);

            /**
             * Redirect the customer to the order confirmation page
             */
            Tools::redirect('index.php?controller=order-confirmation&id_cart=' . (int)$cart->id . '&id_module=' . (int)$this->module->id . '&id_order=' . $this->module->currentOrder . '&key=' . $customer->secure_key);
        } catch (Exception $e) {
            Tools::redirect('index.php?controller=order&step=1');
        }
    }
}