<?php

namespace App\Console\Commands;

use App\Models\TrainingRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use setasign\Fpdi\Fpdi;

class generateCertificate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:cert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Individual Certificates';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $concluded_trainings = TrainingRequest::where('status','completed')->where('sent_pdf','no')->get();
        foreach($concluded_trainings as $training){
            foreach($training->trainees as $trainee){

                $file = public_path("storage/sample.pdf");
                $outputFilePath = public_path("storage/".$trainee->user->id."/".$training->id."/certificate.pdf");

                $pdf = new FPDI;

                $pdf->addPage('L');
                $pagecount = $pdf->setSourceFile($file);;
                $tplIdx = $pdf->importPage(1); 
                $pdf->useTemplate($tplIdx); 
                $pdf->SetFont('Times','I',40);
                $pdf->SetTextColor(0,0,0); 
                $pdf->SetXY(95, 86); 
                $pdf->Write(0, $trainee->user->name); 

                $pdf->SetFont('Times','I',30);
                $pdf->SetXY(95, 112); 
                foreach ($training->trainings as $record){
                $pdf->Write(0, '<li>'.$record->name .'</li>'); 
                }
        
                $pdf->SetFont('Times','I',15);
                $pdf->SetXY(92, 126); 
                $pdf->Write(0, $training->start_time->format('Y-m-d')); 
        
                $pdf->SetFont('Times','I',15);
                $pdf->SetXY(192, 126); 
                $pdf->Write(0, $training->end_time->format('Y-m-d')); 
        
                $pdf->SetFont('Times','I',25);
                $pdf->SetXY(63, 153.5); 
                $pdf->Write(0, "Head Office"); 
        
                $pdf->SetFont('Times','I',25);
                $pdf->SetXY(169, 153.5); 
                foreach ($training->trainers as $trainer){
                $pdf->Write(0, $trainer->name); 
                } 
        
                $pdf->SetFont('Times','I',15);
                $pdf->SetXY(33, 189.5); 
                foreach ($training->trainers as $trainer){
                $pdf->Write(0, $trainer->name); 
                } 
                //$pdf->Write(0, "Samuel Kadima - ".$user->job_title); 
        
                $pdf->SetFont('Times','I',15);
                $pdf->SetXY(180, 189.5); 
                $pdf->Write(0, "MN"); 
                $pdf->Output();
          
                $pdf->Output($outputFilePath, 'F');

                $data = [
                    'intro'  => 'Dear '.$trainee->user->name.',',
                    'content'   => 'Congratulation on completing your training. Kindly find attached your certificate.',
                    'name' => $trainee->user->name,
                    'email' => $trainee->user->email,
                    'subject'  => 'Certificate of Participation'
                ];
                Mail::send('emails.order', $data, function($message) use ($data, $pdf,$outputFilePath) {
                    $message->to($data['email'], $data['name'])
                            ->subject($data['subject'])
                            ->attachData($pdf->Output($outputFilePath, 'F'), "Certificate of participation.pdf");
                });

            }

        }
    }
}
