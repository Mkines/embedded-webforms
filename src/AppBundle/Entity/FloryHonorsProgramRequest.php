<?php
// src/AppBundle/Entity/FloryHonorsProgramRequest.php
/**
 * Description: this form is embedded on the following page of the main website: https://www.bridgewater.edu/events-news/speakers
 * It just takes user input requesting a faculty member to be a guest speaker somewhere from the attached pdf and sends the request to Mary Kay Heatwole via email.
 */
namespace AppBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class FloryHonorsProgramRequest
{
    protected $bc_helper;

    private $formFields = array();
    private $valuesMap;

    private $fullname;
    private $highSchool;
    private $essayAttached;

    public function __construct($bc_helper)
    {
        $this->bc_helper = $bc_helper;
    }


    private function setValuesMap($formData, $formFieldArray)
    {
        $valuesMap = array();
        foreach ((array)$formFieldArray as $formField) {
            // Convert the formField into a tableField for the database
            $tableField = $this->bc_helper->convert_camel_to_db_key($formField);
            //echo $tableField."</br>";
            $valuesMap[$tableField] = $formData[$formField]->getData();
        }

        $this->valuesMap = $valuesMap;
    }

    /** Getters and Setters **/
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
    }
    public function getFullname()
    {
        return $this->fullname;
    }

    public function setHighSchool($highSchool)
    {
        $this->highSchool = $highSchool;
    }
    public function getHighSchool()
    {
        return $this->highSchool;
    }

    public function setEssayAttached($essayAttached)
    {
        $this->essayAttached = $essayAttached;
    }
    public function getEssayAttached()
    {
        return $this->essayAttached;
    }
}