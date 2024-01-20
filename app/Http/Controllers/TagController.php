<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Sector;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TagController extends Controller
{
    //
    public function tags() {
        $devices = Device::all();
        $sectors = Sector::all();
        $tags = Tag::with('sector', 'device')->get();
        return Inertia::render('Gora/TagPanel', compact('devices', 'sectors', 'tags'));
    }

    public function addTag(Request $request) {
        $sector = Sector::find($request->sector);
        $device = Device::find($request->device);

        /*$number = mt_rand(1000, 9999);
        while(Tag::where('qr', $number)->exists()) {
            $number = mt_rand(1000, 9999);
        }*/
        
        $lastTag = Tag::latest()->first();
 

        $tag = new Tag();
        $tag->qr = $lastTag->qr + 1;
        $tag->dependence = $request->dependence;
        $tag->location = $request->location;
        $tag->address= $request->address;
        $tag->sector()->associate($sector);
        $tag->device()->associate($device);

        $tag->url = $request->root() . '/gora/formulariotecnico/qr/'. $tag->qr;

        $tag->save();

        return  to_route('gora.tags');
    }

    public function editTag(Request $request, $id)
    {
        $tag = Tag::find($id);

        $tag->sector_id = $request->input('sector');
        $tag->dependence = $request->input('dependence');
        $tag->location = $request->input('location');
        $tag->device_id = $request->input('device');
        $tag->address = $request->input('address');

        $tag->save();

        return to_route('gora.tags');
    }
}
