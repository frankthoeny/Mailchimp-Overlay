<?php
declare (strict_types = 1);

namespace Drupal\mailchimp_overlay\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides Mailchimp an overlay for page forms.
 */
class MailchimpOverlayAdminSettingsForm extends ConfigFormBase {
 /**
  * The language manager.
  *
  * @var \Drupal\Core\Language\LanguageManagerInterface
  */
 protected $languageManager;

 /**
  * StateService.
  *
  * @var \Drupal\Core\State\StateInterface
  */
 protected StateInterface $stateService;

 /**
  * Creates a new MailchimpAdminSettingsForm instance.
  *
  * @param \Drupal\Core\Language\LanguageManagerInterface $languageManager
  *   The language manager.
  * @param \Drupal\Core\State\StateInterface $stateService
  *   State service.
  */
 public function __construct(LanguageManagerInterface $languageManager, StateInterface $stateService) {
  $this->languageManager = $languageManager;
  $this->stateService    = $stateService;
 }

 /**
  * {@inheritdoc}
  */
 public static function create(ContainerInterface $container) {
  return new static(
   $container->get('language_manager'),
   $container->get('state'),
  );
 }

 /**
  * {@inheritdoc}
  */
 public function getFormId() {
  return 'mailchimp_overlay_admin_settings';
 }

 /**
  * {@inheritdoc}
  */
 protected function getEditableConfigNames() {
  return ['mailchimp_overlay.settings'];
 }

 /**
  * {@inheritdoc}
  */
 public function buildForm(array $form, FormStateInterface $form_state) {

  $config = $this->config('mailchimp_overlay.settings');

  $form['switch'] = [
   '#type'          => 'checkbox',
   '#title'         => $this->t('Switch Overlay On/Off'),
   '#default_value' => $config->get('switch'),
  ];

  $form['title'] = [
   '#type'          => 'textfield',
   '#id'            => 'setting-overlay-title',
   '#title'         => $this->t('Title'),
   '#default_value' => $config->get('title'),
   '#required'      => true,
  ];

  $form['disclaimer'] = [
   '#type'          => 'textarea',
   '#title'         => $this->t('Disclaimer/Note'),
   '#default_value' => $config->get('disclaimer'),
  ];

  // List the key/value pair from mailchimp_signup module.
  $signups       = mailchimp_signup_load_multiple();
  $signupOptions = [];
  /** @var \Drupal\mailchimp_signup\Entity\MailchimpSignup $signup */
  foreach ($signups as $signup) {
   if ((intval($signup->mode) == MAILCHIMP_SIGNUP_PAGE) || (intval($signup->mode) == MAILCHIMP_SIGNUP_BOTH)) {
    $signupOptions[$signup->id] = $signup->title;
   }
  }
  $form['signup_page'] = [
   '#type'          => 'select',
   '#title'         => $this->t('Signup Page'),
   '#options'       => $signupOptions,
   '#default_value' => $config->get('signup_page'),
   '#required'      => true,
  ];

  $layoutOptions = [
    'default' => $this->t('Default'),
    'modal-top-image' => $this->t('Modal Top Image'),
    'modal-left-image' => $this->t('Modal Left Image'),
    'modal-right-image' => $this->t('Modal Right Image'),
    'fixed-bottom-bar' => $this->t('Fixed Bottom Bar'),
   ];

  $form['layout'] = [
   '#type'          => 'radios',
   '#title'         => $this->t('Theme Layout'),
   '#options'       => $layoutOptions,
   '#default_value' => $config->get('layout'),
  ];

  $form['dialog_width'] = [
    '#type'          => 'number',
    '#title'         => $this->t('Dialog Width'),
    '#description'   => $this->t('Enter a number for the width of the overlay.'),
    '#default_value' => $config->get('dialog_width') ? $config->get('dialog_width') : 600,
   ];

  // $form['actions']['submit'] = [
  //  '#type'   => 'submit',
  //  '#submit' => '::customSubmitHandler',
  // ];

  return parent::buildForm($form, $form_state);
 }

 public function customSubmitHandler() {
  // $config = \Drupal::config('mailchimp_overlay.settings');
  // add the cache tag, so that the output gets invalidated when the config is saved
  // \Drupal::service('renderer')->addCacheableDependency($attachments, $config);
 }

 /**
  * {@inheritdoc}
  */
 public function submitForm(array &$form, FormStateInterface $form_state) {

  $config = $this->config('mailchimp_overlay.settings');

  $config
   ->set('switch', $form_state->getValue('switch'))
   ->set('dialog_width', $form_state->getValue('dialog_width'))
   ->set('signup_page', $form_state->getValue('signup_page'))
   ->set('layout', $form_state->getValue('layout'))
   ->set('title', $form_state->getValue('title'))
   ->set('disclaimer', $form_state->getValue('disclaimer'))
   ->save();

  parent::submitForm($form, $form_state);
//   $this->messenger()->addStatus($this->t('The settings has been saved.'));
 }

}
