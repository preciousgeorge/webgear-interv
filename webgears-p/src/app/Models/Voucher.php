<?php
// src/models/Voucher

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
* Voucher class
*/
class Voucher extends Model 
{

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
     const UPDATED_AT = 'date_found';


    /**
    * @var string $table
    *
    * Initialize vouchers table
    */
    protected $table = 'vouchers';

    /**
    * @var array $guarded
    *
    * gaurded field
    */
    protected $guarded = ['id'];

    /**
    * @var $errors
    *
    * Initialize errors var
    */
    private $errors;

    /**
    * @var array $rules
    *
    * Set up Validation rules in array $rules 
    */
    private $rules = [];

    /**
    * @var array $messages 
    *
    * Set up error messages
    */
    private $messages = [];

    /**
    * validate method
    *
    * @param $params
    * @return boolean
    */
    public function validate($params)
    {
        $validator = Validator::make( $params, $this->rules, $this->messages );
        if( $validator->fails() )
        {
          $this->errors = $validator->errors()->all();
          return false;
        }
        return true;
    }    

    /**
    *  errors method
    *
    *  @return $this->errors
    */
    public function errors()
    {
      return $this->errors;
    }
}