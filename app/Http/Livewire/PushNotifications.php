<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PushNotifications extends Component
{
    public $enviar = false;
    public $title;
    public $body;

    public function render()
    {
        return view('livewire.push-notifications');
    }

    protected $rules = [
        'title' => 'required',
        'body' => 'required',
    ];

    public function sendPush()
    {
        $this->validate();

        $this->enviar = false;

        $url = 'https://fcm.googleapis.com/fcm/send';
        // $FcmToken = User::whereNotNull('device_key')->pluck('device_key')->all();

        $serverKey = env('FCM_SERVER_TOKEN');

        $datos = [
            "title" => $this->title,
            "body" => $this->body
        ];

        $data = [
            "to" => "/topics/sanciro",
            "data" => $datos,
            "priority" => "high",
            "content_available" => true
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        try {
            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                Log::warning('Curl error: ' . curl_error($ch));
            }
        } catch (\Exception $th) {
            return $th;
        }
        // Close connection
        curl_close($ch);
        // FCM response
        Log::warning(json_encode($result));
        // return $result;
    }
}
