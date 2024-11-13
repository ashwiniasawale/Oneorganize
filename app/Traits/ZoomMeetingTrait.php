<?php
namespace App\Traits;

use GuzzleHttp\Client;
use App\Models\Utility;
use Log;

/**
 * trait ZoomMeetingTrait
 */
trait ZoomMeetingTrait
{
    public $client;
    public $jwt;
    public $headers;
    public $meeting_url="https://api.zoom.us/v2/";
    public function __construct()
    {
        $this->client = new Client();
    }


    private function retrieveZoomUrl()
    {
        return $this->meeting_url;
    }

    public function toZoomTimeFormat(string $dateTime)
    {
        try {
            $date = new \DateTime($dateTime);

            return $date->format('Y-m-d\TH:i:s');
        } catch (\Exception $e) {
            Log::error('ZoomJWT->toZoomTimeFormat : '.$e->getMessage());

            return '';
        }
    }

    public function createmitting($data)
    {
        $path = 'users/me/meetings';
        $url = $this->retrieveZoomUrl();

        $body = [
            'headers' => $this->getHeader(),
            'body'    => json_encode([
                'topic'      => $data['title'],
                'type'       => self::MEETING_TYPE_SCHEDULE,
                'start_time' => $this->toZoomTimeFormat($data['start_time']),
                'duration'   => $data['duration'],
                'password' => $data['password'],
                'agenda'     => (! empty($data['agenda'])) ? $data['agenda'] : null,
                'timezone'     => 'Asia/Kolkata',
                'settings'   => [
                    'host_video'        => ($data['host_video'] == "1") ? true : false,
                    'participant_video' => ($data['participant_video'] == "1") ? true : false,
                    'waiting_room'      => true,
                ],
            ]),
        ];

        $response =  $this->client->post($url.$path, $body);

        return [
            'success' => $response->getStatusCode() === 201,
            'data'    => json_decode($response->getBody(), true),
        ];

    }

    public function meetingUpdate($id, $data)
    {
        $path = 'meetings/'.$id;
        $url = $this->retrieveZoomUrl();

        $body = [
            'headers' => $this->getHeader(),
            'body'    => json_encode([
                'topic'      => $data['title'],
                'type'       => self::MEETING_TYPE_SCHEDULE,
                'start_time' => $this->toZoomTimeFormat($data['start_time']),
                'duration'   => $data['duration'],
                'agenda'     => (! empty($data['agenda'])) ? $data['agenda'] : null,
                'timezone'     => config('app.timezone'),
                'settings'   => [
                    'host_video'        => ($data['host_video'] == "1") ? true : false,
                    'participant_video' => ($data['participant_video'] == "1") ? true : false,
                    'waiting_room'      => true,
                ],
            ]),
        ];


        $response =  $this->client->patch($url.$path, $body);

        return [
            'success' => $response->getStatusCode() === 204,
            'data'    => json_decode($response->getBody(), true),
        ];
    }

    public function get($id)
    {
        $path = 'meetings/'.$id;
        $url = $this->retrieveZoomUrl();

        $body = [
            'headers' => $this->getHeader(),
            'body'    => json_encode([]),
        ];

            $response =  $this->client->get($url.$path, $body);
            return [
                'success' => $response->getStatusCode() === 204,
                'data'    => json_decode($response->getBody(), true),
            ];


    }

    /**
     * @param string $id
     *
     * @return bool[]
     */
    public function delete($id)
    {
        $path = 'meetings/'.$id;
        $url = $this->retrieveZoomUrl();
        $body = [
            'headers' => $this->headers,
            'body'    => json_encode([]),
        ];

        $response =  $this->client->delete($url.$path, $body);

        return [
            'success' => $response->getStatusCode() === 204,
        ];
    }

    public function getHeader()
    {
        $token = $this->getToken();
    
        if ($token === false) {
            throw new \Exception('Unable to obtain Zoom token.');
        }
    
        return [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ];
    }

    public function getToken()
    {
        $settings = Utility::settings(\Auth::user()->id);
    
        if (
            isset($settings['zoom_client_id']) && !empty($settings['zoom_client_id']) &&
            isset($settings['zoom_client_secret']) && !empty($settings['zoom_client_secret'])
        ) {
            // Construct the basic authentication header
            $basicAuthHeader = base64_encode($settings['zoom_client_id'] . ':' . $settings['zoom_client_secret']);
    
            try {
                // Prepare the request to obtain the access token
                $response = $this->client->request('POST', 'https://zoom.us/oauth/token', [
                    'headers' => [
                        'Authorization' => 'Basic ' . $basicAuthHeader,
                        'Content-Type'  => 'application/x-www-form-urlencoded',
                    ],
                    'form_params' => [
                        'grant_type' => 'client_credentials',
                    ],
                ]);
    
                // Decode the response and retrieve the access token
                $token = json_decode($response->getBody(), true);
    
                if (isset($token['access_token'])) {
                    return $token['access_token'];
                } else {
                    \Log::error('Token response does not contain access_token: ' . json_encode($token));
                }
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                // Log detailed error information
                $responseBody = $e->getResponse()->getBody()->getContents();
                \Log::error('Zoom API Request Failed: ' . $responseBody);
                return false;
            } catch (\Exception $e) {
                // Log any other exceptions
                \Log::error('Exception during token request: ' . $e->getMessage());
                return false;
            }
        } else {
            \Log::error('Zoom credentials are missing or invalid.');
        }
    
        return false;
    }

}




 ?>
