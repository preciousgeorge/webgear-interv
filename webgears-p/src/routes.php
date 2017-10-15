<?php

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Voucher;
use App\Actions\GetVouchers;

// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->view->render($response, 'index.phtml', $args);
});

/* Vouchers view page */
$app->get( '/voucher', function (Request $request, Response $response, array $args) {
    $this->view->render($response, 'voucher.twig');
})->setName('voucher_index');

/* vouchers api endpoint */
$app->get('/api/vouchers', function(Request $request, Response $response, array $args){
     $vouchers = new GetVouchers();
     return $this->response->withJson($vouchers->fetchVouchers());
});

/* submit voucher */
$app->put('/api/submit-voucher', function(Request $request, Response $response, array $args){
      $data = $request->getParsedBody();
      $vouchers = (new GetVouchers())->submitVoucher($data['id']);
      return $this->response->withJson($vouchers);
});
  
