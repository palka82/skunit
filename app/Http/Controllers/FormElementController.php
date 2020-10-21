<?php

namespace App\Http\Controllers;

use App\Form_Elements;
use Illuminate\Http\Request;

class FormElementController extends Controller
{
    //
    public function show( $id ){

    }

    public function store( Request $request ){
        $element = new \App\Form_Elements;
        $element->save();
    }
}
