<?php

// app/Voucher

namespace App;

use App\ManageData;

/**
* Voucher Class
*/

class Voucher implements Interfaces\VouchersAPIInterface
{
    private $data;


    /**
    * class constructor
    *
    * setup $data object with ManageData()
    */
    public function __construct()
    {
        $this->data = new ManageData();
    }

    
    /**
    * getVouchers class
    *
    * @param $reset
    * @return Json String
    */
    public function getVouchers($reset = '')
    {
        return $this->data->readJson($reset);
        
    }

    
}

