<?php

namespace App\Http\Controllers;

use App\Models\TrainingRequest;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class TrainingRequestController extends Controller
{
    public function submitForReview(TrainingRequest $record)
    {

        $record->update(['status' => request()->data]);

        Notification::make()
            ->title('Request status updated')
            ->success()
            ->send();

        if (request()->data == 'in review') {
            return redirect('/admin/training-requests');
        } elseif (request()->data == 'training done') {
            return redirect('/admin/approved-training-requests');
        } else {
            return redirect('/admin/trainings-review-and-approval');
        }
    }
}
