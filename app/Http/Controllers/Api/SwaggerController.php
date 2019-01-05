<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Swagger\Annotations\Swagger;

class SwaggerController extends Controller
{
    /**
     * @SWG\Swagger(
     *    swagger="2.0",
     *     schemes={"http"},
     *     host="www.101.com",
     *     basePath="/api/",
     *     @SWG\Info(
     *         version="1.0.0",
     *         title="101 Api 文档",
     *         description="生态钱包"
     *     )
     * )
     * @SWG\SecurityScheme(
     *     securityDefinition="Bearer",
     *     type="apiKey",
     *     in="header",
     *     name="Authorization"
     * )
     */
    public function getJSON()
    {
        $swagger = \Swagger\scan(app_path('Http/Controllers/Api'));

        return response()->json($swagger, 200);
    }
}
