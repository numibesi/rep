<?php

/**
 * @file
 * Contains the settings for admninistering the rep Module
 */

 namespace Drupal\rep\Form;

 use Drupal\Core\Form\ConfigFormBase;
 use Drupal\Core\Form\FormStateInterface;
 use Drupal\Core\Url;

 class REPSettingsForm extends ConfigFormBase {

     /**
     * Settings Variable.
     */
    Const CONFIGNAME = "rep.settings";

     /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return "rep_form_settings";
    }

    /**
     * {@inheritdoc}
     */

    protected function getEditableConfigNames() {
        return [
            static::CONFIGNAME,
        ];
    }

     /**
     * {@inheritdoc}
     */

     public function buildForm(array $form, FormStateInterface $form_state){
        $config = $this->config(static::CONFIGNAME);

        $home = "";
        if ($config->get("rep_home")!= NULL) {
            $home = $config->get("rep_home");
        }

        $form['namespace_submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Manage NameSpaces'),
            '#name' => 'namespace',
        ];
      
        $form['preferred_names_submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Preferred Names'),
            '#name' => 'preferred',
        ];
      
        $form['rep_home'] = [
            '#type' => 'checkbox',
            '#title' => 'Do you want rep to be the home (first page) of the Drupal?',
            '#default_value' => $home,
        ];

        $shortName = "";
        if ($config->get("site_label")!= NULL) {
            $shortName = $config->get("site_label");
        }
        $form['site_label'] = [
            '#type' => 'textfield',
            '#title' => 'Repository Short Name (ex. "ChildFIRST")',
            '#default_value' => $shortName,
        ];

        $fullName = "";
        if ($config->get("site_name")!= NULL) {
            $fullName = $config->get("site_name");
        }
        $form['site_name'] = [
            '#type' => 'textfield',
            '#title' => 'Repository Full Name (ex. "ChildFIRST: Focus on Innovation")',
            '#default_value' => $fullName,
            '#description' => 'This value is the website name.',
        ];

        $domainUrl = "";
        if ($config->get("repository_domain_url")!= NULL) {
            $domainUrl = $config->get("repository_domain_url");
        }
        $form['repository_domain_url'] = [
            '#type' => 'textfield',
            '#title' => 'Repository Domain URL (ex: http://childfirst.ucla.edu, http://tw.rpi.edu, etc.)',
            '#required' => TRUE,
            '#default_value' => $domainUrl,
            '#description' => 'This value is used to compose the URL of rep elements created within this repository',
        ];

        $domainNamespace = "";
        if ($config->get("repository_domain_namespace")!= NULL) {
            $domainNamespace = $config->get("repository_domain_namespace");
        }
        $form['repository_domain_namespace'] = [
            '#type' => 'textfield',
            '#title' => 'Namespace for Domain URL (ex: ufmg, ucla, rpi, etc.)',
            '#required' => TRUE,
            '#default_value' => $domainNamespace,
        ];

        $description = "";
        if ($config->get("repository_description")!= NULL) {
            $description = $config->get("repository_description");
        }
        $form['repository_description'] = [
            '#type' => 'textarea',
            '#title' => ' description for the repository that appears in the rep APIs GUI',
            '#required' => TRUE,
            '#default_value' => $description,
        ];

        $form['api_url'] = [
            '#type' => 'textfield',
            '#title' => 'rep API Base URL',
            '#default_value' => $config->get("api_url"),
        ];

        //$keys = \Drupal::service('key.repository')->getKeys();
        //var_dump($keys);

        //$key_value = '';
        //$key_entity = \Drupal::service('key.repository')->getKey('jwt');
        //if ($key_entity != NULL && $key_entity->getKeyValue() != NULL) {
        //    $key_value = $key_entity->getKeyValue();
        //}

        $form['jwt_secret'] = [
            '#type' => 'key_select',
            '#title' => 'JWT Secret',
            '#key_filters' => ['type' => 'authentication'],
            '#default_value' => $config->get("jwt_secret"),
        ];

        $form['filler_1'] = [
            '#type' => 'item',
            '#title' => $this->t('<br>'),
        ];
      
        $form['filler_2'] = [
            '#type' => 'item',
            '#title' => $this->t('<br>'),
        ];
      
        return Parent::buildForm($form, $form_state);


     }

    public function validateForm(array &$form, FormStateInterface $form_state) {
        if(strlen($form_state->getValue('site_label')) < 1) {
            $form_state->setErrorByName('site_label', $this->t("Please inform repository's short name."));
        }
        if(strlen($form_state->getValue('site_name')) < 1) {
            $form_state->setErrorByName('site_name', $this->t("Please inform repository's full name."));
        }
        if(strlen($form_state->getValue('repository_domain_url')) < 1) {
            $form_state->setErrorByName('repository_domain_url', $this->t("Please inform repository's Domain URL."));
        } else {
            if ((strtolower(substr($form_state->getValue('repository_domain_url'), 0, 7)) !== "http://") &&  
                (strtolower(substr($form_state->getValue('repository_domain_url'), 0, 8)) !== "https://")) {
                $form_state->setErrorByName('repository_domain_url', $this->t("Domain URL must start with 'http://' or 'https://'."));
            } 
        }
        if(strlen($form_state->getValue('repository_domain_namespace')) < 1) {
            $form_state->setErrorByName('repository_domain_namespace', $this->t("Please inform repository's Domain Namespace."));
        } else if (strlen($form_state->getValue('repository_domain_namespace')) > 10) {
            $form_state->setErrorByName('repository_domain_namespace', $this->t("Domain Namespace cannot have more than 10 characters"));
        } else if (!preg_match('/^[a-zA-Z0-9\-]+$/', $form_state->getValue('repository_domain_namespace'))) {
            $form_state->setErrorByName('repository_domain_namespace', $this->t("Domain Namespace can only have letters, numbers and '-'."));
        }
   }
     
    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $triggering_element = $form_state->getTriggeringElement();
        $button_name = $triggering_element['#name'];
    
        if ($button_name === 'namespace') {
          $form_state->setRedirectUrl(Url::fromRoute('rep.admin_namespace_settings_custom'));
          return;
        } 
        
        if ($button_name === 'preferred') {
          $form_state->setRedirectUrl(Url::fromRoute('rep.admin_preferred_names_custom'));
          return;
        } 
          
          $config = $this->config(static::CONFIGNAME);
        
        //save confs
        $config->set("rep_home", $form_state->getValue('rep_home'));
        $config->set("site_label", trim($form_state->getValue('site_label')));
        $config->set("site_name", trim($form_state->getValue('site_name')));
        $config->set("repository_domain_url", trim($form_state->getValue('repository_domain_url')));
        $config->set("repository_domain_namespace", trim($form_state->getValue('repository_domain_namespace')));
        $config->set("repository_description", trim($form_state->getValue('repository_description')));
        $config->set("api_url", $form_state->getValue('api_url'));
        $config->set("jwt_secret", $form_state->getValue('jwt_secret'));
        $config->save();
        
        //site name
        $configdrupal = \Drupal::service('config.factory')->getEditable('system.site');
        $configdrupal->set('name', $form_state->getValue('site_name')); 
        $configdrupal->save();

        //update Repository configuration
        $api = \Drupal::service('rep.api_connector');

        //label 
        $api->repoUpdateLabel(
            $form_state->getValue('api_url'),
            $form_state->getValue('site_label'));

        //title
        $api->repoUpdateTitle(
            $form_state->getValue('api_url'),
            $form_state->getValue('site_name'));

        //description
        $api->repoUpdateDescription(
            $form_state->getValue('api_url'),
            $form_state->getValue('repository_description'));
        
        //namespace
        $api->repoUpdateNamespace(
            $form_state->getValue('api_url'),
            $form_state->getValue('repository_domain_namespace'),
            $form_state->getValue('repository_domain_url'));
      
        // Save the filename in configuration.
        //$this->config('rep.settings')
        //  ->set('svg_file', $file_id)
        //  ->save();
      
        $messenger = \Drupal::service('messenger');
        $messenger->addMessage($this->t('Your new rep configuration has been saved'));

        $url = Url::fromRoute('rep.repo_info');
        $form_state->setRedirectUrl($url);
        
    }

 }