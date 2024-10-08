<?php

/**
 * @file
 * Contains Drupal\mailchimp_overlay\Controller\MailchimpOverlayController.
 */

declare (strict_types = 1);

namespace Drupal\mailchimp_overlay\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\mailchimp_signup\Form\MailchimpSignupPageForm;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for Mailchimp Overlay routes.
 */
final class MailchimpOverlayController extends ControllerBase {

 /**
  * The messenger service.
  *
  * @var \Drupal\Core\Messenger\MessengerInterface
  */
 protected $messenger;

 /**
  * The form builder service.
  *
  * @var \Drupal\Core\Form\FormBuilderInterface
  */
 protected $formBuilder;

 /**
  * MailchimpOverlayController constructor.
  *
  * @param \Drupal\Core\Messenger\MessengerInterface $messenger
  *   The messenger service.
  * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
  *   The form builder service.
  */
 public function __construct(MessengerInterface $messenger, FormBuilderInterface $form_builder) {
  $this->messenger   = $messenger;
  $this->formBuilder = $form_builder;
 }

 /**
  * {@inheritdoc}
  */
 public static function create(ContainerInterface $container) {
  return new static(
   $container->get('messenger'),
   $container->get('form_builder')
  );
 }

 /**
  * The __invoke magic method
  *
  * Invoke the mailchimp overlay theme.
  *
  * @return mixed
  */
 public function __invoke() {
  $config      = \Drupal::config('mailchimp_overlay.settings');
  $signup_form = $this->getMailchimpSignupForm();
  $layout  = $config->get('layout'); 
  $themeLayout = match($layout){
    'default' => $this->modalLayout($config, $signup_form, $layout),
    'modal-top-image' => $this->modalLayout($config, $signup_form, $layout),
    'modal-left-image' => $this->modalLayout($config, $signup_form, $layout),
    'modal-right-image' => $this->modalLayout($config, $signup_form, $layout),
    'fixed-bottom-bar' => $this->modalLayout($config, $signup_form, $layout),
  };
  return $themeLayout;
 }

 /**
  * The mailchimp overlay dialog theme.
  *
  * Build Theme Layout
  * - Default
  * - Modal Top Image
  * - Modal Left Image
  * - Modal Right Image
  * - Fixed Bottom Bar
  * 
  *
  * @param object $config
  *  Retrieves the mailchimp_overlay.settings objects.
  *
  * @param object $signup_form
  *  The sign up form.
  *
  * @param object $layout
  *  The layout type.
  *
  * @return array $build
  *   Renderable array of page content.
  *
  */
 public function modalLayout($config, $signup_form, $layout) {
  $build = [
   '#theme'      => 'mailchimp_overlay_'.str_replace('-', '_', $layout),
   '#title'      => $config->get('title'),
   '#signupform' => $this->formBuilder->getForm($signup_form),
   '#disclaimer' => $config->get('disclaimer'),
  //  '#backgroundImage' => imageFilePath($config->get('image_file')),
  //  '#backgroundColor' => $config->get('background_color_text'),
  //  '#textColor'       => $config->get('text_color__text'),
  ];

  $renderer = \Drupal::service('renderer');
  $renderer->addCacheableDependency($build, $config);
  return $build;
 }

 /**
  * Thank you message.
  *
  * @return array $build
  *   Renderable array of page content.
  *
  */
 public function thankyou() {
  $build = [
   '#theme' => 'mailchimp_overlay_thankyou',
  ];
  return $build;
 }

/**
 * Image File Path
 *
 * @param object $file_id
 *  The file object
 *
 * @return string $path
 *  The file name
 *
 */
 public function imageFilePath($file_id) {
  $image_file = File::load($file_id[0]);
  $path       = $image_file->createFileUrl();
  return $path;
 }

 /**
  * Get Mailchimp SignupForm
  *
  * Retrieves then returns the signup form to the layout
  *
  * @param object $config
  *  Retrieves the mailchimp_overlay.settings objects.
  * @return object
  * @throws conditon
  *
  */
 public function getMailchimpSignupForm() {
  $signup  = get_mailchimp_signup_id();
  $form_id = 'mailchimp_signup_subscribe_page_' . $signup->id . '_form';
  $form    = new MailchimpSignupPageForm($this->messenger);
  $form->setFormID($form_id);
  $form->setSignup($signup);
  return $form;
 }

}
