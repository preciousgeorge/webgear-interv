<?php

// src/app/Actions/GetVouchers


namespace App\Actions;

use App\Models\Voucher;
use App\Models\Partner;
use App\Utils\LoggerUtils;



class GetVouchers 
{
    protected $client;

    /**
    * class constructor
    *
    * Initialize class with needed constructs
    * setup GuzzleHttp\Client
    */
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client(['timeout' => 12.0]);
    }

    /**
    * fetchVouchers
    * 
    * Update Table and fetch Vouchers
    * @return array
    */
    public function fetchVouchers()
    {
       $this->updateVouchers();
       return $this->fetchDbVouchers();
        
    }
    

    /**
    * Get all partner api urls and names
    *
    */
    public function fetchPartners()
    {     
          return Partner::all(['url', 'partner_name']);
    }

    /**
    * Method to get Vouchers from Multiple API's.
    * The Api Url must be added to the partners table before it can be called here
    * Results return from each Api is set to the array that holds all results but
    * each result is set as value to the Partner name as key.
    *
    *
    * @return array
    */
    public function getVouchersFromApi()
    {
        $requests =  [];
        $vouchers = [];
        $errors = [];

        // iterate through partners and get each partners url to do an Async call with
        foreach($this->fetchPartners() as $partner){
                
            $requests[$partner->partner_name] = $this->client->getAsync($partner->url);       
        }

        // Settle the promise from Asyc requests and wait the results
        $results = \GuzzleHttp\Promise\settle($requests)->wait();


        foreach ($results as $partner_name => $result) {
           if ($result['state'] === 'fulfilled') {
                $response = $result['value'];
                if ($response->getStatusCode() == 200) {
                    array_push($vouchers, json_decode($response->getBody(), true));
                } else{
                    array_push($errors, 'WARNING: returned empty for Partner: '.$shop);
                    LoggerUtils::err($errors);
                }
            } else if ($result['state'] === 'rejected') { 
                array_push($errors, 'ERR: Failed to fetch vouchers for Partner: '.$shop. 'REASON: ' . $result['reason']);
                LoggerUtils::err($errors);
            } else {
                array_push($errors, 'ERR: unknown exception - Failed to fetch vouchers for Partner: '.$shop);
                LoggerUtils::err($errors);
            }
        }
        return $vouchers;

    }
   

    /**
    * persistVoucherToDatabase method
    *
    * update or if the values are new create voucher
    * @return object
    */
    public function persistVouchersToDatabase($data)
    {
       $voucher = Voucher::updateOrCreate(
           [
             'code' => $data['code']
           ],
           ['shop' => $data['program_name'], 
           'code' => $data['code'], 
           'value'=> $data['discount'],
           'url' =>  $data['destinationUrl'],
           'valid_from_date' => $this->getDate($data['startDate']),
           'expiry_date' => $this->getDate($data['expiryDate']),
           'submitted' => 0]
       );
       return $voucher;
    }

    /**
    * Get Data from Api and persist to database
    *
    */
    public function updateVouchers()
    {
         $vouchers = $this->getVouchersFromApi();
         foreach($vouchers as $voucher){
             $decoded = json_decode($voucher, true);
             foreach($decoded as $data){
                $this->persistVouchersToDatabase($data);
             }
         }
         
    }

    /**
    * Submit Voucher
    *
    * update voucher as submitted
    * @param int $id
    * @return array
    */
    public function submitVoucher($id)
    {
        $voucher = Voucher::where('id', $id)->update(['submitted' => 1]);
        if($voucher){
            return $this->fetchDbVouchers();
        }
        return ['error' => 'No Voucher to fetch'];
    }

    /**
    * fetchDbVouchers
    * Get Vouchers from database
    *
    * @return array
    */
    public function fetchDbVouchers(){
        $result = Voucher::where('submitted', '=', '0')
                                ->orderBy('date_found', 'desc')
                                ->get()
                                ->toArray();
        return $result;
    }

    /**
    * getDate
    *
    * Get datetime value with argument supplied
    *
    * @param string $date
    * @return string
    */
    public function getDate($date = ''){
            $date = new \DateTime($date);
            return $date->format('Y-m-d H:i:s');
    }


}