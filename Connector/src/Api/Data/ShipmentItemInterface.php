<?php
/**
 * ShipmentItemInterface.php
 *
 * @copyright Copyright © 2021 Onecode  All rights reserved.
 * @author    Spyros Bodinis {spyros@onecode.gr}
 */

namespace Onecode\MarketplaceConnector\Library\Api\Data;



interface ShipmentItemInterface extends LineItemInterface
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
     * Row total.
     */
    const ROW_TOTAL = 'row_total';
    /**
     * Price.
     */
    const PRICE = 'price';
    /**
     * Order item ID.
     */
    const ORDER_ITEM_ID = 'order_item_id';
    /**
     * Name.
     */
    const NAME = 'name';
    /**
     * SKU.
     */
    const SKU = 'sku';
    /**
     * QTY.
     */
    const QTY = 'qty';

    /**
     * Gets the ID for the shipment item.
     *
     * @return int|null Shipment item ID.
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
     * Gets the name for the shipment item.
     *
     * @return string|null Name.
     */
    public function getName();

    /**
     * Gets the parent ID for the shipment item.
     *
     * @return int|null Parent ID.
     */
    public function getParentId();

    /**
     * Gets the price for the shipment item.
     *
     * @return float|null Price.
     */
    public function getPrice();


    /**
     * Gets the row total for the shipment item.
     *
     * @return float|null Row total.
     */
    public function getRowTotal();

    /**
     * Gets the SKU for the shipment item.
     *
     * @return string|null SKU.
     */
    public function getSku();


    /**
     * Sets the parent ID for the shipment item.
     *
     * @param int $id
     * @return $this
     */
    public function setParentId($id);

    /**
     * Sets the row total for the shipment item.
     *
     * @param float $amount
     * @return $this
     */
    public function setRowTotal($amount);

    /**
     * Sets the price for the shipment item.
     *
     * @param float $price
     * @return $this
     */
    public function setPrice($price);


    /**
     * Sets the name for the shipment item.
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Sets the SKU for the shipment item.
     *
     * @param string $sku
     * @return $this
     */
    public function setSku($sku);
}
