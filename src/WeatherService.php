<?php

namespace Drupal\open_weather_map;

use Drupal\Component\Utility\Html;
use Drupal\Core\Config\ConfigFactory;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\ClientInterface;
use Drupal\Core\Url;

/**
 * WeatherService.
 */
class WeatherService {

  /**
   * The HTTP client to fetch the feed data with.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  protected $configFactory;

  /**
   * Constructs a database object.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The Guzzle HTTP client.
   */
  public function __construct(ClientInterface $http_client, ConfigFactory $configFactory) {
    $this->httpClient = $http_client;
    $this->configFactory = $configFactory;
  }

  /**
   * Get a complete query for the API.
   */
  public function createRequest() {
    $query = [];
    $config = $this->configFactory->get('open_weather_map.settings');
    $appid_config = $this->configFactory->get('open_weather_map.settings')->get('appid');
    $query['appid'] = Html::escape($appid_config);
    // $query['cnt'] = $options['count'];
    $input_data = Html::escape($config->get('cityname'));
    $query['q'] = $input_data;
    return $query;
  }

  /**
   * Return the data from the API in xml format.
   */
  public function getWeatherInformation() {
    try {
      $response = $this->httpClient->request('GET','https://api.openweathermap.org/data/2.5/weather',
      [
        'query' => $this->createRequest(),
      ]);
    }
    catch (GuzzleException $e) {
      return FALSE;
    }
    return $response->getBody()->getContents();
  }

}
