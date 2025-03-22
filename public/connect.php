$client = new \GuzzleHttp\Client();
$response = $client->request('GET', 'http://api.openweathermap.org/data/2.5/weather', [
    'query' => [
        'q' => 'Santiago,CL',
        'appid' => '010812aedca4fcccc144a35e18d1c873'
    ]
]);
$data = json_decode($response->getBody(), true);
