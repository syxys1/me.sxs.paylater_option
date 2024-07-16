<?php

require_once 'payinstruction.civix.php';
// phpcs:disable
use CRM_Payinstruction_ExtensionUtil as E;
// phpcs:enable


/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
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
        ->addValue('is_reserved', TRUE)
        ->addValue('is_sms', FALSE)
        ->execute();

    foreach ($messageTemplates as $messageTemplate) {
        Civi::log()->debug('payinstruction.php::payinstruction_setup messageTemplate' . '  ' . print_r($messageTemplate, true)); 
    }
}
