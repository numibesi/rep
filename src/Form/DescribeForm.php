<?php

/**
 * @file
 * Contains the settings for admninistering the REP Module
 */

 namespace Drupal\rep\Form;

 use Drupal\Core\Form\FormBase;
 use Drupal\Core\Form\FormStateInterface;
 use Drupal\Core\Url;
 use Drupal\rep\ListUsage;
 use Drupal\rep\Utils;
 use Drupal\rep\Entity\Tables;
 use Drupal\rep\Entity\GenericObject;
 use Drupal\rep\Vocabulary\REPGUI;
 use Drupal\rep\Vocabulary\VSTOI;

 class DescribeForm extends FormBase {

    protected $element;

    protected $source;

    protected $codebook;
  
    public function getElement() {
      return $this->element;
    }
  
    public function setElement($obj) {
      return $this->element = $obj; 
    }
  
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return "describe_form";
    }

    /**
     * {@inheritdoc}
     */

     public function buildForm(array $form, FormStateInterface $form_state, $elementuri=NULL){

        // RETRIEVE REQUESTED ELEMENT
        $uri_decode=base64_decode($elementuri);
        $full_uri = Utils::plainUri($uri_decode);
        $api = \Drupal::service('rep.api_connector');
        $this->setElement($api->parseObjectResponse($api->getUri($full_uri),'getUri'));

        $objectProperties = GenericObject::inspectObject($this->getElement());

        //if ($objectProperties !== null) {
        //    dpm($objectProperties);
        //} else {
        //    dpm("The provided variable is not an object.");
        //}
        

        // RETRIEVE CONFIGURATION FROM CURRENT IP
        if ($this->getElement() != NULL) {
            $hascoType = $this->getElement()->hascoTypeUri;
            if ($hascoType == VSTOI::INSTRUMENT) {
                $shortName = $this->getElement()->hasShortName;
            }
            if ($hascoType == VSTOI::INSTRUMENT || $hascoType == VSTOI::CODEBOOK) {
                $name = $this->getElement()->label;
            }
            $message = "";
        } else {
            $shortName = "";
            $name = "";
            $message = "<b>FAILED TO RETRIEVE ELEMENT FROM PROVIDED URI</b>";
        }

        // Instantiate tables 
        $tables = new Tables;

        $form['header1'] = [
            '#type' => 'item',
            '#title' => '<h3>Data Properties</h3>',
        ];

        foreach ($objectProperties['literals'] as $propertyName => $propertyValue) {
            // Add a textfield element for each property
            $form[$propertyName] = [
              '#type' => 'textfield',
              '#title' => $this->t($propertyName),
              '#default_value' => $propertyValue, // Set default value
              '#disabled' => TRUE,
            ];
          }
          

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Back'),
            '#name' => 'back',
        ];
        $form['space'] = [
            '#type' => 'item',
            '#value' => $this->t('<br><br>'),
        ];

        return $form;

    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
    }
     
    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $url = Url::fromRoute('rep.about');
        $form_state->setRedirectUrl($url);
    }

 }