<?php
/**
 * ReturnOrderInterface.php
 *
 * @copyright Copyright © 2022 Onecode P.C.  All rights reserved.
 * @author    Spyros Bodinis {spyros@onecode.gr}
 */

namespace Onecode\ShopFlixConnector\Library\Interfaces;

interface ReturnOrderInterface
{
    const SHOPFLIX_ORDER_ID = "shopflix_order_id";
    const SHOPFLIX_PARENT_ORDER_ID = "shopflix_parent_order_id";
    const INCREMENT_ID = "increment_id";
    const STATUS = "status";
    const STATE  = "state";
    const SUBTOTAL = "subtotal";
    const TOTAL_PAID = "total_paid";
    const CUSTOMER_EMAIL = "customer_email";
    const CUSTOMER_FIRSTNAME = "customer_firstname";
    const CUSTOMER_LASTNAME = "customer_lastname";
    const CUSTOMER_REMOTE_IP = "customer_remote_ip";
    const CUSTOMER_NOTE = "customer_note";
    const CREATED_AT = "created_at";


    const STATUS_RETURN_REQUESTED = "return_requested";
    const STATUS_ON_THE_WAY_TO_THE_STORE = "on_the_way_to_the_store";
    const STATUS_DELIVERED_TO_THE_STORE = "delivered_to_the_store";
    const STATUS_RETURN_APPROVED = "approved";
    const STATUS_RETURN_DECLINED = "declined";
    const STATUS_RETURN_COMPLETED = "completed";


    const STATE_PROCESS_FROM_SHOPFLIX = "process_from_shopflix";
    const STATE_DELIVERED_TO_THE_STORE = "delivered";
    const STATE_APPROVED = "approved";
    const STATE_DECLINED = "declined";

}