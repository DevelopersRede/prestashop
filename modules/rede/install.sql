CREATE TABLE IF NOT EXISTS `PREFIX_erede`
(
  `id_rede`                      INT(32)        NOT NULL AUTO_INCREMENT,
  `id_order`                     INT(32)        NOT NULL,
  `amount`                       DECIMAL(32, 2) NOT NULL,
  `transaction_id`               VARCHAR(32)    NOT NULL,
  `refund_id`                    VARCHAR(32)    NOT NULL,
  `authorization_code`           VARCHAR(32)    NOT NULL,
  `nsu`                          VARCHAR(32)    NOT NULL,
  `bin`                          VARCHAR(6)     NOT NULL,
  `last4`                        VARCHAR(4)     NOT NULL,
  `installments`                 CHAR(2)        NOT NULL,
  `can_capture`                  INT(1)         NOT NULL,
  `can_cancel`                   INT(1)         NOT NULL,
  `payment_method`               VARCHAR(32)    NOT NULL,

  `return_code_authorization`    VARCHAR(32)    NOT NULL,
  `return_message_authorization` VARCHAR(255)   NOT NULL,
  `authorization_datetime`       DATETIME       NOT NULL,

  `return_code_capture`          VARCHAR(32)  DEFAULT NULL,
  `return_message_capture`       VARCHAR(255) DEFAULT NULL,
  `capture_datetime`             DATETIME     DEFAULT NULL,

  `return_code_cancelment`       VARCHAR(32)    NOT NULL,
  `return_message_cancelment`    VARCHAR(255)   NOT NULL,
  `cancelment_datetime`          DATETIME     DEFAULT NULL,
  PRIMARY KEY (`id_rede`)
) DEFAULT CHARSET = utf8;