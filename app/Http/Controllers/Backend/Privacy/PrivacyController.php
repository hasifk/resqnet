<?php

namespace App\Http\Controllers\Backend\Privacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PrivacyController extends Controller {
    public function privacy() {
        return view('backend.privacy.index');
    }
}
