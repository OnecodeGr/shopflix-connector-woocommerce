<?php
/**
 * ItemInterface.php
 *
 * @copyright Copyright © 2021 Onecode  All rights reserved.
 * @author    Spyros Bodinis {spyros@onecode.gr}
 */

namespace Onecode\MarketplaceConnector\Library\Api\Data;


interface ItemInterface
{
    const ITEM_ID = "item_id";
    const SKU = "sku";
    const PRICE = "price";
    const NAME = "name";
    const QTY = "qty";
    const PRODUCT_TYPE = "product_type";
    const PRODUCT_ID = "product_id";
    const PARENT_ITEM_ID = "parent_item_id";
    const ORDER_ID = "parent_id";

    const ATTRIBUTES = [
        self::SKU,
        self::PRICE,
        self::NAME,
        self::QTY,
        self::PRODUCT_TYPE,
        self::PRODUCT_ID,
        self::PARENT_ITEM_ID,
        self::ORDER_ID
    ];

    /**
     * @return $this
     */
    public function getId();

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id);

    /**
     * @return int
     */
    public function getOrderId(): int;

    /**
     * Gets the item ID for the order item.
     *
     * @return int|null Item ID.
     */
    public function getItemId(): int;


    /**
     * Sets the item ID for the order item.
     *
     * @param int $id
     * @return $this
     */
    public function setItemId(int $id): ItemInterface;

    /**
     * @param int $orderId
     * @return $this
     */
    public function setOrderId(int $orderId);


    /**
     * @return int
     */
    public function getParentItemId(): int;

    /**
     * @param int $parentItemId
     * @return $this
     */
    public function setParentItemId(int $parentItemId): ItemInterface;

    /**
     * @return int
     */
    public function getProductId(): int;


    /**
     * @param int $productId
     * @return $this
     */
    public function setProductId(int $productId): ItemInterface;

    /**
     * @return string
     */
    public function getProductType(): string;

    /**
     * @param string $productType
     * @return $this
     */
    public function setProductType(string $productType): ItemInterface;

    /**
     * @return string
     */
    public function getSku(): string;

    /**
     * @param string $sku
     * @return $this
     */
    public function setSku(string $sku): ItemInterface;

    /**
     * @return float
     */
    public function getPrice(): float;

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice(float $price): ItemInterface;

    /**
     * @return int
     */
    public function getQty(): int;

    /**
     * @param int $qty
     * @return $this
     */
    public function setQty(int $qty): ItemInterface;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name);

}
