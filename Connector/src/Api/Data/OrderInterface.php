<?php
/**
 * OrderInterface.php
 *
 * @copyright Copyright © 2021 Onecode  All rights reserved.
 * @author    Spyros Bodinis {spyros@onecode.gr}
 */

namespace Onecode\MarketplaceConnector\Library\Api\Data;


interface OrderInterface
{
    const ENTITY_ID = "entity_id";
    const MARKETPLACE_ORDER_ID = "marketplace_order_id";
    const MAGENTO_ORDER_ID = "magento_order_id";
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
    const SHIPPING_ADDRESS_ID = "shipping_address_id";
    const BILLING_ADDRESS_ID = "billing_address_id";
    const SYNCED = "sync";
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    const ITEMS = "items";
    const STATE = "state";

    const STATE_ACCEPTED = "accepted";
    const STATE_REJECTED = "rejected";
    const STATE_CANCELED = "canceled";
    const STATE_PENDING_ACCEPTANCE = "pending_acceptance";
    const STATE_COMPLETED = "completed";


    const STATUS_HISTORIES = 'status_histories';

    const ATTRIBUTES = [
        self::MARKETPLACE_ORDER_ID,
        self::MAGENTO_ORDER_ID,
        self::INCREMENT_ID,
        self::STATUS,
        self::SUBTOTAL,
        self::DISCOUNT_AMOUNT,
        self::TOTAL_PAID,
        self::CUSTOMER_EMAIL,
        self::CUSTOMER_FIRSTNAME,
        self::CUSTOMER_LASTNAME,
        self::CUSTOMER_REMOTE_IP,
        self::CUSTOMER_NOTE,
        self::SYNCED
    ];

    /**
     * @return int
     */
    public function getMarketplaceOrderId(): int;

    /**
     * @param int $marketplaceOrderId
     * @return $this
     */
    public function setMarketplaceOrderId(int $marketplaceOrderId): OrderInterface;


    /**
     * @return int|null
     */
    public function getMagentoOrderId(): ?int;

    /**
     * @param int $magentoOrderId
     * @return $this
     */
    public function setMagnetoOrderId(int $magentoOrderId): OrderInterface;

    /**
     * @return string|null
     */
    public function getState(): ?string;

    /**
     * @param string $state
     * @return $this
     */
    public function setState(string $state): OrderInterface;


    /**
     * @return string|null
     */
    public function getStatus(): ?string;

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): OrderInterface;

    /**
     * Sets status histories for the order.
     *
     * @param StatusHistoryInterface[] $statusHistories
     * @return $this
     */
    public function setStatusHistories(array $statusHistories = null): OrderInterface;

    /**
     * @return float
     */
    public function getSubtotal(): float;

    /**
     * @param float $subtotal
     * @return $this
     */
    public function setSubtotal(float $subtotal): OrderInterface;

    /**
     * @return float
     */
    public function getDiscountAmount(): float;

    /**
     * @param float $discountAmount
     * @return $this
     */
    public function setDiscountAmount(float $discountAmount): OrderInterface;

    /**
     * @return float
     */
    public function getTotalPaid(): float;

    /**
     * Gets status histories for the order.
     *
     * @return StatusHistoryInterface[]|null Array of status histories.
     */
    public function getStatusHistories(): ?array;

    /**
     * @param float $totalPaid
     * @return $this
     */
    public function setTotalPaid(float $totalPaid): OrderInterface;

    /**
     * @return string
     */
    public function getIncrementId(): string;

    /**
     * @param string $incrementId
     * @return $this
     */
    public function setIncrementId(string $incrementId): OrderInterface;

    /**
     * @return string
     */
    public function getCustomerEmail(): string;

    /**
     * @param string $customerEmail
     * @return $this
     */
    public function setCustomerEmail(string $customerEmail): OrderInterface;

    /**
     * @return string
     */
    public function getCustomerFirstname(): string;

    /**
     * @param string $customerFirstname
     * @return $this
     */
    public function setCustomerFirstname(string $customerFirstname): OrderInterface;

    /**
     * @return string
     */
    public function getCustomerLastname(): string;

    /**
     * @param string $customerLastname
     * @return $this
     */
    public function setCustomerLastname(string $customerLastname): OrderInterface;

    /**
     * @return string|null
     */
    public function getRemoteIp(): ?string;

    /**
     * @param string $remoteIp
     * @return $this
     */
    public function setRemoteIp(string $remoteIp): OrderInterface;

    /**
     * @return string
     */
    public function getCustomerNote(): string;

    /**
     * @param string $customerNote
     * @return $this
     */
    public function setCustomerNote(string $customerNote): OrderInterface;


    /**
     * Sets the billing address ID for the order.
     *
     * @param int $id
     * @return $this
     */
    public function setBillingAddressId(int $id): OrderInterface;

    /**
     * Gets the billing address ID for the order.
     *
     * @return int|null Billing address ID.
     */
    public function getBillingAddressId(): ?int;

    /**
     * Sets the shipping address ID for the order.
     *
     * @param int $id
     * @return $this
     */
    public function setShippingAddressId(int $id): OrderInterface;

    /**
     * Gets the shipping address ID for the order.
     *
     * @return int|null Shipping address ID.
     */
    public function getShippingAddressId(): ?int;

    /**
     * @param AddressInterface|null $billingAddress
     * @return $this
     */
    public function setBillingAddress(AddressInterface $billingAddress = null): OrderInterface;

    /**
     * @param AddressInterface|null $shippingAddress
     * @return $this
     */
    public function setShippingAddress(AddressInterface $shippingAddress = null): OrderInterface;

    /**
     * @param $items
     * @return $this
     */
    public function setItems($items): OrderInterface;

    /**
     * Gets the created-at timestamp for the order.
     *
     * @return string|null Created-at timestamp.
     */
    public function getCreatedAt(): ?string;

    /**
     * Sets the created-at timestamp for the order.
     *
     * @param string $createdAt timestamp
     * @return $this
     */
    public function setCreatedAt(string $createdAt): OrderInterface;

    /**
     * Gets the updated-at timestamp for the order.
     *
     * @return string|null Created-at timestamp.
     */
    public function getUpdatedAt(): ?string;

    /**
     * Sets the updated-at timestamp for the order.
     *
     * @param string $updatedAt timestamp
     * @return $this
     */
    public function setUpdatedAt(string $updatedAt): OrderInterface;

    /**
     * Gets the sync status for the order
     * @return bool
     */
    public function getSynced(): bool;

    /**
     * Sets the sync status for the order
     *
     * @param bool $sync true|false
     * @return OrderInterface
     */
    public function setSynced(bool $sync): OrderInterface;

    /**
     * Can auto accept the order
     *
     * @return bool
     */
    public function canAutoAccept(): bool;
}
