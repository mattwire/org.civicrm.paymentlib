<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 5                                                  |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2018                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
 */

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2018
 */

/**
 * This trait allows us to consolidate common payment processor extension functionality
 *
 */
trait CRM_Core_PaymentTrait {

  /**
   * Get the billing email address
   *
   * @param array $params
   * @param int $contactId
   *
   * @return string|NULL
   */
  protected static function getBillingEmail($params, $contactId) {
    $billingLocationId = CRM_Core_BAO_LocationType::getBilling();

    $emailAddress = CRM_Utils_Array::value("email-{$billingLocationId}", $params,
      CRM_Utils_Array::value('email-Primary', $params,
        CRM_Utils_Array::value('email', $params, NULL)));

    if (empty($emailAddress) && !empty($contactId)) {
      // Try and retrieve an email address from Contact ID
      try {
        $emailAddress = civicrm_api3('Email', 'getvalue', array(
          'contact_id' => $contactId,
          'return' => ['email'],
        ));
      }
      catch (CiviCRM_API3_Exception $e) {
        return NULL;
      }
    }
    return $emailAddress;
  }

  /**
   * Get the contact id
   *
   * @param array $params
   *
   * @return int ContactID
   */
  protected static function getContactId($params) {
    return CRM_Utils_Array::value('contactID', $params,
             CRM_Utils_Array::value('contact_id', $params,
               CRM_Utils_Array::value('cms_contactID', $params,
                 CRM_Utils_Array::value('cid', $params, NULL
           ))));
  }
}