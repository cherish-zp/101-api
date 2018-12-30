<?php

namespace App\Http\Controllers\Api;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BannerController extends BaseController
{
    public function index()
    {
        return $this->success(Banner::getBanners());
    }
}
