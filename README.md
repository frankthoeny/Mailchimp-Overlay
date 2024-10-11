## INTRODUCTION

The Mailchimp Overlay module enhances email marketing by allowing businesses to create visually appealing popup overlays on their websites, prompting first-time visitors to sign up for newsletters and offers. It integrates seamlessly with Mailchimp, enabling effective lead capture and mailing list growth, while offering customization options to ensure alignment with the website’s design. Its user-friendly interface simplifies the setup and management of signup forms, ultimately boosting engagement and conversion rates.

Below is a list of features that this module provides:
- Seamless integration with the Mailchimp module and Mailchimp Signup sub-module.
- Integrates the Mailchimp Signup form page defined in the configuration.
- A cookie triggers an overlay popup and displays overlay popup once during visit.
- On/Off switch to pause showing the overlay popup.
- A Title for the overlay popup.
- A Disclaimer field at the bottom of the overlay popup. 

## REQUIREMENTS

To use the Mailchimp Overlay module effectively, ensure that both the Mailchimp module - https://www.drupal.org/project/mailchimp and the Mailchimp Signup sub-module are installed and activated on your Drupal platform, as these dependencies are essential for the overlay functionality to work properly.

## INSTALLATION

Install as you would normally install a contributed Drupal module.
See: https://www.drupal.org/node/895232 for further information.

## CONFIGURATION

The Mailchimp Overlay implements a cookie that triggers an overlay popup only once per visit, using JavaScript to check if the cookie exists upon loading the page. If the cookie is not found, display the overlay popup and set a cookie until the browser session ends. This way, the popup will only show once during each visit, preventing it from reappearing if the user refreshes the page or navigates within the site.

## MAINTAINERS

Current maintainers for Drupal 10:

- Frank Thoeny - [frankthoeny](https://www.drupal.org/u/frankthoeny)

