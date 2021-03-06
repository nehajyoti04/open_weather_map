<?php

namespace Drupal\open_weather_map;

use Drupal\Component\Utility\Html;
use Drupal\Core\Config\ConfigFactory;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\ClientInterface;

/**
 * Class for WeatherService.
 */
class WeatherService {

  /**
   * The HTTP client to fetch the feed data with.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * Constructs a database object.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The Guzzle HTTP client.
   * @param \Drupal\Core\Config\ConfigFactory $configFactory
   *   The config Factory.
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
    $key_config = $this->configFactory->get('open_weather_map.settings')->get('key');
    $query['appid'] = Html::escape($key_config);
    $cityname = Html::escape($config->get('cityname'));
    $country_code = Html::escape($config->get('country_code'));

    $query['q'] = $cityname . "," . $country_code;
    return $query;
  }

  /**
   * Return the data from the API in xml format.
   */
  public function getWeatherInformation() {
    try {

      $config = $this->configFactory->get('open_weather_map.settings');
      $response = $this->httpClient->request('GET', 'https://api.openweathermap.org/data/2.5/weather',
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
