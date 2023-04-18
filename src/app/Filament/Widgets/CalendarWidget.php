<?php

namespace App\Filament\Widgets;

use App\Models\TrainingRequest;
use Filament\Widgets\Widget;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{

    public function fetchEvents(array $fetchInfo): array
    {
            $trainings = TrainingRequest::WhereHas('trainees', function ($query) {
                $query->where(['user_id' => auth()->id()]);
            })->whereNotNull('start_time')->get();


        $data = $trainings->map(function($training, $key) {
            return [
                'id' => $training->id,
                'title' => $training->trainings->name,
                'start' => $training->start_time->addHours(3),
                'end' => $training->end_time->addHours(3),
            ];
        })->toArray();
     
        return $data;
    }

}
