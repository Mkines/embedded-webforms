<?php
// src/AppBundle/DataAccessLayer/RefundRequestDataMapper.php
namespace AppBundle\DataAccessLayer;

use AppBundle\AppBundle;
use AppBundle\DependencyInjection\DBController;
use AppBundle\DependencyInjection\Emailer;

class RefundRequestDataMapper
{
    private $dataParentModel;
    /** @var  DBController */
    protected $db;
    protected $validatorService;

    /* Main Constructor: */
    public function __construct($dataParentModel, $db, $validatorService)
    {
        $this->dataParentModel = $dataParentModel;
        $this->db = $db;
        $this->validatorService = $validatorService;
    }

    // SELECT Functions:
    public function selectFinanceRefundRequestAddressOptions()
    {
        $sql = "SELECT rr_address_id, rr_address_text FROM finance_refund_request_address_options";
        $rawData =  $this->db->select_db_row(array(''), $sql);

        $refundRequestAddressOptions = array();
        foreach ($rawData as $i=>$row)
            $refundRequestAddressOptions[$row['rr_address_id']] = $row['rr_address_text'];

        return $refundRequestAddressOptions;
    }

    // Data Validation Functions:
    public function validateData($submitName)
    {
        /**
         * Description: used to validate the userInputted form data from a specific source.
         **/
        $errors = $this->validatorService->validate($this->dataParentModel);
        if ($submitName == 'submitRefundRequest')
        {
            if (count($errors) > 0) {
                $errorsArray = array();
                foreach ($errors as $error)
                    array_push($errorsArray, $error->getMessage());

                return $errorsArray;
            }

            return "user-input-valid";
        }
    }

    // Emailer:
    public function emailData($emailService)
    {
        $emailService->sendRefundRequestEmailToAdmin($this->dataParentModel);
    }
}