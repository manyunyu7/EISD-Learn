<?php

namespace App\Helper;


use App\Models\MyAnalyticEvent;
use App\Models\UserMNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

// $myS3BaseUrl = "https://lms-modernland.s3.ap-southeast-3.amazonaws.com/";
// Sesuaikan dengan endpoint baru + nama bucket
$myS3BaseUrl = "https://jkt-1.s3.eranyacloud.id/bucket1-modernland/";