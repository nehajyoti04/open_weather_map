<?php

namespace Drupal\open_weather_map\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * @file
 * Administration page callbacks for the Weather configurations.
 */

class WeatherConfigForm extends ConfigFormBase {
  public function getFormId() {
    return 'open_weather_map_settings';
  }
  public function getEditableConfigNames() {
    return [
      'open_weather_map.settings',
    ];

  }
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('open_weather_map.settings');

    $form['appid'] = [
      '#title' => $this->t('App ID'),
      '#type' => 'textfield',
      '#default_value' => $config->get('appid'),
    ];

    $form['cityname'] = [
      '#title' => $this->t('City Name'),
      '#type' => 'textfield',
      '#default_value' => $config->get('cityname'),
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->config('open_weather_map.settings')
      ->set('appid', $form_state->getValue('appid'))
      ->set('cityname', $form_state->getValue('cityname'))
      ->save();

    // Set values in variables.
    parent::submitForm($form, $form_state);
  }
}