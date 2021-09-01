<?php

namespace Drupal\open_weather_map\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * @file
 * Administration page callbacks for the Weather configurations.
 */

/**
 * Class to store weather configuration.
 */
class WeatherConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'open_weather_map_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return [
      'open_weather_map.settings',
    ];

  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('open_weather_map.settings');

    $form['endpoint'] = [
      '#title' => $this->t('API Endpoint'),
      '#type' => 'textfield',
      '#default_value' => $config->get('endpoint'),
    ];

    $form['key'] = [
      '#title' => $this->t('App ID'),
      '#type' => 'textfield',
      '#default_value' => $config->get('key'),
    ];

    $form['cityname'] = [
      '#title' => $this->t('City Name'),
      '#type' => 'textfield',
      '#default_value' => $config->get('cityname'),
    ];

    $form['country_code'] = [
      '#title' => $this->t('Country Code'),
      '#type' => 'textfield',
      '#default_value' => $config->get('country_code'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->config('open_weather_map.settings')
      ->set('key', $form_state->getValue('key'))
      ->set('cityname', $form_state->getValue('cityname'))
      ->save();

    // Set values in variables.
    parent::submitForm($form, $form_state);
  }

}
