<?php

namespace App\Http\Controllers;

use App\Forms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Integer;
use Illuminate\Support\Facades\DB;

class FormsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $list["data"] = \App\Forms::all();
        return json_encode($list);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Проверка
        $this->validate($request,[
            'add_form_name' => 'required|string',
            'add_form_desc' => 'required'
        ]);

        $form = new \App\Forms;
        $form->name = $request->input('add_form_name');
        $form->description = $request->input('add_form_desc');
        $form->save();
    }

    public function render(Request $request)
    {
        $index = 0; //Кол-во елементов на форме
        $form_id = $request->input('form_id');
        $data = $request->input('data');
        if( empty($form_id) ){
            $return = '<div class="alert alert-danger" role="alert">Не задана форма!</div>';
            return view("test",['content' => $return]);
        }
        $forms =  \App\Forms::where('id', $form_id)->get();
        if( empty($forms) ){
            $return = '<div class="alert alert-danger" role="alert">Форма с ID #' . $form_id . 'не найдена!</div>';
            return view("test",['content' => $return]);
        }
        Log::info('Форма: '.$forms[0]);
        $elements = \App\Form_Elements::where('form_id', $forms[0]->id)->orderBy('order', 'asc')->get();
        $in_elements = \App\Form_Elements::where('form_id', $forms[0]->id)->where('in','t')->get();
        $in_data = json_decode($data);
        if( sizeof($in_data) != sizeof($in_elements) ){
            $return = '<div class="alert alert-danger" role="alert">Упс. Кол-во вводных данных не совпадает с кол-ом элементов на форме!</div>';
            return view("test",['content' => $return]);
        }
        $return = '<form method="post" name="' . $forms[0]->name . '">';
        foreach ( $elements as $element ){
            switch ( $element->type ){
                case "date":
                    $return .= '<div class="form-group ' . ( !empty($element->label) ? 'row' : '') . '" id="grp_' . $element->name . '" ' . (empty($element->first_show)? 'style="display: none;"':'') . '>';
                    if( !empty($element->label) ){
                        $return .= '<label for="' . $element->name . '" class="col-sm-2 col-form-label">' . $element->label . '</label>';
                        $return .= '<div class="col-sm-10">';
                    }
                    $return .= '<input type="date" name="' . $element->name . '" id="' . $element->name . '" value="' . ( empty($in_data[$index]) ? $element->value : $in_data[$index] ) . '" ' . $element->attr . '>';
                    if( !empty($element->label) ){
                        $return .= '</div>';
                    }
                    $return .= '</div>';
                    if( !empty($element->in) ){
                        $index++;
                    };
                    break;
                case "time":
                    $return .= '<div class="form-group ' . ( !empty($element->label) ? 'row' : '') . '" id="grp_' . $element->name . '" ' . (empty($element->first_show)? 'style="display: none;"':'') . '>';
                    if( !empty($element->label) ){
                        $return .= '<label for="' . $element->name . '" class="col-sm-2 col-form-label">' . $element->label . '</label>';
                        $return .= '<div class="col-sm-10">';
                    }
                    $return .= '<input type="time" name="' . $element->name . '" id="' . $element->name . '" value="' . ( empty($in_data[$index]) ? $element->value : $in_data[$index] ) . '" ' . $element->attr . '>';
                    if( !empty($element->label) ){
                        $return .= '</div>';
                    }
                    $return .= '</div>';
                    if( !empty($element->in) ) {
                        $index++;
                    }
                    break;
                case "input_text":
                    $return .= '<div class="form-group ' . ( !empty($element->label) ? 'row' : '') . '" id="grp_' . $element->name . '" ' . (empty($element->first_show)? 'style="display: none;"':'') . '>';
                    Log::info($element->first_show);
                    if( !empty($element->label) ){
                        $return .= '<label for="' . $element->name . '" class="col-sm-2 col-form-label">' . $element->label . '</label>';
                        $return .= '<div class="col-sm-10">';
                    }
                    $return .= '<input type="text" name="' . $element->name . '" id="' . $element->name . '" value="' . ( empty($in_data[$index]) ? $element->value : $in_data[$index] ) . '" ' . $element->attr . '>';
                    if( !empty($element->label) ){
                        $return .= '</div>';
                    }
                    $return .= '</div>';
                    if( !empty($element->in) ) {
                        $index++;
                    }
                    break;
                case "p":
                    if( !empty($element->label) ){
                        $return .= '<label for="' . $element->name . '" class="col-sm-2 col-form-label">' . $element->label . '</label>';
                    }
                    $return .= '<p id="' . $element->name . '" ' . $element->attr . '>' . ( empty($in_data[$index]) ? $element->value : $in_data[$index] ) . '</p>';
                    if( !empty($element->in) ) {
                        $index++;
                    }
                    break;
                case "input_hidden":
                    if( !empty($element->label) ){
                        $return .= '<label for="' . $element->name . '" class="col-sm-2 col-form-label">' . $element->label . '</label>';
                    }
                    $return .= '<input type="hidden" name="' . $element->name . '" id="' . $element->name . '" value="' . ( empty($in_data[$index]) ? $element->value : $in_data[$index] ) . '" ' . $element->attr . '>';
                    if( !empty($element->in) ) {
                        $index++;
                    }
                    break;
                case "textarea":
                    $return .= '<div class="form-group ' . ( !empty($element->label) ? 'row' : '') . '" id="grp_' . $element->name . '" ' . (empty($element->first_show)? 'style="display: none;"':'') . '>';
                    if( !empty($element->label) ){
                        $return .= '<label for="' . $element->name . '" class="col-sm-2 col-form-label">' . $element->label . '</label>';
                        $return .= '<div class="col-sm-10">';
                    }
                    $return .= '<textarea name="' . $element->name . '" id="' . $element->name . '" '. $element->attr . '>' . ( empty($in_data[$index]) ? $element->value : $in_data[$index] ) . '</textarea>';
                    if( !empty($element->label) ){
                        $return .= '</div>';
                    }
                    $return .= '</div>';
                    if( !empty($element->in) ) {
                        $index++;
                    }
                    break;
                case "select":
                    $i = 0;
                    $return .= '<div class="form-group ' . ( !empty($element->label) ? 'row' : '') . '" id="grp_' . $element->name . '" ' . (empty($element->first_show)? 'style="display: none;"':'') . '>';
                    if( !empty($element->label) ){
                        $return .= '<label for="' . $element->name . '" class="col-sm-2 col-form-label">' . $element->label . '</label>';
                        $return .= '<div class="col-sm-10">';
                    }
                    $return .= '<select name="' . $element->name . '" id="' . $element->name . '" ' . $element->attr . '>';
                    //$ls = empty($in_data[$index]) ? $element->value : $in_data[$index];
                    if( strpos($element->value, 'tbl.') === 0 ) {
                        $tbl = explode('.', $element->value);
                        $records = DB::table( $tbl[1] )->get();
                        //$return .= '<option value="0" disabled></option>';
                        foreach ( $records as $record ){
                            $return .= '<option value="' . $record->id . '"' .  (isset($in_data[$index])? (($record->id == $in_data[$index])? ' selected' : ''):'') . '>' . $record->name . '</option>';
                        }
                    } else {
                        $values = json_decode( $in_data[$index] );
                        if (is_array($values)) {
                            foreach ($values as $value) {
                                $return .= '<option value="' . $i . '">' . $value . '</option>';
                                $i++;
                            }
                        }
                    }
                    $return .= '</select>';
                    if( !empty($element->label) ){
                        $return .= '</div>';
                    }
                    $return .= '</div>';
                    if( !empty($element->in) ) {
                        $index++;
                    }
                    break;
                case "button":
                    $return .= '<button id="' .  $element->name . ' name="' . $element->name . '" ' . $element->attr . '>' . $element->value . '</button>';
                    if( !empty($element->in) ) {
                        $index++;
                    }
                    break;
            }
        }
        $return .= '</form>';
        return view("test",['content' => $return]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Forms  $forms
     * @return \Illuminate\Http\Response
     */
    public function show(Forms $forms)
    {
        //
        //$list["data"] = \App\Forms::find($forms->id);
        return $forms->name;
        //return json_encode($list);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Forms  $forms
     * @return \Illuminate\Http\Response
     */
    public function edit(Forms $forms)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Forms  $forms
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Forms $forms)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Forms  $forms
     * @return \Illuminate\Http\Response
     */
    public function destroy(Forms $forms)
    {
        //
    }
}
