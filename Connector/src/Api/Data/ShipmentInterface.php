<?php
/**
 * ShipmentInterface.php
 *
 * @copyright Copyright © 2021 Onecode  All rights reserved.
 * @author    Spyros Bodinis {spyros@onecode.gr}
 */

namespace Onecode\MarketplaceConnector\Library\Api\Data;



interface ShipmentInterface
{
    /**
     * Entity ID.
     */
    const ENTITY_ID = 'entity_id';
    /**
     * Order ID.
     */
    const ORDER_ID = 'order_id';
    /**
     * Shipping address ID.
     */
    const SHIPPING_ADDRESS_ID = 'shipping_address_id';
    /**
     * Billing address ID.
     */
    const BILLING_ADDRESS_ID = 'billing_address_id';
    /**
     * Shipment status.
     */
    const SHIPMENT_STATUS = 'shipment_status';
    /**
     * Increment ID.
     */
    const INCREMENT_ID = 'increment_id';
    /**
     * Created-at timestamp.
     */
    const CREATED_AT = 'created_at';
    /**
     * Updated-at timestamp.
     */
    const UPDATED_AT = 'updated_at';
    /**
     * Items.
     */
    const ITEMS = 'items';
    /**
     * Tracks.
     */
    const TRACKS = 'tracks';

    /**
     * Gets the billing address ID for the shipment.
     *
     * @return int|null Billing address ID.
     */
    public function getBillingAddressId();

    /**
     * Gets the created-at timestamp for the shipment.
     *
     * @return string|null Created-at timestamp.
     */
    public function getCreatedAt();

    /**
     * Sets the created-at timestamp for the shipment.
     *
     * @param string $createdAt timestamp
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * Gets the ID for the shipment.
     *
     * @return int|null Shipment ID.
     */
    public function getEntityId();

    /**
     * Sets entity ID.
     *
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId);


    /**
     * Gets the increment ID for the shipment.
     *
     * @return string|null Increment ID.
     */
    public function getIncrementId();

    /**
     * Gets the order ID for the shipment.
     *
     * @return int Order ID.
     */
    public function getOrderId();

    /**
     * Gets the shipment status.
     *
     * @return int|null Shipment status.
     */
    public function getShipmentStatus();

    /**
     * Gets the shipping address ID for the shipment.
     *
     * @return int|null Shipping address ID.
     */
    public function getShippingAddressId();

    /**
     * Gets the updated-at timestamp for the shipment.
     *
     * @return string|null Updated-at timestamp.
     */
    public function getUpdatedAt();

    /**
     * Sets the items for the shipment.
     *
     * @param ShipmentItemInterface[] $items
     * @return $this
     */
    public function setItems($items);

    /**
     * Gets the items for the shipment.
     *
     * @return ShipmentItemInterface[]
     */
    public function getItems();

    /**
     * Gets the tracks for the shipment.
     *
     * @return ShipmentTrackInterface[] Array of tracks.
     */
    public function getTracks();

    /**
     * Sets the tracks for the shipment.
     *
     * @param ShipmentTrackInterface[] $tracks
     * @return $this
     */
    public function setTracks($tracks);

    /**
     * Sets the order ID for the shipment.
     *
     * @param int $id
     * @return $this
     */
    public function setOrderId($id);

    /**
     * Sets the shipping address ID for the shipment.
     *
     * @param int $id
     * @return $this
     */
    public function setShippingAddressId($id);

    /**
     * Sets the billing address ID for the shipment.
     *
     * @param int $id
     * @return $this
     */
    public function setBillingAddressId($id);

    /**
     * Sets the shipment status.
     *
     * @param int $shipmentStatus
     * @return $this
     */
    public function setShipmentStatus($shipmentStatus);

    /**
     * Sets the increment ID for the shipment.
     *
     * @param string $id
     * @return $this
     */
    public function setIncrementId($id);

    /**
     * Sets the updated-at timestamp for the shipment.
     *
     * @param string $timestamp
     * @return $this
     */
    public function setUpdatedAt($timestamp);


}
