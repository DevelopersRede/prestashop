<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Rede\Environment;
use Rede\eRede;
use Rede\Store;
use Rede\Transaction;

require dirname(__FILE__) . '/../../vendor/autoload.php';

class Rede extends PaymentModule
{

    public $tab;
    private $_html = '';

    public function __construct()
    {
        $this->name = 'rede';
        $this->tab = 'payments_gateways';
        $this->version = 1.1;
        $this->author = 'Rede';
        $this->is_eu_compatible = 1;
        $this->currencies = true;
        $this->currencies_mode = 'checkbox';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = 'eRede';
        $this->description = 'Pagamentos através da Rede';
        $this->confirmUninstall = 'Quer mesmo desinstalar?';
        $this->ps_versions_compliancy = array(
            'min' => '1.4',
            'max' => '1.6.99.99'
        );
    }

    public function install()
    {
        $installFile = realpath(dirname(__FILE__) . '/install.sql');

        if (!is_readable($installFile)) {
            throw new Exception('Arquivo de instalação não foi encontrado');
        }

        $sql = str_replace('PREFIX_', _DB_PREFIX_, file_get_contents($installFile));

        if (!Db::getInstance()->Execute($sql)) {
            throw new RuntimeException('Erro ao instalar o módulo');
        }

        if (!Configuration::get('REDE_STATE_AUTHORIZED')) {
            $order_state = new OrderState();
            $order_state->name = array();

            foreach (Language::getLanguages() as $language) {
                $order_state->name[$language['id_lang']] = 'Rede - Pendente';
            }

            $order_state->module_name = 'Rede';
            $order_state->send_email = false;
            $order_state->color = '#259f11';
            $order_state->hidden = false;
            $order_state->delivery = false;
            $order_state->logable = true;
            $order_state->invoice = false;

            $order_state->add();

            if (!Configuration::updateValue('REDE_STATE_AUTHORIZED', (int)$order_state->id)) {
                return false;
            }
        }

        if (!Configuration::get('REDE_STATE_CAPTURED')) {
            $order_state = new OrderState();
            $order_state->name = array();

            foreach (Language::getLanguages() as $language) {
                $order_state->name[$language['id_lang']] = 'Rede - Processando';
            }

            $order_state->module_name = 'Rede';
            $order_state->send_email = false;
            $order_state->color = '#16119f';
            $order_state->hidden = false;
            $order_state->delivery = false;
            $order_state->logable = true;
            $order_state->invoice = true;

            $order_state->add();

            if (!Configuration::updateValue('REDE_STATE_CAPTURED', (int)$order_state->id)) {
                return false;
            }
        }

        if (!Configuration::get('REDE_STATE_CANCELED')) {
            $order_state = new OrderState();
            $order_state->name = array();

            foreach (Language::getLanguages() as $language) {
                $order_state->name[$language['id_lang']] = 'Rede - Cancelado';
            }

            $order_state->module_name = 'Rede';
            $order_state->send_email = false;
            $order_state->color = '#9f1111';
            $order_state->hidden = false;
            $order_state->delivery = false;
            $order_state->logable = true;
            $order_state->invoice = false;

            $order_state->add();

            if (!Configuration::updateValue('REDE_STATE_CANCELED', (int)$order_state->id)) {
                return false;
            }
        }

        if (!Configuration::get('REDE_STATE_ERROR')) {
            $order_state = new OrderState();
            $order_state->name = array();

            foreach (Language::getLanguages() as $language) {
                $order_state->name[$language['id_lang']] = 'Rede - Erro';
            }

            $order_state->module_name = 'Rede';
            $order_state->send_email = false;
            $order_state->color = '#ac0c0c';
            $order_state->hidden = false;
            $order_state->delivery = false;
            $order_state->logable = true;
            $order_state->invoice = false;

            $order_state->add();

            if (!Configuration::updateValue('REDE_STATE_ERROR', (int)$order_state->id)) {
                return false;
            }
        }

        if (!parent::install() or
            !Configuration::updateValue('REDE_ENVIRONMENT', 'tests') or
            !Configuration::updateValue('REDE_PV', '') or
            !Configuration::updateValue('REDE_TOKEN', '') or

            !Configuration::updateValue('REDE_PAYMENT_METHOD', 'authorize') or
            !Configuration::updateValue('REDE_SOFT_DESCRIPTOR', '') or
            !Configuration::updateValue('REDE_CARD_MAX_INSTALLMENTS', 12) or
            !Configuration::updateValue('REDE_CARD_MIN_INSTALLMENTS_AMOUNT', 0) or

            !Configuration::updateValue('REDE_MODULE', '') or
            !Configuration::updateValue('REDE_GATEWAY', '') or

            !$this->registerHook('payment') or
            !$this->registerHook('paymentReturn') or
            !$this->registerHook('home') or
            !$this->registerHook('invoice') or
            !$this->registerHook('actionOrderStatusPostUpdate') or
            !$this->registerHook('rightColumn')) {

            return false;
        }

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() or
            !Configuration::deleteByName('REDE_ENVIRONMENT') or
            !Configuration::deleteByName('REDE_PV') or
            !Configuration::deleteByName('REDE_TOKEN') or

            !Configuration::deleteByName('REDE_PAYMENT_METHOD') or
            !Configuration::deleteByName('REDE_SOFT_DESCRIPTOR') or
            !Configuration::deleteByName('REDE_CARD_MAX_INSTALLMENTS') or
            !Configuration::deleteByName('REDE_CARD_MIN_INSTALLMENTS_AMOUNT') or

            !Configuration::deleteByName('REDE_GATEWAY') or
            !Configuration::deleteByName('REDE_MODULE') or

            !Configuration::deleteByName('REDE_STATE_AUTHORIZED') or
            !Configuration::deleteByName('REDE_STATE_CAPTURED') or
            !Configuration::deleteByName('REDE_STATE_CANCELED') or
            !Configuration::deleteByName('REDE_STATE_ERROR')) {

            return false;
        }

        return true;
    }

    public function getContent()
    {
        $this->_html = '';

        if (isset($_POST['save_rede'])) {
            Configuration::updateValue('REDE_ENVIRONMENT', $_POST['rede_environment']);
            Configuration::updateValue('REDE_PV', $_POST['rede_pv']);
            Configuration::updateValue('REDE_TOKEN', $_POST['rede_token']);

            Configuration::updateValue('REDE_PAYMENT_METHOD', $_POST['rede_payment_method']);
            Configuration::updateValue('REDE_SOFT_DESCRIPTOR', $_POST['rede_soft_descriptor']);
            Configuration::updateValue('REDE_CARD_MAX_INSTALLMENTS',
                isset($_POST['rede_card_max_installments']) ? $_POST['rede_card_max_installments'] : 12);
            Configuration::updateValue('REDE_CARD_MIN_INSTALLMENTS_AMOUNT',
                isset($_POST['rede_card_mim_installments_value']) ? $_POST['rede_card_mim_installments_value'] : 0);

            Configuration::updateValue('REDE_MODULE', $_POST['rede_module']);
            Configuration::updateValue('REDE_GATEWAY', $_POST['rede_gateway']);

            $this->displayConf();
        }

        $this->displaySettings();

        return $this->_html;
    }

    public function displayConf()
    {
        $this->_html .= '
		<div class="alert alert-success">
			Configurações atualizadas
        </div>';
    }

    public function displaySettings()
    {
        global $smarty;

        $rede_environment = $this->checkConfig('rede_environment', 'REDE_ENVIRONMENT');
        $rede_pv = $this->checkConfig('rede_pv', 'REDE_PV');
        $rede_token = $this->checkConfig('rede_token', 'REDE_TOKEN');

        $rede_payment_method = $this->checkConfig('rede_payment_method', 'REDE_PAYMENT_METHOD', 'authorize_capture');
        $rede_soft_descriptor = $this->checkConfig('rede_soft_descriptor', 'REDE_SOFT_DESCRIPTOR');
        $rede_card_max_installments = $this->checkConfig('rede_card_max_installments', 'REDE_CARD_MAX_INSTALLMENTS', 12);
        $rede_card_mim_installments_amount = $this->checkConfig('rede_card_mim_installments_amount',
            'REDE_CARD_MIN_INSTALLMENTS_AMOUNT', 0);

        $rede_gateway = $this->checkConfig('rede_gateway', 'REDE_GATEWAY');
        $rede_module = $this->checkConfig('rede_module', 'REDE_MODULE');

        $smarty->assign(array(
            'action' => $_SERVER['REQUEST_URI'],

            'rede_environment' => $rede_environment,
            'rede_pv' => $rede_pv,
            'rede_token' => $rede_token,

            'rede_payment_method' => $rede_payment_method,
            'rede_soft_descriptor' => $rede_soft_descriptor,
            'rede_card_max_installments' => $rede_card_max_installments,
            'rede_card_mim_installments_amount' => $rede_card_mim_installments_amount,

            'rede_gateway' => $rede_gateway,
            'rede_module' => $rede_module
        ));

        $this->_html = $this->display(__FILE__, 'settings.tpl');
    }

    private function checkConfig($postKey, $confKey, $default = null)
    {
        $conf = Configuration::getMultiple(array(
            'REDE_ENVIRONMENT',
            'REDE_PV',
            'REDE_TOKEN',

            'REDE_PAYMENT_METHOD',
            'REDE_SOFT_DESCRIPTOR',
            'REDE_CARD_MAX_INSTALLMENTS',
            'REDE_CARD_MIN_INSTALLMENTS_AMOUNT',

            'REDE_GATEWAY',
            'REDE_MODULE'
        ));

        $confValue = isset($_POST[$postKey]) ? $_POST[$postKey] : (isset($conf[$confKey]) ? $conf[$confKey] : '');

        if ($confValue === null) {
            $confValue = $default;
        }

        return $confValue;
    }

    public function hookRightColumn()
    {
        $this->context->controller->addJS($this->_path . 'script/jquery.validate.js');
        $this->context->controller->addJS($this->_path . 'script/rede.js');

        return true;
    }

    public function hookPayment()
    {
        global $smarty, $cart;

        $amount = (float)($cart->getOrderTotal(true, Cart::BOTH));

        $this->context->controller->addJS($this->_path . 'script/jquery.validate.js');
        $this->context->controller->addJS($this->_path . 'script/rede.js');

        $max_installments = Configuration::get('REDE_CARD_MAX_INSTALLMENTS');
        $min_value = Configuration::get('REDE_CARD_MIN_INSTALLMENTS_AMOUNT');

        $installments[] = array(
            'label' => sprintf('R$ %.02f à vista', $amount),
            'value' => 1
        );

        for ($i = 2; $i <= $max_installments; $i++) {
            $value = ceil($amount / $i * 100) / 100;

            if ($value >= $min_value) {
                $installments[] = array(
                    'label' => sprintf('%d vezes de R$ %.02f', $i, $value),
                    'value' => $i
                );
            }
        }

        $year = (int)date('Y');

        $smarty->assign(array(
            'installments' => $installments,
            'amount' => number_format($amount, 2, ',', '.'),
            'years' => range($year, $year + 12),
            'current_month' => date('m'),
            'current_year' => date('Y'),
            'id_cart' => $cart->id
        ));

        return $this->display(__FILE__, 'payment.tpl');
    }

    public function hookPaymentReturn($vars)
    {
        global $smarty;

        extract($vars);

        $transaction_data = $this->getOrderData($objOrder->id);

        $smarty->assign($transaction_data);

        return $this->display(__FILE__, 'payment_return.tpl');
    }

    public function getOrderData($id_order)
    {
        $rq = Db::getInstance()->getRow(sprintf('SELECT * FROM `%serede` WHERE id_order = %d ORDER BY `id_rede` DESC',
            _DB_PREFIX_, pSQL($id_order)));

        return $rq;
    }

    public function hookInvoice($params)
    {
        global $smarty;

        $id_order = $params['id_order'];
        $transaction_data = $this->getOrderData($id_order);

        if (isset($transaction_data['payment_method'])) {
            $transaction_data['payment_method_text'] = $transaction_data['payment_method'] == 'authorize' ? 'Somente Autorizar' : 'Autorizar e Capturar';
        }

        $smarty->assign($transaction_data);

        return $this->display(__FILE__, 'invoice.tpl');
    }

    public function hookActionOrderStatusPostUpdate($params)
    {
        $newOrderState = $params['newOrderStatus'];
        $id_order = $params['id_order'];

        switch ($newOrderState->id) {
            case Configuration::get('REDE_STATE_CAPTURED'):
                $this->capture($id_order);
                break;
            case Configuration::get('REDE_STATE_CANCELED'):
                $this->cancel($id_order);
                break;
        }
    }

    public function addOrder($transaction, $reference, $amount, $card_installments, $capture, $payment_method)
    {
        Db::getInstance()->Execute("INSERT INTO `" . _DB_PREFIX_ . "erede`(
          `id_order`,
          `amount`,
          `transaction_id`,
          `refund_id`,
          `authorization_code`,
          `nsu`,
          `bin`,
          `last4`,
          `installments`,
          `can_capture`,
          `can_cancel`,
          `payment_method`,
          `return_code_authorization`,
          `return_message_authorization`,
          `authorization_datetime`
          ) VALUES (" .
            '"' . $reference . '",' .
            $amount . ',' .
            '"' . $transaction->getTid() . '",' .
            '"' . $transaction->getRefundId() . '",' .
            '"' . $transaction->getAuthorizationCode() . '",' .
            '"' . $transaction->getNsu() . '",' .
            '"' . $transaction->getCardBin() . '",' .
            '"' . $transaction->getLast4() . '",' .
            '"' . $card_installments . '",' .
            '"' . !$capture . '",' .
            '"1",' .
            '"' . $payment_method . '",' .
            '"' . $transaction->getReturnCode() . '",' .
            '"' . $transaction->getReturnMessage() . '",' .
            '"' . date('Y-m-d H:i:s') . '"' .
            ")");
    }

    public function validate($post)
    {
        if (!isset($post['card_number'])) {
            throw new Exception('Cartão de crédito inválido');
        }

        if (!isset($post['holder_name']) || empty($post['holder_name'])) {
            throw new Exception('Titular do cartão inválido');
        }

        if (!isset($post['card_expiration_year'])) {
            throw new Exception('Ano de expiração do cartão inválido.');
        }

        if (!isset($post['card_expiration_month'])) {
            throw new Exception('Mês de expiração do cartão inválido.');
        }

        if (!isset($post['card_cvv'])) {
            throw new Exception('Código de segurança inválido');
        }

        if (!$this->validateCcNum($post['card_number'])) {
            throw new Exception('Cartão de crédito inválido');
        }

        if (!is_numeric($post['card_cvv']) || (strlen($post['card_cvv']) < 3 || strlen($post['card_cvv']) > 4)) {
            throw new Exception('Código de segurança inválido');
        }

        if (preg_replace('/[^a-z\s]/i', '', $post['holder_name']) != $post['holder_name']) {
            throw new Exception('Titular do cartão inválido');
        }

        $year = date('Y');
        $month = date('m');

        if ((int)$post['card_expiration_year'] < $year) {
            throw new Exception('Ano de expiração do cartão inválido.');
        }

        if ((int)$post['card_expiration_year'] == $year) {
            if ((int)$post['card_expiration_month'] < $month) {
                throw new Exception('Mês de expiração do cartão inválido.');
            }
        }
    }

    private function validateCcNum($ccNumber)
    {
        $ccNumber = preg_replace('/[^\d]/', '', $ccNumber);
        $cardNumber = strrev($ccNumber);
        $numSum = 0;

        for ($i = 0; $i < strlen($cardNumber); $i++) {
            $currentNum = substr($cardNumber, $i, 1);

            /**
             * Double every second digit
             */
            if ($i % 2 == 1) {
                $currentNum *= 2;
            }

            /**
             * Add digits of 2-digit numbers together
             */
            if ($currentNum > 9) {
                $firstNum = $currentNum % 10;
                $secondNum = ($currentNum - $firstNum) / 10;
                $currentNum = $firstNum + $secondNum;
            }

            $numSum += $currentNum;
        }

        /**
         * If the total has no remainder it's OK
         */

        return ($numSum % 10 == 0);
    }

    public function validateOrder(
        $id_cart,
        $id_order_state,
        $amount_paid,
        $payment_method = 'Rede',
        $message = null,
        $extra_vars = array(),
        $currency_special = null,
        $dont_touch_amount = false,
        $secure_key = false,
        Shop $shop = null
    )
    {

        $cart = new Cart($id_cart);
        $amount = (float)($cart->getOrderTotal(true, Cart::BOTH));
        $cart = new Cart(intval($id_cart));
        $cart->id_currency = 1;
        $cart->save();

        if (!is_numeric($amount_paid)) {
            $amount_paid = $amount;
        }

        parent::validateOrder($id_cart, $id_order_state, $amount_paid, $payment_method, $message, $extra_vars, 1);

        if ($amount_paid != $amount) {
            $order = new Order($this->currentOrder);
            $history = new OrderHistory();
            $history->id_order = intval($order->id);
            $history->changeIdOrderState(intval($id_order_state), intval($order->id));
            $history->addWithemail(true, $extra_vars);
        }
    }

    public function pay($post)
    {
        $cart = new Cart(intval($post['id_cart']));
        $orderId = Order::getOrderByCartId((int)($cart->id));
        $amount = $cart->getOrderTotal();

        $reference = $orderId;
        $payment_method = Configuration::get('REDE_PAYMENT_METHOD');
        $capture = $payment_method === 'authorize_capture';
        $store = $this->store();

        $transaction = new Transaction($amount, $reference + time());

        $transaction->creditCard($post['card_number'], $post['card_cvv'], $post['card_expiration_month'], $post['card_expiration_year'], $post['holder_name']);
        $transaction->capture($capture);

        $gateway = Configuration::get('REDE_GATEWAY');
        $module = Configuration::get('REDE_MODULE');
        $softDescriptor = Configuration::get('REDE_SOFT_DESCRIPTOR');

        if (!empty($gateway) && !empty($module)) {
            $transaction->additional($gateway, $module);
        }

        if (!empty($softDescriptor)) {
            $transaction->setSoftDescriptor($softDescriptor);
        }

        $card_installments = (int)$post['card_installments'];
        $card_installments = $card_installments < 1 || $card_installments > Configuration::get('REDE_CARD_MAX_INSTALLMENTS') ? 1 : $card_installments;

        if ($card_installments > 1) {
            $transaction->setInstallments($card_installments);
        }

        $exception = null;

        try {
            $transaction = (new eRede($store, $this->logger()))->create($transaction);
        } catch (Exception $e) {
            $exception = $e;
        }

        $return_code = $transaction->getReturnCode();
        $status = Configuration::get('REDE_STATE_ERROR');

        if ($exception !== null) {
            $error = new stdClass();
            $error->error = $exception->getMessage();

            echo json_encode($error);
        } else if ($_GET['type'] == 'redirect') {
            if ($return_code == '00') {
                $status = $capture ? Configuration::get('REDE_STATE_CAPTURED') : Configuration::get('REDE_STATE_AUTHORIZED');
            }

            $this->validateOrder($cart->id, $status, $cart->getOrderTotal());

            $order = new Order($this->currentOrder);

            $this->addOrder($transaction, $order->id, $amount, $card_installments, $capture, $payment_method);

            $response = new stdClass();
            $response->redirect = __PS_BASE_URI__ . 'index.php?' . http_build_query(
                    array(
                        'controller' => 'order-confirmation',
                        'id_cart' => $cart->id,
                        'id_module' => $this->getModuleIdByName($this->name),
                        'id_order' => $order->id,
                        'key' => $cart->secure_key
                    )
                );

            echo json_encode($response);
        }
    }

    public function capture($order_id)
    {
        $order = $this->getOrderData($order_id);

        $capture = isset($order['can_capture']) ? $order['can_capture'] : false;

        if ($capture) {
            $amount = isset($order['amount']) ? $order['amount'] : '';
            $tid = isset($order['transaction_id']) ? $order['transaction_id'] : '';
            $exception = null;

            $transaction = (new eRede($this->store(), $this->logger()))->capture(
                (new Transaction($amount))->setTid($tid)
            );

            $nsu = $transaction->getNsu();
            $return_code = $transaction->getReturnCode();
            $return_message = $transaction->getReturnMessage();

            Db::getInstance()->Execute(sprintf(
                'UPDATE `%serede` SET
                    `nsu`="%s",
                    `can_capture`="0",
                    `return_code_capture`="%s",
                    `return_message_capture`="%s",
                    `capture_datetime`="%s"
                    WHERE `id_order`="%s";',
                _DB_PREFIX_,
                $nsu, $return_code, $return_message, date('Y-m-d H:i:s'), $order_id));
        }

    }

    public function cancel($order_id)
    {
        $order = $this->getOrderData($order_id);
        $cancel = isset($order['can_cancel']) ? $order['can_cancel'] : false;

        if ($cancel) {
            $amount = isset($order['amount']) ? $order['amount'] : '';
            $tid = isset($order['transaction_id']) ? $order['transaction_id'] : '';
            $exception = null;

            $transaction = (new eRede($this->store(), $this->logger()))->cancel(
                (new Transaction($amount))->setTid($tid)
            );

            $refund_id = $transaction->getRefundId();
            $return_code = $transaction->getReturnCode();
            $return_message = $transaction->getReturnMessage();

            Db::getInstance()->Execute(sprintf(
                'UPDATE `%serede` SET
                    `refund_id`="%s",
                    `can_capture`="0",
                    `can_cancel`="0",
                    `return_code_cancelment`="%s",
                    `return_message_cancelment`="%s",
                    `cancelment_datetime`="%s"
                    WHERE `id_order`="%s";',
                _DB_PREFIX_,
                $refund_id, $return_code, $return_message, date('Y-m-d H:i:s'), $order_id));
        }

    }

    private function logger()
    {
        $logger = null;

        if (class_exists('\Monolog\Logger')) {
            $logger = new Logger('rede');
            $logger->pushHandler(new StreamHandler(_PS_ROOT_DIR_ . '/log/rede.log', Logger::DEBUG));
            $logger->info('Log Rede');
        }

        return $logger;
    }

    private function environment()
    {
        $environment = Environment::production();

        if (Configuration::get('REDE_ENVIRONMENT') == 'tests') {
            $environment = Environment::sandbox();
        }

        return $environment;
    }

    private function store()
    {
        $environment = $this->environment();
        $store = new Store(Configuration::get('REDE_PV'), Configuration::get('REDE_TOKEN'), $environment);

        return $store;

    }
}
