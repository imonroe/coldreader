<?php

namespace imonroe\cr_network_aspects;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use imonroe\crps\Aspect;
use imonroe\crps\Ana;

class APIResultAspect extends \imonroe\crps\Aspect
{
    public function notes_schema()
    {
        return parent::notes_schema();
    }
    public function create_form($subject_id, $aspect_type_id = null)
    {
        return parent::create_form($subject_id, $this->aspect_type);
    }
    public function edit_form()
    {
        return parent::edit_form($id);
    }
    public function display_aspect()
    {
        $output = "<strong>API Result: <strong>";
        $output .= '<pre>';
        $output .= $this->aspect_data;
        $output .= '</pre>';
        return $output;
    }
    public function parse()
    {
    }
}  // End of the APIResultAspectclass.
