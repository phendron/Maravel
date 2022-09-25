<?php

use App\User;

require __DIR__.'/../../../vendor/autoload.php';
$app=require_once __DIR__.'/../../../bootstrap/app.php';
$kernel=$app->make(Illuminate\Contracts\Http\Kernel::class);
$response=$kernel->handle($request = Illuminate\Http\Request::capture());

include(__DIR__.'/../../../routes/web.php');

echo json_encode(Route::getRoutes());

?>