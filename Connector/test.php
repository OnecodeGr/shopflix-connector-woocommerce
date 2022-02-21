<?php


require "vendor/autoload.php";


use Onecode\MarketplaceConnector\Library\Connector;

$connector = new Connector("virtuality@buy247.gr", "4dZ3460613164AWJpseXLY96X81a7rB9", "https://buy247.gr/wellcomm/api");
print_r($connector->getNewOrders());
