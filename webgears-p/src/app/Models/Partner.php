<?php
// src/models/Partners

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
* Partner class
*/

class Partner extends Model
{
  /**
    * @var $table
    *
    * Initialize partners table
    */
    protected $table = 'partners';


    /**
    * @var $errors
    *
    * Initialize errors var
    */
    private $errors;
    
        /**
        * @var $rules
        *
        * Set up Validation rules in array $rules 
        */
        private $rules = [];
    
        /**
        * @var $messages 
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