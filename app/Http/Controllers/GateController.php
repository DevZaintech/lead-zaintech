<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;

class GateController extends Controller
{
    public function inputLead()
    {
        return view('gate.inputlead');
    }
}
