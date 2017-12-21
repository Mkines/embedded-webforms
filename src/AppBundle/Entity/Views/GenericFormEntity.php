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
    protected $db_controller;
    protected $emailer;
    protected $formFactory;

    public function __construct($modelName, $formTemplateName, $db_controller, $emailer, FormFactory $formFactory)
    {
        $this->db_controller = $db_controller;
        $this->emailer = $emailer;
        $this->formFactory = $formFactory;

        // Set the Form DataModel requested and the form's template:
        $this->dataModel = new $modelName($db_controller);
        $this->setFormTemplate($formTemplateName);
    }

    /** Processing and Control Functions: **/
    public function formPost($postProcess)
    {
        foreach ($postProcess as $process)
        {

        }
    }


    /** Getters and Setters: **/
    public function getDataModel()
    {
        return $this->dataModel;
    }

    public function setFormTemplate($formTemplateName)
    {
        $this->formTemplate = $this->formFactory->create($formTemplateName, $this->getDataModel(), array(
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