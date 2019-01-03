<?php

namespace App\Http\Controllers\Api;

use App\Models\Banner;

class BannerController extends BaseController
{
    public function index()
    {
        return $this->success(Banner::getBanners());
    }
}
