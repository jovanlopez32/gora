<?php

namespace App\Http\Controllers;

use App\Models\CountReport;
use App\Models\Device;
use App\Models\EndDay;
use App\Models\ProblemReport;
use App\Models\Sector;
use App\Models\Tag;
use App\Models\Technical;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class FormController extends Controller
{
    //

    public function form(Request $request, $tag, $type, $id) {
        $tag = Tag::with('device')->with('sector')->find($tag);
        $technical = $type == 'usuario' ? User::find($id) : Technical::find($id);

        $count_reports = $tag->countreports()->with('device')->latest()->get();
        $problem_reports = $tag->problemreports()->latest()->get();

        return Inertia::render('FormTechnical/TechnicalForm', compact('tag', 'technical', 'count_reports', 'problem_reports'));
    }

    public function passTechnical($qr_code) {
        $qr_check = Tag::where('qr', $qr_code)->exists();
        if(!$qr_check) { return to_route('gora.technical.qr.error'); }
        return redirect()->route('gora.technical.form.key', compact('qr_code'));
    }

    public function checkTechnicalForm(Request $request){

        $qr_code = $request->input('qr_code');

        $error = $request->input('error');

        $qr_check = Tag::where('qr', $qr_code)->exists();

        $sector =  Tag::where('qr', $qr_code)->with('sector')->first()->sector->name;

        if(!$qr_check) { return to_route('gora.technical.qr.error'); }

        return Inertia::render('FormTechnical/CheckUser', compact('qr_code', 'error', 'sector'));
    }

    public function checkTechnicalKey(Request $request){
        $qr_code = $request->qr_code;
        $error = true;
        $check_key = Technical::where('key', $request->key)->exists();
        $check_key_user = User::where('key', $request->key)->exists();
        if(!$check_key && !$check_key_user) {
            //mandamos un mensaje que no existe esa clave y que es un intruso curioso.
            return redirect()->route('gora.technical.form.key', compact('qr_code', 'error'));
        }
        //si alguno de los dos fue verdadero obtenemos el usuario que esta accediendo.
        $technicalData = $check_key == true ? ['id' => Technical::where('key', $request->key)->first()->id, 'type' => 'tecnico'] : ['id' => User::where('key', $request->key)->first()->id, 'type' => 'usuario'];

        $technical = json_decode(json_encode($technicalData));

        $tag = Tag::where('qr', $qr_code)->first();

        return redirect()->route('gora.technical.form', ['tag' => $tag->id, 'type' => $technical->type, 'id' => $technical->id]);
    }

    public function error() {
        return Inertia::render('FormTechnical/Error/ErrorTechnical');
    }

    /* Save Reports */
    public function saveCountReport(Request $request){
        $tagAll = Tag::with('sector')->with('device')->find($request->tag);

        $sector = Sector::find($tagAll->sector->id);
        $device = Device::find($tagAll->device->id);
        $tag = Tag::find($request->tag);

        $checkuser = User::where('key', $request->technical)->exists();
        $technical = null;
        if($checkuser){
            $technical = User::where('key', $request->technical)->first();
        } else {
            $technical = Technical::where('key', $request->technical)->first();
        }

        /* Fecha actual */
        $current_date = Carbon::now();

        /* Checamos si hay algun registro ya hecho anterior para asi evitar duplicidad. */
        if($tag->countreports()->whereMonth('created_at', $current_date->month)->exists()){
            return 'Ya existe registro para esta fecha.';
        }

        /* Esto solo se hara una vez y es para cargar los datos del inicio para que funcione correctamente la plataforma ========*/
        $previous_month = Carbon::now()->subMonthNoOverflow(1);
/*  */
        /* Verificamos que la etiqueta tenga reportes anteriores, si no tiene reportes anteriores entonces damos por hecho que es nueva */
        $check_new_tag = CountReport::where('tag_id', $request->tag)->exists();
        if(!$check_new_tag) {

            /* Si no hay un registro anterior quiere decir que hay una nueva etiqueta*/
            $initial_count_report = new CountReport();
            $initial_count_report->technical_name = $technical->name;
            $initial_count_report->tag()->associate($tag);
            $initial_count_report->device()->associate($device);
            $initial_count_report->sector()->associate($sector);

            /* Duplicadora */
            $initial_count_report->previous_reading = 0;
            $initial_count_report->current_reading = $request->count;

            /* Impresiones */
            $initial_count_report->previous_reading_print = 0;
            $initial_count_report->current_reading_print = $request->count_print;

            /* Escaner */
            $initial_count_report->previous_reading_scan = 0;
            $initial_count_report->current_reading_scan = $request->count_scan;

            $initial_count_report->production = 0;
            $initial_count_report->production_print = 0;
            $initial_count_report->production_scan = 0;


            $initial_count_report->total = 0;
            $initial_count_report->total_print = 0;
            $initial_count_report->total_scan = 0;

            $initial_count_report->created_at = $previous_month;
            $initial_count_report->updated_at = $previous_month;

            $initial_count_report->total_global =   $initial_count_report->total_scan +   $initial_count_report->total_print +   $initial_count_report->total;


            $initial_count_report->save();

            return 'Primeros datos Guardados con exito';
        }


        $previous_tag = CountReport::where('tag_id', $request->tag)->latest('created_at')->first();

        $day = $current_date->day;

        $count_report = new CountReport();
        $count_report->technical_name = $technical->name;
        $count_report->tag()->associate($tag);
        $count_report->device()->associate($device);
        $count_report->sector()->associate($sector);


        $count_report->previous_reading = $previous_tag->current_reading;
        $count_report->current_reading = $request->count;

        $count_report->previous_reading_print = $previous_tag->current_reading_print;
        $count_report->current_reading_print = $request->count_print;

        $count_report->previous_reading_scan = $previous_tag->current_reading_scan;
        $count_report->current_reading_scan = $request->count_scan;

        /* Totales y producciones */

        $count_report->production = $request->count - $previous_tag->current_reading;
        $count_report->total = $count_report->production * $device->price;

        $count_report->production_print = $request->count_print - $previous_tag->current_reading_print;
        $count_report->total_print = $count_report->production_print * $device->price_print;

        $count_report->production_scan = $request->count_scan - $previous_tag->current_reading_scan;
        $count_report->total_scan = $count_report->production_scan * $device->price_scan;


        $count_report->total_global =   $count_report->total_scan +   $count_report->total_print +   $count_report->total;

        /* Cambiamos el mes actual al anterior si esta dentro de los primeros 10 dias */
        $endday = EndDay::first();
        //cambiar 15 por 30
        if ($day >= 1 && $day <= $endday->day) {
            #Hacemos algo para que created_at tenga la fecha del mes anterior.
            $count_report->created_at = $previous_month;
        }
        $imageUrls = [];

        if($request->hasFile('images') ){
            $current_date = date('M') . date('Y');
            foreach ($request->file('images') as $index => $uploadedFile) {
                $rand = rand(10000, 99999);
                $image_name = 'counter-' . $device->model . $current_date . $index . $rand . '.' . $uploadedFile->getClientOriginalExtension();
                $uploadedFile->storeAs('public/image', $image_name);
                $image_url = Storage::url('image/' . $image_name);
                $imageUrls[] = $image_url;
            }
        }

        $urlsJson = json_encode($imageUrls);
        $count_report->url = $urlsJson;
        $count_report->save();

        return to_route('gora.technical.success');
    }


    public function saveProblemReport(Request $request) {

        $tagAll = Tag::with('sector')->with('device')->find($request->tag);

        $sector = Sector::find($tagAll->sector->id);
        $device = Device::find($tagAll->device->id);
        $tag = Tag::find($request->tag);

        $checkuser = User::where('key', $request->technical)->exists();
        $technical = null;
        if($checkuser){
            $technical = User::where('key', $request->technical)->first();
        } else {
            $technical = Technical::where('key', $request->technical)->first();
        }

        $problem_report = new ProblemReport();
        $problem_report->technical_name = $technical->name;
        $problem_report->tag()->associate($tag);
        $problem_report->device()->associate($device);
        $problem_report->sector()->associate($sector);

        $problem_report->problem = $request->problem;
        $problem_report->description = $request->description;

        $imageUrls = [];

        if($request->hasFile('images') ){
            /* $current_date = date('M') . date('Y');
            $image_name = 'problem-' . $device->model . $current_date . '.' .  $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('public/image', $image_name);
            $image_url = Storage::url('image/' . $image_name);
            $problem_report->url = $image_url; */
            $rand = rand(10000, 99999);
            $current_date = date('M') . date('Y');
            foreach ($request->file('images') as $index => $uploadedFile) {
                $image_name = 'problem-' . $device->model . $current_date . $index . $rand . '.' . $uploadedFile->getClientOriginalExtension();
                $uploadedFile->storeAs('public/image', $image_name);
                $image_url = Storage::url('image/' . $image_name);
                $imageUrls[] = $image_url;
            }
        }

        $urlsJson = json_encode($imageUrls);
        $problem_report->url = $urlsJson;
        $problem_report->save();

        return to_route('gora.technical.success');
    }

    public function success() {
        return Inertia::render('FormTechnical/Coverage/Success');
    }

}
