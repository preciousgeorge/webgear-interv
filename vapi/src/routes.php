<?php
require __DIR__.'/../vendor/autoload.php';

use Slim\Http\Request;
use Slim\Http\Response;
use App\Voucher;

// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->response->withJson(['Welcome' => 'Your are at \'/\'']);
});

/** with reset option for reset the api to call input1.json again **/
$app->get('/vouchers[/{reset}]', function(Request $request, Response $response, array $args){
    //log
    $this->logger->info("voucher endpoint '/vouchers' route" );
    $voucherList = new Voucher();
    $reset = $request->getQueryParams();
    //Render Vouchers 
    return $this->response->withJson($voucherList->getVouchers($reset));
});
