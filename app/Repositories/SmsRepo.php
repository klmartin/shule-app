<?php

namespace App\Repositories;

use Twilio\Rest\Client;

class SmsRepo
{
	public function send_sms($phoneno,$message)
    {
        $receiverNumber = $phoneno;
        try {
  
            $account_sid = getenv("TWILIO_SID");
            $auth_token = getenv("TWILIO_TOKEN");
            $twilio_number = getenv("TWILIO_FROM");
  
            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number, 
                'body' => $message]);
  
            return 'SMS Sent Successfully';
  
        } catch (Exception $e) {
            return "Error: ". $e->getMessage();
        }
    }
}