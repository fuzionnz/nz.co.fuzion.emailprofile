<?php

require_once 'emailprofile.civix.php';

/**
 * Implements hook_civicrm_buildForm()
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 */

function emailprofile_civicrm_buildForm($formName, &$form) {

  if (strpos($formName, 'CRM_Contribute_Form_Contribution_') === 0) {
    // add email fields back into the profile(s)
    $emails = emailprofile_buildCustom($form, $form->_values['custom_pre_id'], 'customPre');
    $emails = array_merge($emails, emailprofile_buildCustom($form, $form->_values['custom_post_id'], 'customPost'));

    if (empty($emails)) {
      return;
    }

    $formName = substr($formName, 33);
    $id = $form->getContactID();
    $has_blt = FALSE;
    $defaults = array();

    foreach($emails as $email) {
      if ($id && $formName == 'Main') {
        $loc = substr($email, 6);
        // assign default value
        $api = civicrm_api3('Email', 'get', array(
          'sequential' => 1,
          'contact_id' => $id,
          'location_type_id' => $loc,
        ));
        if ($api['count'] > 0) {
          $defaults[$email] = $api['values'][0]['email'];
        }
        $has_blt = $has_blt || $loc == $form->_bltID;
      }
      elseif ($formName != 'Main') {
        $defaults[$email] = $form->_params[$email];
      }
      // apply required rule if applicable
      if (!empty($form->_fields[$email]['is_required'])) {
        $form->addRule($email, ts($form->_fields[$email]['title'] . ' is a required field.'), 'required');
      }
    }
    if ($formName == 'Main') {
      $form->removeElement('email-' . $form->_bltID);

      if (!$has_blt) {
        $form->add('hidden', 'email-' . $form->_bltID);
      }

      $rm = CRM_Core_Resources::singleton();
      $rm->addVars('emailprofile', array(
        'bltID' => $form->_bltID,
        'first' => $emails[0],
        'has_blt' => $has_blt ? 1 : 0,
      ));
      $rm->addScriptFile('nz.co.fuzion.emailprofile', 'emailprofile.js');
    }

    // add defaults if there are any
    if (!empty($defaults)) {
      $form->setDefaults($defaults);
    }
  }
}

/*
  This is mostly taken from CRM_Contribute_Form_ContributionBase::buildCustom
  It adds in the email fields that were excluded
 */
function emailprofile_buildCustom($form, $id, $name) {
  if ($id) {
    $contactID = $form->getContactID();

    $tpl = $form->getTemplate();
    $before = $tpl->get_template_vars($name);
    $after = array();

    $fields = CRM_Core_BAO_UFGroup::getFields($id, FALSE, CRM_Core_Action::ADD, NULL, NULL, FALSE,
      NULL, FALSE, NULL, CRM_Core_Permission::CREATE, NULL
    );

    foreach($fields as $key => $field) {
      if (!empty($before[$key])) {
        $after[$key] = $field;
        continue;
      }
      if (substr($key, 0, 6) != 'email-') {
        continue;
      }

      CRM_Core_BAO_UFGroup::buildProfile(
        $form,
        $field,
        CRM_Profile_Form::MODE_CREATE,
        $contactID,
        TRUE
      );
      $form->_fields[$key] = $field;

      $after[$key] = $field;
    }

    if (count($before) != count($after)) {
      $form->assign($name, $after);
      return array_diff(array_keys($after), array_keys($before));
    }
  }
  return array();
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function emailprofile_civicrm_config(&$config) {
  _emailprofile_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function emailprofile_civicrm_xmlMenu(&$files) {
  _emailprofile_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function emailprofile_civicrm_install() {
  _emailprofile_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function emailprofile_civicrm_uninstall() {
  _emailprofile_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function emailprofile_civicrm_enable() {
  _emailprofile_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function emailprofile_civicrm_disable() {
  _emailprofile_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function emailprofile_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _emailprofile_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function emailprofile_civicrm_managed(&$entities) {
  _emailprofile_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function emailprofile_civicrm_caseTypes(&$caseTypes) {
  _emailprofile_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function emailprofile_civicrm_angularModules(&$angularModules) {
_emailprofile_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function emailprofile_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _emailprofile_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Functions below this ship commented out. Uncomment as required.
 *

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function emailprofile_civicrm_preProcess($formName, &$form) {

}

*/
