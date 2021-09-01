<?php

namespace Drupal\open_weather_map\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\open_weather_map\WeatherService;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Builds an static callback page.
 */
class WeatherController extends ControllerBase {

  /**
   * The module handler.
   *
   * @var \Drupal\open_weather_map\WeatherService
   */
  protected $weatherservice;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a Drupal\Component\Plugin\PluginBase object.
   *
   * @param \Drupal\open_weather_map\WeatherService $weatherservice
   *   The information from the Weather service for this block.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config Factory.
   */
  public function __construct(WeatherService $weatherservice, ConfigFactoryInterface $config_factory) {
    $this->weatherservice = $weatherservice;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('open_weather_map.weather_service'),
      $container->get('config.factory')
    );
  }

  /**
   * Call back for route static_content.
   */
  public function build() {
    $output = json_decode($this->weatherservice->getWeatherInformation(), TRUE);
    $attributes = [
      'humidity' => $output['main']['humidity'],
      'temp_max' => $output['main']['temp_max'] - 273.15,
      'temp_min' => $output['main']['temp_min'] - 273.15 ,
      'pressure' => $output['main']['pressure'],
      'wind_speed' => $output['wind']['speed'],
    ];

    $build[] = [
      '#theme' => 'open_weather_map',
      '#open_weather_map_detail' => $attributes,
    ];
    return $build;
  }

}
