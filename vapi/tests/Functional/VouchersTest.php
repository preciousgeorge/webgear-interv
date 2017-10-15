<?php

namespace Tests\Functional;


class VouchersTest extends \Tests\BaseTestCase
{
     /**
     * Test that the index route returns a rendered response containing the text 'SlimFramework' but not a greeting
     */
     public function testHomePageWorks()
     {
         $response = $this->runApp('GET', '/');
 
         $this->assertEquals(200, $response->getStatusCode());
         $this->assertContains('Welcome', (string)$response->getBody());
         $this->assertNotContains('Hello', (string)$response->getBody());
     }

     /**
     * Test that /vouchers endpoint returns success status
     * and that it returns expected data
     */
     public function testVouchersReturned()
     {
        $response = $this->runApp('GET', '/vouchers');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('RECARO5', (string)$response->getBody());
     }


     /**
     * Test that vouchers endpoint when visited twice brings different results 
     */
     public function testVouchersAreUpdated()
     {
        $res1 = $this->runApp('GET', '/vouchers/1');
        $res2 = $this->runApp('GET', '/vouchers/1');

        $this->assertTrue(strcmp((string)$res1->getBody(), (string)$res2->getBody()) === 0);
     }
 
}