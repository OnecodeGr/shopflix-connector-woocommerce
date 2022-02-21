<?php 

class connector {
	
	    const MARKETPLACE_NEW_ORDER_STATUS = "O";
    const MARKETPLACE_CANCEL_ORDER_STATUS = "I";
    const MARKETPLACE_PARTIAL_ORDER_STATUS = "E";
    const MARKETPLACE_SHIPPED_ORDER_STATUS = "K";
    const MARKETPLACE_ON_THE_WAY_ORDER_STATUS = "J";

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
	

	public function get_orders() {
	
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://buy247.gr/dev/api/orders?status=O',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic dmlydHVhbGl0eUBidXkyNDcuZ3I6QjI3c0dmR3VFMTRSMnU0MzdzOTk2M3I3bVh5Sjl5TzA='
  ),
));

$response = curl_exec($curl);

curl_close($curl);

		return $response;
	
	}



}

?>