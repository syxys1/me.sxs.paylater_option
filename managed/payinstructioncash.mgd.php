<?php

use CRM_Payinstruction_ExtensionUtil as E;

return [
  [
    'name' => 'Pay Instructions Cash',
    'entity' => 'PaymentProcessorType',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'PayinstructionCash',
        'title' => E::ts('Payment Instructions for Cash'),
        'user_name_label' => 'Ignore',
        'password_label' => 'Ignore',
        'signature_label' => 'Ignore',
        'class_name' => 'Payment_PayinstructionCash',
        'url_site_default' => 'https://unused.org',
        'billing_mode' => 1,
        'is_recur' => TRUE,
        'payment_instrument_id:name' => 'Cash',
      ],
      'match' => [
        'name',
      ],
    ],
  ],
];
