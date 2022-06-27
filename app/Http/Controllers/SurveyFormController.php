<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\itSurveyController;
use PDF;

class SurveyFormController extends Controller
{

    public function getTicketSurveyPDF($id) {

        $obj = new itSurveyController();
        $survey = $obj->getTicketSurveyPDF($id);

        $data = [
            'ticket_no' => $survey->ticket->ticket_no,
            'survey_no' => $survey->survey_header->survey_no,
            'survey_date' => $survey->survey_header->survey_date,
            'emp_fullname_th' => $survey->ticket->name_th,
            'emp_fullname_en' => $survey->ticket->name_eng,
            'emp_department' => $survey->ticket->dept_name,
            'emp_position' => '',
            'emp_tel' => $survey->ticket->tel,
            'ticket_subject' => $survey->ticket->subject_name,
            'survey_score_overall' => $survey->survey_header->level_overall,
            'survey_remark' => $survey->survey_header->survey_remark,
            'survey_score' => $survey->survey_detail
        ];
        
        $pdf = PDF::loadView('pdf.surveyticketform', $data) ->setPaper('a4');
        return @$pdf->stream($data->ticket_no.'pdf');
    }
}
