<?php
// src/AppBundle/DataAccessLayer/RefundRequestDataMapper.php
namespace AppBundle\DataAccessLayer;

use AppBundle\DependencyInjection\DBController;

class RefundRequestDataMapper
{
    /** @var  DBController */
    protected $db;

    /* Main Constructor: */
    public function __construct($db)
    {
        $this->db = $db;
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
    public function validateData()
    {

    }
}