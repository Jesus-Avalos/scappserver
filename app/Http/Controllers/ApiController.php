<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Event;
use App\Models\Slider;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getSliders()
    {
        $sliders = Slider::where('status', 1)->orderBy('position', 'ASC')->get();
        return $sliders;
    }

    public function getEvents()
    {
        $events = Event::where('status', 1)->orderBy('id', 'DESC')->get();
        return $events;
    }

    public function getAreas()
    {
        $areas = Area::with(['services', 'contests.files'])->where('status', 1)->orderBy('id', 'ASC')->get();
        return $areas;
    }

    public function getData()
    {
        $sliders = Slider::where('status', 1)->orderBy('position', 'ASC')->get();
        $events = Event::where('status', 1)->orderBy('id', 'DESC')->get();
        $areas = Area::with(['services', 'contests.files'])->where('status', 1)->orderBy('id', 'ASC')->get();
        return [
            'sliders' => $sliders,
            'events' => $events,
            'areas' => $areas
        ];
    }
}
