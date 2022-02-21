<?php
/**
 * ShipmentTrackInterface.php
 *
 * @copyright Copyright © 2021 Onecode  All rights reserved.
 * @author    Spyros Bodinis {spyros@onecode.gr}
 */

namespace Onecode\MarketplaceConnector\Library\Api\Data;

interface ShipmentTrackInterface
{
    /**
     * Entity ID.
     */
    const ENTITY_ID = 'entity_id';
    /**
     * Parent ID.
     */
    const PARENT_ID = 'parent_id';
    /**
     * Quantity.
     */
    const QTY = 'qty';
    /**
     * Order ID.
     */
    const ORDER_ID = 'order_id';
    /**
     * Track number.
     */
    const TRACK_NUMBER = 'track_number';
    /**
     * Tracking url.
     */
    const TRACKING_URL = 'tracking_url';
    /**
     * Created-at timestamp.
     */
    const CREATED_AT = 'created_at';
    /**
     * Updated-at timestamp.
     */
    const UPDATED_AT = 'updated_at';

    /**
     * Sets the order_id for the shipment package.
     *
     * @param int $id
     * @return $this
     */
    public function setOrderId($id);

    /**
     * Gets the order_id for the shipment package.
     *
     * @return int
     */
    public function getOrderId();

    /**
     * Gets the created-at timestamp for the shipment package.
     *
     * @return string|null Created-at timestamp.
     */
    public function getCreatedAt();

    /**
     * Sets the created-at timestamp for the shipment package.
     *
     * @param string $createdAt timestamp
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * Gets the ID for the shipment package.
     *
     * @return int|null Shipment package ID.
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
     * Gets the parent ID for the shipment package.
     *
     * @return int Parent ID.
     */
    public function getParentId();

    /**
     * Gets the updated-at timestamp for the shipment package.
     *
     * @return string|null Updated-at timestamp.
     */
    public function getUpdatedAt();

    /**
     * Sets the updated-at timestamp for the shipment package.
     *
     * @param string $timestamp
     * @return $this
     */
    public function setUpdatedAt($timestamp);

    /**
     * Sets the parent ID for the shipment package.
     *
     * @param int $id
     * @return $this
     */
    public function setParentId($id);

    /**
     * Sets the track number for the shipment package.
     *
     * @param string $trackNumber
     * @return $this
     */
    public function setTrackNumber($trackNumber);

    /**
     * Gets the Track Number for the shipment package.
     *
     * @return string Track Number.
     */
    public function getTrackNumber();

    /**
     * Sets the track url for the shipment package.
     *
     * @param string $trackingUrl
     * @return $this
     */
    public function setTrackingUrl($trackingUrl);

    /**
     * Gets the Track url for the shipment package.
     *
     * @return string Track url.
     */
    public function getTrackingUrl();
}
