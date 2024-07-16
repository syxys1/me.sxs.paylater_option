<?php

require_once 'payinstruction.civix.php';
// phpcs:disable
use CRM_Payinstruction_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function payinstruction_civicrm_config(&$config): void {
  //Civi::log()->debug('payinstruction.php::civicrm_config hook config' . '  ');
  _payinstruction_civix_civicrm_config($config);
 }

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function payinstruction_civicrm_install(): void {
  Civi::log()->debug('payinstruction.php::civicrm_install hook' . '  ' );
  _payinstruction_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function payinstruction_civicrm_enable(): void {
  Civi::log()->debug('payinstruction.php::civicrm_enable hook');
  _payinstruction_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_managed().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function payinstruction_civicrm_managed(&$entities): void {
  foreach ($entities as $entity) {
    if($entity["module"] == 'me.sxs.payinstruction') {
      Civi::log()->debug('payinstruction.php::civicrm_managed entity params values class_name' . '  ' . print_r($entity["params"]["values"]["class_name"], true));
       
    }
  }
}

/**
 * Implements hook_civicrm_postinstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postinstall
 */
function payinstruction_civicrm_postinstall(): void {
  Civi::log()->debug('payinstruction.php::civicrm_postinstall');
  
  // Check installed payment processor link to this extension
  $manageds = \Civi\Api4\Managed::get(TRUE)
    ->addSelect('module', 'payment_processor_type.class_name', 'payment_processor_type.title')
    ->addJoin('PaymentProcessorType AS payment_processor_type', 'LEFT', ['payment_processor_type.id', '=', 'entity_id'])
    ->addWhere('module', '=', 'me.sxs.payinstruction')
    ->execute();
  foreach ($manageds as $managed) {
    Civi::log()->debug('payinstruction.php::civicrm_postinstall managed' . '  ' . print_r($managed, true));
    Civi::log()->debug('payinstruction.php::civicrm_postinstall managed class_name' . '  ' . print_r($managed["payment_processor_type.class_name"], true));
    // Check if instructions messages exist
    $messageTemplates = \Civi\Api4\MessageTemplate::get(TRUE)
      ->addWhere('msg_subject', '=', $managed["payment_processor_type.class_name"])
      ->execute();
    
    Civi::log()->debug('payinstruction.php::civicrm_postinstall messageTemplates' . '  ' . print_r($messageTemplates, true));
    
      if ($messageTemplates->count() == 0) {
        // No message template, set one up
        payinstruction_setup($managed);
        Civi::log()->debug('payinstruction.php::civicrm_postinstall add messageTemplate' . '  ' . print_r($managed["class_name"], true)); 
      }
  }
}

/**
 * Implements hook_civicrm_alterContent().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterContent
 */
function payinstruction_civicrm_alterContent(&$content, $context, &$tplName, &$object) {
  Civi::log()->debug('payinstruction.php::civicrm_alterContent Hook templateName' . '  ' . print_r($tplName, true));
  Civi::log()->debug('payinstruction.php::civicrm_alterContent Hook context' . '  ' . print_r($context, true));
  Civi::log()->debug('payinstruction.php::civicrm_alterContent Hook object _eventId' . '  ' . print_r($object->_eventId, true));
  Civi::log()->debug('payinstruction.php::civicrm_alterContent Hook object ' . '  ' . print_r($object->_attributes["class"], true));
  
  if($context == "form") {
    if($tplName == "CRM/Event/Form/Registration/Confirm.tpl") {
      
      //Civi::log()->debug('payinstruction.php::civicrm_alterContent Hook content' . '  ' . print_r($content, true));
      Civi::log()->debug('payinstruction.php::civicrm_alterContent Hook object _paymentProcessor' . '  ' . print_r($object->_paymentProcessor, true));
      //Civi::log()->debug('payinstruction.php::civicrm_alterContent Hook object _paymentProcessor className' . '  ' . print_r($object->_paymentProcessor["class_name"], true));
      
      // Disable API permission checks (short-hand notation)
      //\Civi\Api4\MessageTemplate::myAction(0)->execute();
      // Retrieve instructions message
      $messageTemplates = \Civi\Api4\MessageTemplate::get(FALSE)
        ->addWhere('msg_subject', 'CONTAINS', $object->_paymentProcessor["class_name"])
        ->execute();

      //Civi::log()->debug('payinstruction.php::civicrm_alterContent messageTemplates count' . '  ' . print_r($messageTemplates->count(), true));
          
      if ($messageTemplates->count() == 1) {
        foreach ($messageTemplates as $messageTemplate) {
          //Civi::log()->debug('payinstruction.php::civicrm_alterContent messageTemplate msg_html' . '  ' . print_r($messageTemplate["msg_html"], true)); 
          $pay_later_instruction = $messageTemplate["msg_html"];
        }
      }
      
      // Find the div element with class "continue_message_section"
      $pattern = '/<\s*div\s*class\s*=\s*".*\Qcontinue_message-section\E.*"\s*>\X*?<\s*\/div\s*>/';
      $replacement = '$0' . PHP_EOL .'<div class="pay-later-receipt-instructions">' . $pay_later_instruction . '</div>';
  
      $content = preg_replace ($pattern, $replacement, $content, 1); 
      //Civi::log()->debug('payinstruction.php::civicrm_alterContent replacement' . '  ' . print_r($replacement, true));
      //Civi::log()->debug('payinstruction.php::civicrm_alterContent newContent' . '  ' . print_r($content, true));
    }
    if($tplName == "CRM/Financial/Form/Payment.tpl") {
      
      Civi::log()->debug('payinstruction.php::civicrm_alterContent Hook content' . '  ' . print_r($content, true));
      Civi::log()->debug('payinstruction.php::civicrm_alterContent Hook object _paymentProcessor' . '  ' . print_r($object->_paymentProcessor, true));
      Civi::log()->debug('payinstruction.php::civicrm_alterContent Hook object _paymentProcessor className' . '  ' . print_r($object->_paymentProcessor["class_name"], true));
      
      // Find the div element with id "payment_information"
      $pay_later_instruction = "Ceci est un test";
      $pattern = '/<\s*div\s*id\s*=\s*".*\Qpayment_information\E.*"\s*>\X*?<\s*\/div\s*>/';
      $replacement = '<div id="payment_information">' . $pay_later_instruction . '</div>';
   
      // Disable API permission checks (short-hand notation)
      //\Civi\Api4\MessageTemplate::myAction(0)->execute();
      // Retrieve instructions message
      //$messageTemplates = \Civi\Api4\MessageTemplate::get(FALSE)
      //  ->addWhere('msg_subject', 'CONTAINS', $object->_paymentProcessor["class_name"])
      //  ->execute();

      //Civi::log()->debug('payinstruction.php::civicrm_alterContent messageTemplates count' . '  ' . print_r($messageTemplates->count(), true));
          
      //if ($messageTemplates->count() == 1) {
      //  foreach ($messageTemplates as $messageTemplate) {
          //Civi::log()->debug('payinstruction.php::civicrm_alterContent messageTemplate msg_html' . '  ' . print_r($messageTemplate["msg_html"], true)); 
      //    $pay_later_instruction = $messageTemplate["msg_html"];

      $content = preg_replace ($pattern, $replacement, $content, 1); 
      //Civi::log()->debug('payinstruction.php::civicrm_alterContent replacement' . '  ' . print_r($replacement, true));
      //Civi::log()->debug('payinstruction.php::civicrm_alterContent newContent' . '  ' . print_r($content, true));
      Civi::log()->debug('payinstruction.php::civicrm_alterContent Hook new content' . '  ' . print_r($content, true));

    }
  }     
      
}


/**
 * hook_civicrm_postinstall() support function.
 *
 * @link 
 */
function payinstruction_setup(&$managed): void {

  switch ($managed["payment_processor_type.class_name"]) {
      case "Payment_PayinstructionCash":
          $msg_title = "Instruction Paiement Comptant";
          $msg_subject = "Payment_PayinstructionCash";
          $msg_text = null;
          $msg_html = "<h2 class=\"has-text-align-center wp-block-heading rich-text\" style=\"white-space:pre-wrap;min-width:1px;\">PAIEMENT EN ARGENT COMPTANT</h2>\r\n\r\n<p class=\"has-text-align-center wp-block-paragraph rich-text\" style=\"white-space:pre-wrap;min-width:1px;\">Pour effectuer un paiement en argent comptant, rendez-vous à nos locaux, à l'adresse suivante :</p>\r\n\r\n<h4 class=\"has-text-align-center wp-block-heading rich-text\" style=\"white-space:pre-wrap;min-width:1px;\">1400 boul. Édouard, local 116<br />\r\nSaint-Hubert, Québec<br />\r\nJ4T 3T2</h4>\r\n\r\n<p class=\"has-text-align-center wp-block-paragraph rich-text\" style=\"white-space:pre-wrap;min-width:1px;\"><strong>S.V.P. bien vérifier nos heures d'ouvertures sur la page d'accueil avant de vous y rendre.</strong></p>\r\n\r\n<p class=\"has-text-align-center wp-block-paragraph rich-text\" style=\"white-space:pre-wrap;min-width:1px;\"><strong>ATTENTION</strong><strong> Nous devrons recevoir votre paiement d'ici 10 jours pour éviter l'annulation de votre inscription.</strong>&nbsp;</p>\r\n\r\n<p class=\"has-text-align-center wp-block-paragraph rich-text\" style=\"white-space:pre-wrap;min-width:1px;\"><strong>Pour toutes questions, n’hésitez pas à communiquer avec nous</strong>&nbsp;</p>\r\n\r\n<h5 class=\"has-text-align-center wp-block-heading rich-text\" style=\"white-space:pre-wrap;min-width:1px;line-height:1.6;\">Martin Larivière<br />\r\n(450) 926-5210 poste 2<br />\r\n<a href=\"mailto:mlariviere@asprs.qc.ca\">mlariviere@asprs.qc.ca</a></h5>";
        break;
      case "Payment_PayinstructionCheck":
          $msg_title = "Instruction Paiement Chèque";
          $msg_subject = "Payment_PayinstructionCheck";
          $msg_text = null;
          $msg_html = "<h2 class=\"has-text-align-center wp-block-heading rich-text\" style=\"white-space:pre-wrap;min-width:1px;\">PAIEMENT PAR CHÈQUE</h2>\r\n\r\n<p class=\"has-text-align-center wp-block-paragraph rich-text\" style=\"white-space:pre-wrap;min-width:1px;\">Pour effectuer un paiement par chèque à l’Association, libeller votre chèque à l'ordre de :&nbsp;</p>\r\n\r\n<h4 class=\"has-text-align-center wp-block-heading rich-text\" style=\"white-space:pre-wrap;min-width:1px;\"><strong>Association Sclérose en Plaques Rive-Sud</strong></h4>\r\n\r\n<div class=\"wp-block-spacer\" style=\"height:28px;\">&nbsp;</div>\r\n\r\n<p class=\"has-text-align-center wp-block-paragraph rich-text\" style=\"white-space:pre-wrap;min-width:1px;\"><strong>IMPORTANT</strong> S.V.P. Inscrire à l'endos du chèque, en lettres moulées, le nom de l'évènement, la date de l'évènement ainsi que le(s) nom(s) pour qui vous<br />\r\neffectuez le paiement ou inclure une copie de votre courriel de confirmation dans l'enveloppe avec votre chèque.</p>\r\n\r\n<p class=\"has-text-align-center wp-block-paragraph rich-text\" style=\"white-space:pre-wrap;min-width:1px;\">Faites parvenir le tout à l'adresse suivante :</p>\r\n\r\n<h4 class=\"has-text-align-center wp-block-heading rich-text\" style=\"white-space:pre-wrap;min-width:1px;\">1400 boul. Édouard, local 116<br />\r\nSaint-Hubert, Québec<br />\r\nJ4T 3T2</h4>\r\n\r\n<div class=\"wp-block-spacer\" style=\"height:30px;\">&nbsp;</div>\r\n\r\n<p class=\"has-text-align-center wp-block-paragraph rich-text\" style=\"white-space:pre-wrap;min-width:1px;\"><strong>ATTENTION</strong><strong> Nous devrons recevoir votre chèque d'ici 10 jours pour éviter l'annulation de votre inscription.</strong>&nbsp;</p>\r\n\r\n<p class=\"has-text-align-center wp-block-paragraph rich-text\" style=\"white-space:pre-wrap;min-width:1px;\"><strong>Pour toutes questions, n’hésitez pas à communiquer avec nous</strong>&nbsp;</p>\r\n\r\n<h5 class=\"has-text-align-center wp-block-heading rich-text\" style=\"white-space:pre-wrap;min-width:1px;line-height:1.6;\">Martin Larivière<br />\r\n(450) 926-5210 poste 2<br />\r\n<a href=\"mailto:mlariviere@asprs.qc.ca\">mlariviere@asprs.qc.ca</a></h5>";
        break;
      case "Payment_PayinstructionInterac":
          $msg_title = "Instruction Paiement Interac";
          $msg_subject = "Payment_PayinstructionInterac";
          $msg_text = null;
          $msg_html = "<h2 class=\"has-text-align-center\" style=\"white-space:pre-wrap;min-width:1px;text-transform:uppercase;\">Paiement par virement Interac</h2>\r\n\r\n<p class=\"has-text-align-center wp-block-paragraph rich-text\" style=\"white-space:pre-wrap;min-width:1px;\">Pour faire un virement Interac à l’Association vous devez obligatoirement utiliser les informations ci-dessous :&nbsp;</p>\r\n\r\n<table class=\"has-border-color\">\r\n\t<tbody>\r\n\t\t<tr>\r\n\t\t\t<td class=\"has-text-align-right\">\r\n\t\t\t<div class=\"rich-text\" style=\"white-space:pre-wrap;min-width:1px;\"><strong>Courriel :</strong></div>\r\n\t\t\t</td>\r\n\t\t\t<td class=\"has-text-align-left\">\r\n\t\t\t<div class=\"rich-text\" style=\"white-space:pre-wrap;min-width:1px;\"><a href=\"mailto:paiement@asprs.qc.ca\">paiement@asprs.qc.ca</a></div>\r\n\t\t\t</td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td class=\"has-text-align-right\">\r\n\t\t\t<div class=\"rich-text\" style=\"white-space:pre-wrap;min-width:1px;\"><strong>Question :</strong></div>\r\n\t\t\t</td>\r\n\t\t\t<td class=\"has-text-align-left\">\r\n\t\t\t<div class=\"rich-text\" style=\"white-space:pre-wrap;min-width:1px;\">association</div>\r\n\t\t\t</td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<td class=\"has-text-align-right\">\r\n\t\t\t<div class=\"rich-text\" style=\"white-space:pre-wrap;min-width:1px;\"><strong>Réponse :</strong></div>\r\n\t\t\t</td>\r\n\t\t\t<td class=\"has-text-align-left\">\r\n\t\t\t<div class=\"rich-text\" style=\"white-space:pre-wrap;min-width:1px;\">asprs1234 <strong> (en lettres minuscules)</strong></div>\r\n\t\t\t</td>\r\n\t\t</tr>\r\n\t</tbody>\r\n</table>\r\n\r\n<p class=\"has-text-align-center wp-block-paragraph rich-text\" style=\"white-space:pre-wrap;min-width:1px;\"><strong>ATTENTION</strong> Toute personne qui effectue un virement Interac en utilisant d’autres informations que&nbsp;celles mentionnées ci-dessus verra son virement refusé.&nbsp;</p>\r\n\r\n<p class=\"has-text-align-center wp-block-paragraph rich-text\" style=\"white-space:pre-wrap;min-width:1px;\"><strong>Pour toutes questions, n’hésitez pas à communiquer avec nous</strong>&nbsp;</p>\r\n\r\n<h5 class=\"has-text-align-center wp-block-heading rich-text\" style=\"white-space:pre-wrap;min-width:1px;line-height:1.6;\">Martin Larivière<br />\r\n(450) 926-5210 poste 2<br />\r\n<a href=\"mailto:mlariviere@asprs.qc.ca\">mlariviere@asprs.qc.ca</a></h5>";
        break;
      default:
          $msg_title = $managed["payment_processor_type.title"];
          $msg_subject = $managed["payment_processor_type.class_name"];
          $msg_text = null;
          $msg_html = "";
  }
  
  $messageTemplates = \Civi\Api4\MessageTemplate::create(TRUE)
      ->addValue('msg_title', $msg_title)
      ->addValue('msg_subject', $msg_subject)
      ->addValue('msg_html', $msg_html)
      ->addValue('msg_text', $msg_text)
      ->addValue('is_active', TRUE)
      ->addValue('is_reserved', FALSE)
      ->addValue('is_sms', FALSE)
      ->execute();

  foreach ($messageTemplates as $messageTemplate) {
      Civi::log()->debug('payinstruction.php::payinstruction_setup messageTemplate' . '  ' . print_r($messageTemplate, true)); 
  }
}