<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\EndDay;
use App\Models\Sector;
use App\Models\Technical;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{
    //
    public function settings() {
        $technicals = Technical::all();
        $sectors = Sector::all();
        $devices = Device::all();
        $day = EndDay::first();

        return Inertia::render('Gora/Settings', compact('technicals', 'sectors', 'devices', 'day'));
    }


    public function editEndDay(Request $r) {
        $dayObject = EndDay::first();
        $dayObject->day = $r->day;
        $dayObject->save();
    }

    public function addTechnical (Request $request) {
        $nTechnical = new Technical();
        $nTechnical->name = $request->name_t;
        $nTechnical->key = $request->key_t;
        $nTechnical->job = $request->job_t;
        $nTechnical->save();
    }

    public function editTechnical(Request $request, $id)
    {
        $technical = Technical::find($id);

        $technical->name = $request->input('name');
        $technical->job = $request->input('job');
        $technical->key = $request->input('key');

        $technical->save();

        return to_route('gora.settings');
    }

    public function addSector (Request $request) {
        $nSector = new Sector();
        $nSector->name = $request->name_s;
        $nSector->color = $request->color_s == null ? '#000' : $request->color_s;
        $nSector->save();
    }

    public function editSector(Request $request, $id)
    {
        //return "1";
        $sector = Sector::find($id);

        $sector->name = $request->input('name');
        $sector->color = $request->input('color');

        $sector->save();

        return to_route('gora.settings');
    }

    public function addDevice (Request $request) {
        $nDevice = new Device();
        $nDevice->model = $request->model_d;
        $nDevice->type = $request->type_d;

        $nDevice->price = $request->price_d;

        $nDevice->price_print = $request->price_d_print;
        $nDevice->price_scan = $request->price_d_scan;

        $nDevice->save();
    }

    public function editDevice(Request $request, $id)
    {
        $device = Device::find($id);

        $device->model = $request->input('model');
        $device->type = $request->input('type');
        $device->price = $request->input('price');
        $device->price_print = $request->input('price_print');
        $device->price_scan = $request->input('price_scan');

        $device->save();

        return to_route('gora.settings');
    }
}
