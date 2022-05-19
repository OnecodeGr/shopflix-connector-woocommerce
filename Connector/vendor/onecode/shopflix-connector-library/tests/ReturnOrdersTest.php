<?php
/**
 * ReturnOrdersTest.php
 *
 * @copyright Copyright © 2022 ${ORGANIZATION_NAME}  All rights reserved.
 * @author    Spyros Bodinis {spyros@onecode.gr}
 */

namespace Spyrmp\ShopFlixConnector\Tests;

use Onecode\ShopFlixConnector\Library\Connector;
use PHPUnit\Framework\TestCase;

class ReturnOrdersTest extends TestCase
{
    private $connector;

    public function setUp(): void
    {
        parent::setUp();
        $this->connector = new Connector($_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV["API_URL"] , "-7 days");
        $this->filepath = __DIR__ . "/return_orders/";
        if (!is_dir($this->filepath)) mkdir($this->filepath, 0777, true);
    }

    public function testGetDeclinedReturnedOrders()
    {
        $response = $this->connector->getDeclinedReturnedOrders();
        $this->assertNull($this->writeResponse("declined_returned.json", json_encode($response, JSON_PRETTY_PRINT)));
    }

    private function writeResponse($filename, $content)
    {
        file_put_contents($this->filepath . $filename, $content);

    }

    public function testGetNewReturnedOrders()
    {
        $response = $this->connector->getNewReturnedOrders();
        $this->assertNull($this->writeResponse("new_returned_orders.json", json_encode($response, JSON_PRETTY_PRINT)));
    }

    public function testGetCompletedReturnedOrders()
    {
        $response = $this->connector->getCompletedReturnedOrders();
        $this->assertNull($this->writeResponse("completed.json", json_encode($response, JSON_PRETTY_PRINT)));
    }

    public function testGetApprovedReturnOrders()
    {
        $response = $this->connector->getApprovedReturnOrders();
        $this->assertNull($this->writeResponse("approved.json", json_encode($response, JSON_PRETTY_PRINT)));

    }

    public function testGetOnTheWayToStoreReturnedOrders()
    {
        $response = $this->connector->getOnTheWayToStoreReturnedOrders();

        $this->assertNull($this->writeResponse("on_the_way_returned.json", json_encode($response, JSON_PRETTY_PRINT)));
    }

    public function testGetDeliveredToStoreReturnedOrders()
    {
        $response = $this->connector->getDeliveredToStoreReturnedOrders();
        $this->assertNull($this->writeResponse("delivered.json", json_encode($response, JSON_PRETTY_PRINT)));
    }
}
