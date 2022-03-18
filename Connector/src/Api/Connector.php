<?php
/**
 * Connector.php
 *
 * @copyright Copyright © 2021   All rights reserved.
 * @author    Spyros Bodinis {spyros@onecode.gr}
 */

namespace Onecode\ShopFlixConnector\Library;

use DateTime;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\RequestOptions;
use InvalidArgumentException;
use Monolog\Handler\StreamHandler;
use Psr\Http\Message\RequestInterface;
use Spyrmp\JsonSerializerDeserializer\Json;
use Onecode\ShopFlixConnector\Library\Interfaces\AddressInterface;
use Onecode\ShopFlixConnector\Library\Interfaces\ItemInterface;
use Onecode\ShopFlixConnector\Library\Interfaces\OrderInterface;
use Onecode\ShopFlixConnector\Library\Interfaces\ShipmentInterface;
use Onecode\ShopFlixConnector\Library\Interfaces\ShipmentItemInterface;
use Onecode\ShopFlixConnector\Library\Interfaces\ShipmentTrackInterface;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use Monolog\Logger;

/**
 * Class Connector
 * @package Onecode\ShopFlixConnector\Library
 */
class Connector
{

    const SHOPFLIX_NEW_ORDER_STATUS = "O";
    const SHOPFLIX_CANCEL_ORDER_STATUS = "I";
    const SHOPFLIX_PARTIAL_ORDER_STATUS = "E";
    const SHOPFLIX_READY_TO_SHIPPED_STATUS = "H";
    const SHOPFLIX_SHIPPED_ORDER_STATUS = "K";
    const SHOPFLIX_COMPLETED_ORDER_STATUS = "C";
    const SHOPFLIX_ON_THE_WAY_ORDER_STATUS = "J";
    const SHOPFLIX_REJECTED_STATUS = "D";


    const SHOPFLIX_COMPANY_NAME = "103";
    const SHOPFLIX_IS_INVOICE = "115";
    const SHOPFLIX_COMPANY_OWNER = "116";
    const SHOPFLIX_COMPANY_ADDRESS = "117";
    const SHOPFLIX_COMPANY_VAT_NUMBER = "119";
    const SHOPFLIX_TAX_OFFICE = "120";


    /**
     * @var Client
     */
    private $_httpClient;
    private $_jsonSerializer;
    private $_baseUrl;
    private $_path;
    private $_username;
    private $_password;
    /**
     * @var int
     */
    private $_startTime;
    /**
     * @var int
     */
    private $_endTime;


    private $_debug = false;


    private $_clientRequestData = [];

    /**
     * @param $username
     * @param $apikey
     * @param $apiUrl
     * @param $modifier
     * @param $debugMode
     */
    public function __construct($username, $apikey, $apiUrl, $modifier = "-6 hours")
    {
        $this->_username = $username;
        $this->_password = $apikey;

        $this->_baseUrl = $apiUrl;
        $this->_jsonSerializer = new Json;
        $this->_clientRequestData = ["timeout" => 90, 'auth' => [$this->_username, $this->_password]];


        $this->initiateClient();
        $dateTime = new DateTime();

        $this->_endTime = $dateTime->getTimestamp();
        $dateTime->modify($modifier);
        $this->_startTime = $dateTime->getTimestamp();
    }

    private function initiateClient()
    {
        $urlParts = parse_url($this->_baseUrl);

        $uri = preg_replace('/^www\./', '', ($urlParts['scheme'] ?? "http") . "://" . $urlParts['host']);
        $this->_path = $urlParts['path'] . "/" ?? '';
        $this->_clientRequestData["base_uri"] = $uri;
        $this->_httpClient = new Client($this->_clientRequestData);
    }


    public function getNewOrders(): array
    {
        return $this->getOrders(self::SHOPFLIX_NEW_ORDER_STATUS);
    }

    private function getOrders($orderStatus, $startTime = false, $endTime = false): array
    {
        $data = [];

        $path = $this->_path . "orders";
        $query = $this->getOrderQueryByStatus($orderStatus, $startTime, $endTime);


        for ($page = 1; $page <= $this->getPageForOrders($query); $page++) {
            $query['page'] = $page;

            $response = $this->_httpClient->get($path, ['query' => $query]);
            $responseObject = $this->_jsonSerializer->deserialize($response->getBody()->getContents());
            foreach ($responseObject['orders'] as $order) {
                $data[] = $this->getOrderDetail($order['order_id']);
            }
        }
        return $data;
    }

    private function getOrderQueryByStatus($orderStatus, $startTime, $endTime): array
    {

        $data = [
            "status" => $orderStatus
        ];

        if ($startTime && $endTime) {
            $data['period'] = "C";
            $data['time_from'] = $startTime;
            $data['time_to'] = $endTime;
        }

        return $data;


    }


    private function getPageForOrders($query, $die = false): int
    {
        $path = $this->_path . "orders";
        $response = $this->_httpClient->get($path, ['query' => $query]);

        $responseObject = $this->_jsonSerializer->deserialize($response->getBody()->getContents());
        if ($die) {
            dd(json_encode($responseObject), $this->_username, $this->_password);
        }
        $itemPerPages = $responseObject['params']['items_per_page'];
        $totalItems = $responseObject['params']['total_items'];
        return (int)ceil($totalItems / $itemPerPages);

    }

    /**
     * @param $orderId
     * @return array
     * @throws GuzzleException
     */
    public function getOrderDetail($orderId): array
    {
        $data = [];
        if ($orderId) {
            $path = $this->_path . "orders/$orderId";
            $response = $this->_httpClient->get($path);
            $responseObject = $this->_jsonSerializer->deserialize($response->getBody()->getContents());

            $data = [
                "order" =>
                    [
                        OrderInterface::SHOPFLIX_ORDER_ID => $responseObject['order_id'],
                        OrderInterface::INCREMENT_ID => $responseObject['order_id'],
                        OrderInterface::STATE => $this->getState($responseObject['status']),
                        OrderInterface::STATUS => $this->getStatus($responseObject['status']),
                        OrderInterface::SUBTOTAL => $responseObject['subtotal'],
                        OrderInterface::DISCOUNT_AMOUNT => $responseObject['discount'],
                        OrderInterface::TOTAL_PAID => $responseObject['total'],
                        OrderInterface::CUSTOMER_EMAIL => $responseObject['email'],
                        OrderInterface::CUSTOMER_FIRSTNAME => $responseObject['firstname'],
                        OrderInterface::CUSTOMER_LASTNAME => $responseObject['lastname'],
                        OrderInterface::CUSTOMER_REMOTE_IP => $responseObject['ip_address'] ?? "",
                        OrderInterface::CUSTOMER_NOTE => $responseObject['notes'],
                        OrderInterface::CREATED_AT => $responseObject['timestamp']
                    ],
                "addresses" => [
                    [
                        AddressInterface::FIRSTNAME => !empty($responseObject["s_firstname"]) ? $responseObject["s_firstname"] : $responseObject['firstname'],
                        AddressInterface::LASTNAME => !empty($responseObject["s_lastname"]) ? $responseObject["s_lastname"] : $responseObject['lastname'],
                        AddressInterface::POSTCODE => $responseObject["s_zipcode"],
                        AddressInterface::TELEPHONE => !empty($responseObject["s_phone"]) ? $responseObject["s_phone"] : $responseObject['phone'],
                        AddressInterface::STREET => $responseObject["s_address"],
                        AddressInterface::ADDRESS_TYPE => "shipping",
                        AddressInterface::CITY => $responseObject['s_city'],
                        AddressInterface::EMAIL => $responseObject['email'],
                        AddressInterface::COUNTRY_ID => $responseObject['s_country'],

                    ],
                    [
                        AddressInterface::FIRSTNAME => !empty($responseObject["b_firstname"]) ? $responseObject["b_firstname"] : $responseObject['firstname'],
                        AddressInterface::LASTNAME => !empty($responseObject["b_lastname"]) ? $responseObject["b_lastname"] : $responseObject['lastname'],
                        AddressInterface::POSTCODE => $responseObject["b_zipcode"],
                        AddressInterface::TELEPHONE => !empty($responseObject["b_phone"]) ? $responseObject["b_phone"] : $responseObject['phone'],
                        AddressInterface::STREET => $responseObject["b_address"],
                        AddressInterface::ADDRESS_TYPE => "billing",
                        AddressInterface::CITY => $responseObject['b_city'],
                        AddressInterface::EMAIL => $responseObject['email'],
                        AddressInterface::COUNTRY_ID => $responseObject['b_country'],
                    ]
                ],
                "items" => [],

            ];

            if (isset($responseObject["fields"][self::SHOPFLIX_IS_INVOICE]) && $responseObject["fields"][self::SHOPFLIX_IS_INVOICE] == "Y") {
                $data[OrderInterface::IS_INVOICE] = true;
                $data["invoice"] = [
                    OrderInterface::COMPANY_NAME => $responseObject["fields"][self::SHOPFLIX_COMPANY_NAME] ?? $responseObject["fields"][self::SHOPFLIX_COMPANY_OWNER],
                    OrderInterface::COMPANY_ADDRESS => $responseObject["fields"][self::SHOPFLIX_COMPANY_ADDRESS],
                    OrderInterface::COMPANY_OWNER => $responseObject["fields"][self::SHOPFLIX_COMPANY_OWNER],
                    OrderInterface::COMPANY_VAT_NUMBER => $responseObject["fields"][self::SHOPFLIX_COMPANY_VAT_NUMBER],
                    OrderInterface::TAX_OFFICE => $responseObject["fields"][self::SHOPFLIX_TAX_OFFICE],
                ];
            } else {
                $data[OrderInterface::IS_INVOICE] = false;
            }


            foreach ($responseObject['products'] as $product) {
                $data["items"][] = [
                    ItemInterface::SKU => $product['product_code'],
                    ItemInterface::PRICE => $product['price'],
                    ItemInterface::QTY => $product['amount']
                ];
            }

        }
        return $data;
    }


    private function getState($status)
    {
        switch ($status) {
            case self::SHOPFLIX_NEW_ORDER_STATUS:
                return OrderInterface::STATE_PENDING_ACCEPTANCE;
            case self::SHOPFLIX_CANCEL_ORDER_STATUS:
                return OrderInterface::STATE_CANCELED;
            case self::SHOPFLIX_READY_TO_SHIPPED_STATUS;
            case self::SHOPFLIX_PARTIAL_ORDER_STATUS:
            case self::SHOPFLIX_SHIPPED_ORDER_STATUS:
            case self::SHOPFLIX_COMPLETED_ORDER_STATUS:
            case self::SHOPFLIX_ON_THE_WAY_ORDER_STATUS:
                return OrderInterface::STATE_COMPLETED;
            case self::SHOPFLIX_REJECTED_STATUS:
                return OrderInterface::STATE_REJECTED;
        }
    }

    private function getStatus($status)
    {
        switch ($status) {
            case self::SHOPFLIX_NEW_ORDER_STATUS:
                return OrderInterface::STATUS_PENDING_ACCEPTANCE;
            case self::SHOPFLIX_CANCEL_ORDER_STATUS:
                return OrderInterface::STATUS_CANCELED;
            case self::SHOPFLIX_PARTIAL_ORDER_STATUS:
                return OrderInterface::STATUS_PARTIAL_SHIPPED;
            case self::SHOPFLIX_READY_TO_SHIPPED_STATUS:
                return OrderInterface::STATUS_READY_TO_BE_SHIPPED;
            case self::SHOPFLIX_SHIPPED_ORDER_STATUS:
                return OrderInterface::STATUS_SHIPPED;
            case self::SHOPFLIX_COMPLETED_ORDER_STATUS:
                return OrderInterface::STATUS_COMPLETED;
            case self::SHOPFLIX_ON_THE_WAY_ORDER_STATUS:
                return OrderInterface::STATUS_ON_THE_WAY;
            case self::SHOPFLIX_REJECTED_STATUS:
                return OrderInterface::STATUS_REJECTED;

        }

    }

    public function getPartialShipped()
    {

        return $this->getOrders(
            self::SHOPFLIX_PARTIAL_ORDER_STATUS,
            $this->_startTime,
            $this->_endTime
        );
    }

    public function getShipped()
    {

        return $this->getOrders(
            self::SHOPFLIX_SHIPPED_ORDER_STATUS,
            $this->_startTime,
            $this->_endTime
        );
    }

    public function getCompletedOrders()
    {

        return $this->getOrders(
            self::SHOPFLIX_COMPLETED_ORDER_STATUS,
            $this->_startTime,
            $this->_endTime
        );
    }


    public function getCancelOrders()
    {

        return $this->getOrders(
            self::SHOPFLIX_CANCEL_ORDER_STATUS,
            $this->_startTime,
            $this->_endTime
        );
    }


    public function getOnTheWayOrders()
    {

        return $this->getOrders(
            self::SHOPFLIX_ON_THE_WAY_ORDER_STATUS,
            $this->_startTime,
            $this->_endTime
        );
    }

    /**
     * @throws Exception
     */
    public function picking($orderId)
    {
        $requestData = ["status" => 'G', "notify_user" => 1, "notify_department" => 0, "notify_vendor" => 0];

        $this->updateOrder($orderId, $requestData);

    }

    /**
     * @throws Exception
     */
    private function updateOrder($orderId, $requestData = [])
    {
        $path = $this->_path . "orders/$orderId";
        $response = $this->_httpClient->put($path, [RequestOptions::JSON => $requestData]);
        if ($response->getStatusCode() >= 400 && $response->getStatusCode() <= 500) {
            throw new Exception($response->getBody()->getContents());
        }


        try {
            $this->_jsonSerializer->deserialize($response->getBody()->getContents());
        } catch (InvalidArgumentException $e) {
            throw new Exception($response->getBody()->getContents());
        }


    }

    /**
     * @throws Exception|GuzzleException
     */
    public function forShipment($shipmentId)
    {
        $requestData = [
            "status" => 'A',
            "carrier" => 'funship'
        ];

        $this->updateShipment($shipmentId, $requestData);
    }

    /**
     * @param $shipmentId
     * @param $requestData
     * @return void
     * @throws GuzzleException|Exception
     */
    private function updateShipment($shipmentId, $requestData = [])
    {

        $path = $this->_path . "shipments/$shipmentId";
        $response = $this->_httpClient->put($path, [RequestOptions::JSON => $requestData]);

        if ($response->getStatusCode() >= 400 && $response->getStatusCode() <= 500) {
            throw new Exception($response->getBody()->getContents());
        }


        try {
            $this->_jsonSerializer->deserialize($response->getBody()->getContents());
        } catch (InvalidArgumentException $e) {
            throw new Exception($response->getBody()->getContents());
        }
    }

    /**
     * @param $orderId
     * @param $message
     * @throws Exception
     */
    public function rejected($orderId, $message)
    {
        $requestData = [
            "status" => self::SHOPFLIX_REJECTED_STATUS,
            "notify_user" => 0,
            "notify_department" => 0,
            "notify_vendor" => 0,
            "details" => $message
        ];

        $this->updateOrder($orderId, $requestData);

    }

    /**
     * @param $orderId
     * @throws Exception
     */
    public function readyToBeShipped($orderId)
    {
        $requestData = [
            "status" => 'H',
            "notify_user" => 0,
            "notify_department" => 0,
            "notify_vendor" => 0
        ];
        $this->updateOrder($orderId, $requestData);
    }


    public function printManifest($shipments)
    {
        $path = $this->_path . "courier";
        $response = $this->_httpClient->get($path, [
                "query" => [
                    "custom_manifest" => 1,
                    "shipments" => implode(",", $shipments)
                ]
            ]
        );
        $content = $response->getBody()->getContents();
        return $this->_jsonSerializer->deserialize($content);
    }

    public function getManifest()
    {
        $path = $this->_path . "courier";

        $response = $this->_httpClient->get($path, ['query' => ['manifest' => 1,]]);

        $content = $response->getBody()->getContents();
        return $this->_jsonSerializer->deserialize($content);
    }

    public function printVoucher($voucher, $labelFormat = "pdf")
    {
        $path = $this->_path . "courier";
        $query = $this->getPrintQuery($labelFormat, $voucher);
        $response = $this->_httpClient->get($path, ['query' => $query, "debug" => $this->_debug]);
        $content = $response->getBody()->getContents();
        return $this->_jsonSerializer->deserialize($content);

    }

    private function getPrintQuery($labelFormat, $voucher, $type = "print")
    {

        $query = [];
        if ($type == "print") {
            $query['print'] = $voucher;
        } else {
            $query['printmass'] = implode(",", $voucher);
        }

        switch ($labelFormat) {
            default:
                $query['labelFormat'] = $labelFormat;
                break;
            case "singlepdf_100x150":
                $query['labelFormat'] = $labelFormat;
                $query['p'] = "thermiko";
                break;
        }
        return $query;

    }

    public function createVoucher($shipmentId)
    {
        $path = $this->_path . "courier/{$shipmentId}";
        $response = $this->_httpClient->get($path);
        $content = $response->getBody()->getContents();
        return $this->_jsonSerializer->deserialize($content);
    }

    public function getShipmentUrl($shipmentId)
    {
        $path = $this->_path . "shipments";
        $response = $this->_httpClient->get($path, ['query' => ['shipment_id' => $shipmentId,]]);
        $content = $response->getBody()->getContents();
        $json = $this->_jsonSerializer->deserialize($content);

        return $json['shipments'][0]['carrier_info']['tracking_url'];
    }

    public function getVoucher($shipmentId)
    {
        $path = $this->_path . "shipments";
        $response = $this->_httpClient->get($path, ['query' => ['shipment_id' => $shipmentId,]]);
        $content = $response->getBody()->getContents();
        $json = $this->_jsonSerializer->deserialize($content);

        return $json['shipments'][0]['tracking_number'] ?? "";
    }

    public function printVouchers($vouchers, $labelFormat = "pdf")
    {
        $path = $this->_path . "courier";
        $query = $this->getPrintQuery($labelFormat, $vouchers, "printmass");
        $response = $this->_httpClient->get($path, ['query' => $query, "debug" => $this->_debug]);
        $content = $response->getBody()->getContents();
        return $this->_jsonSerializer->deserialize($content);
    }

    public function getShipment($orderId)
    {
        $path = $this->_path . "shipments";
        $response = $this->_httpClient->get($path, ['query' => ['order_id' => $orderId,

        ]]);
        $content = $response->getBody()->getContents();
        $json = $this->_jsonSerializer->deserialize($content);
        $data = [];
        foreach ($json['shipments'] as $key => $shipment) {
            $data[$key] = [
                "shipment" =>
                    [
                        ShipmentInterface::INCREMENT_ID => $shipment["shipment_id"],
                        ShipmentInterface::SHIPMENT_STATUS => $this->getShippingStatus($shipment['status']),
                        ShipmentInterface::CREATED_AT => $shipment['shipment_timestamp'],
                    ],
                ShipmentInterface::ITEMS => [],
                ShipmentInterface::TRACKS => [
                    ShipmentTrackInterface::TRACK_NUMBER => $shipment['tracking_number'],
                    ShipmentTrackInterface::TRACKING_URL => $shipment['carrier_info']['tracking_url'],

                ],
            ];
            foreach ($shipment['products_info'] as $product) {
                $data[$key][ShipmentInterface::ITEMS][] = [
                    ShipmentItemInterface::SKU => $product['product_id'],
                    ShipmentItemInterface::QTY => $product['product_qty']
                ];
            }
        }

        return $data;
    }

    private function getShippingStatus($status)
    {
        switch ($status) {
            case "P":
                return 1; #pending
            case "A":
                return 2; #creted voucher
            case "S":
                return 3; #on the way
        }
    }

}

