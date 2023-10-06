<?php
namespace App\Services;
use GuzzleHttp\Client;      
use Guzzle\Http\Exception\ClientErrorResponseException;

class SMSService {

    public $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function registration($phone, $message) {
        $url = 'https://apisms.beem.africa/v1/send';
        $api_key = config('services.sms.key');
        $api_secret = config('services.sms.secret');

        try {
            $response = $this->client->request('POST', $url, [
                "verify" => false,
                'headers' => [
                    'Authorization' => sprintf('Basic %s', base64_encode("$api_key:$api_secret")),
                    'Content-Type' => 'application/json'
                ],
                "json" => [
                    'source_addr' => 'INFO',
                    'encoding'=>0,
                    'schedule_time' => '',
                    'message' => $message,
                    'recipients' => [array('recipient_id' => '1','dest_addr'=>$phone)]
                ]
            ]);
            $statuscode = $response->getStatusCode();
            if ($statuscode == 200) {
                $responseData = json_decode($response->getBody()->getContents()); 
                echo json_encode($responseData);               
            }
        } catch (ClientErrorResponseException $exception) {
            $responseBody = $exception->getResponse()->getBody(true);
            return response()->json($responseBody);
        }
    }
}