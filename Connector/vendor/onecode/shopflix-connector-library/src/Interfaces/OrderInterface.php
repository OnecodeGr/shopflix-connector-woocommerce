<?php
/**
 * OrderInterface.php
 *
 * @copyright Copyright © 2021 Onecode  All rights reserved.
 * @author    Spyros Bodinis {spyros@onecode.gr}
 */

namespace Onecode\ShopFlixConnector\Library\Interfaces;


interface OrderInterface
{
    const SHOPFLIX_ORDER_ID = "shopflix_order_id";
    const INCREMENT_ID = "increment_id";
    const STATUS = "status";
    const SUBTOTAL = "subtotal";
    const DISCOUNT_AMOUNT = "discount_amount";
    const TOTAL_PAID = "total_paid";
    const CUSTOMER_EMAIL = "customer_email";
    const CUSTOMER_FIRSTNAME = "customer_firstname";
    const CUSTOMER_LASTNAME = "customer_lastname";
    const CUSTOMER_REMOTE_IP = "customer_remote_ip";
    const CUSTOMER_NOTE = "customer_note";
    const STATE = "state";
    const CREATED_AT = "created_at";


    const STATE_ACCEPTED = "accepted";
    const STATE_REJECTED = "rejected";
    const STATE_CANCELED = "canceled";
    const STATE_PENDING_ACCEPTANCE = "pending_acceptance";
    const STATE_COMPLETED = "completed";

    const STATUS_PICKING = "picking";
    const STATUS_ACCEPTED = "accepted";
    const STATUS_READY_TO_BE_SHIPPED = "ready_to_be_shipped";
    const STATUS_PENDING_ACCEPTANCE = "pending_acceptance";
    const STATUS_REJECTED = "rejected";
    const STATUS_ON_THE_WAY = "on_the_way";
    const STATUS_COMPLETED = "completed";
    const STATUS_SHIPPED = "shipped";
    const STATUS_CANCELED = "canceled";
    const STATUS_PARTIAL_SHIPPED = "partial_shipped";

    const COMPANY_NAME = "company_name";
    const IS_INVOICE = "is_invoice";
    const COMPANY_OWNER = "company_owner";
    const COMPANY_ADDRESS = "company_address";
    const COMPANY_VAT_NUMBER = "company_vat_number";
    const TAX_OFFICE = "tax_office";
}
