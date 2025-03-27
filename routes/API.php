<?php
use Illuminate\Routing\Router;
use WorkSpace\Controller\AuthController;
use WorkSpace\Controller\SystemController;
use WorkSpace\Controller\WorkSpaceController;
use Middleware\Authenticate;

return function (Router $router) {
   $router->aliasMiddleware('auth', Authenticate::class);

   //--------------------------------------------------HOME--------------------------------------------------//

   $router->group(['prefix' => '/'], function (Router $router) {
      $router->get('/', [SystemController::class, 'root']);
      $router->post('/upload-file-s3', [SystemController::class, 'uploadFileToS3']);
      $router->get('/user-inf', [SystemController::class, 'getUserInfoFromRequest'])->middleware('auth');
   });

   //--------------------------------------------------AUTH--------------------------------------------------//

   $router->group(['prefix' => 'auth'], function (Router $router) {
      $router->post('/register', [AuthController::class, 'Register']);
      $router->post('/login', [AuthController::class, 'Login']);
      $router->post('/refresh-token', [AuthController::class, 'RefreshToken']);
   });

   //--------------------------------------------------WORKSPACE--------------------------------------------------//

   $router->group(['prefix' => 'workspace', 'middleware' => 'auth'], function (Router $router) {
      $router->get('/', [WorkSpaceController::class, 'getWorkSpacesByIDUser']);
      $router->post('/', [WorkSpaceController::class, 'createWorkSpace']);
      $router->get('/{IDWorkSpace}', [WorkSpaceController::class,'getWorkSpacesByIDWorkSpace']);
   });
};