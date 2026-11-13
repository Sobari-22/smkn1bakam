<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ErapotController extends Controller
{
    public function index(): View
    {
        $erapotUrl = 'http://36.92.146.177:8154';

        return view('admin.erapor.index', compact('erapotUrl'));
    }
}