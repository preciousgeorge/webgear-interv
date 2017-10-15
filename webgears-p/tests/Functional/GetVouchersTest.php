<?php

namespace Tests\Functional;

class GetVouchersTest extends \Tests\BaseTestCase
{
   
 
     /**
     * Test that voucher_index page is html and is accessable 
     */
     public function testVoucherIndexPageIsHtml()
     {
         $response = $this->runApp('GET', '/voucher');
 
         $this->assertEquals(200, $response->getStatusCode());
         $this->assertValidHtml($response->getBody());
     }

     /**
     * Test that the index route won't accept a post request
     */
     public function testPostVoucherIndexPageIsNotAllowed()
     {
         $response = $this->runApp('POST', '/voucher', ['code']);
 
         $this->assertEquals(405, $response->getStatusCode());
         $this->assertContains('Method not allowed', (string)$response->getBody());
     }
    

    /**
     * Test that the /api/voucher route returns a rendered response containing the text 'code' 
     * and it's not an array
     */
    public function testVoucherAPIEndPointWorks()
    {
        $response = $this->runApp('GET', '/api/vouchers');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('code', (string)$response->getBody());
        $this->assertFalse(is_array($response->getBody()));
    }

    /**
     * Test that the /api/submit-voucher route works and returns vouchers
     *
     */
    public function testSubmitVoucher()
    {
        $response = $this->runApp('PUT', '/api/submit-voucher', ['id'=>1]);

        $this->assertNotEquals(204, $response->getStatusCode());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('code', (string)$response->getBody());
        
    }

    
}