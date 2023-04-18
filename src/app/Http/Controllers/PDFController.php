<?php

namespace App\Http\Controllers;

use App\Imports\ImportUser;
use App\Models\Trainer;
use App\Models\Training;
use App\Models\TrainingRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use setasign\Fpdi\Fpdi;

class PDFController extends Controller
{
     /**
     * Write code on Method
     *
     * @return response()
     */
    public function index(Request $request)
    {
        $filePath = public_path("storage/sample.pdf");
        $outputFilePath = public_path("storage/sample_output.pdf");
        $this->fillPDFFile($filePath, $outputFilePath);
          
        return response()->file($outputFilePath);
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function fillPDFFile($file, $outputFilePath)
    {
        $training = TrainingRequest::find(1);
        $user = User::find(1);
        $training_name = Training::find(1);
        $trainer = Trainer::find(1);
        
        $pdf = new FPDI;

        $pdf->addPage('L');
        $pagecount = $pdf->setSourceFile($file);;
        $tplIdx = $pdf->importPage(1); 
        $pdf->useTemplate($tplIdx); 
        $pdf->SetFont('Times','I',40);
        $pdf->SetTextColor(0,0,0); 
        $pdf->SetXY(95, 86); 
        $pdf->Write(0, $user->name); 

        $pdf->SetFont('Times','I',30);
        $pdf->SetXY(95, 112); 
        $pdf->Write(0, $training_name->name); 

        $pdf->SetFont('Times','I',15);
        $pdf->SetXY(92, 126); 
        $pdf->Write(0, $training->start_time); 

        $pdf->SetFont('Times','I',15);
        $pdf->SetXY(192, 126); 
        $pdf->Write(0, $training->end_time); 

        $pdf->SetFont('Times','I',25);
        $pdf->SetXY(63, 153.5); 
        $pdf->Write(0, "Head Office"); 

        $pdf->SetFont('Times','I',25);
        $pdf->SetXY(169, 153.5); 
        $pdf->Write(0, $trainer->name); 

        $pdf->SetFont('Times','I',15);
        $pdf->SetXY(33, 189.5); 
        $pdf->Write(0, "Samuel Kadima - ".$user->job_title); 

        $pdf->SetFont('Times','I',15);
        $pdf->SetXY(180, 189.5); 
        $pdf->Write(0, "SK"); 
        $pdf->Output();
  
        return $pdf->Output($outputFilePath, 'F');
    }

    public function import(Request $request){
        Excel::import(new ImportUser, $request->file('file'));
        return 'uploadeeeeeeeeeeeed';
    }
}
