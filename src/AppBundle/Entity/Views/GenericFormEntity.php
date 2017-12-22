<?php
// src/AppBundle/Entity/Views/GenericFormEntity.php
/**
 * Description: the generic form entity is responsible for instantiating a data model, handling the requests from the controller and passing the user input to the model.
 * These are user data driven web forms that also implement a FormBuilder to display the data model's
 */
namespace AppBundle\Entity\Views;
use Symfony\Component\Form\FormFactory;

class GenericFormEntity
{
    // Variables:
    private $dataModel;
    private $formTemplate;

    // System Objects:
    protected $db_controller;
    protected $emailer;
    protected $form_factory;
    protected $sessions;

    public function __construct($modelName, $formTemplateName, $previousData, $db_controller, $emailer, FormFactory $form_factory, $validatorService, $sessions)
    {
        $this->db_controller = $db_controller;
        $this->emailer = $emailer;
        $this->form_factory = $form_factory;
        $this->sessions = $sessions;

        // Set the Form DataModel requested and the form's template:
        $this->dataModel = new $modelName($db_controller, $validatorService);
        if ($previousData != '')
            $this->db_controller->setObjectData($previousData, $this->dataModel);
        $this->setFormTemplate($formTemplateName);
    }

    /** Processing and Control Functions: **/
    public function formPost($postProcess, $submitName)
    {
        foreach ($postProcess as $process)
        {
            switch ($process)
            {
                case "validateData":
                    $response = $this->getDataModel()->getDataMapper()->$process($submitName);
                    if ($response != 'user-input-valid') {
                        $this->sessions->set('errors', $response);
                        // Reset user input into the form on reload:
                        $dataArray = $this->db_controller->build_class_data_array($this->getDataModel());
                        $this->sessions->set('previousData', $dataArray);
                        return 'form-error';
                    }
                    break;
                case "emailData":
                    $response = $this->getDataModel()->getDataMapper()->$process($this->emailer);
                    break;
            }
        }

        return 'submission-complete';
    }


    /** Getters and Setters: **/
    public function getDataModel()
    {
        return $this->dataModel;
    }

    public function setFormTemplate($formTemplateName)
    {
        $this->formTemplate = $this->form_factory->create($formTemplateName, $this->getDataModel(), array(
        ));
    }
    public function getFormTemplate()
    {
        return $this->formTemplate;
    }
    public function getFormTemplateView()
    {
        return $this->formTemplate->createView();
    }
}