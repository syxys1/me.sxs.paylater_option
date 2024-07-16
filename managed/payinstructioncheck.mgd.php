<?php

use CRM_Payinstruction_ExtensionUtil as E;

return [
  [
    'name' => 'Pay Instructions Check',
    'entity' => 'PaymentProcessorType',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'PayinstructionCheck',
        'title' => E::ts('Payment Instructions for Check'),
        'user_name_label' => 'Ignore',
        'password_label' => 'Ignore',
        'signature_label' => 'Ignore',
        'class_name' => 'Payment_PayinstructionCheck',
        'url_site_default' => 'https://unused.org',
        'billing_mode' => 1,
        'is_recur' => TRUE,
        'payment_instrument_id:name' => 'Check',
      ],
      'match' => [
        'name',
      ],
    ],
  ],
];
