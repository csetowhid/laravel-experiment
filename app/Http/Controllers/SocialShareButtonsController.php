<?php

namespace App\Http\Controllers;
use Jenssegers\Date\Date;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Rajurayhan\Bndatetime\BnDateTimeConverter; // on Top

class SocialShareButtonsController extends Controller
{
    public function ShareWidget()
    {
        $url = url()->full();
        // $url = "https://dailynews.goberr.com/single-main/8/post";
        $shareComponent = \Share::page($url)
        ->facebook()
        ->twitter()
        ->linkedin()
        ->telegram()
        ->whatsapp()        
        ->reddit();
        
        return view('posts', compact('shareComponent'));
    }

    public function time()
    {
        // $dateConverter  =  new  BnDateTimeConverter();
        // $dateConverter->getConvertedDateTime('2018-09-07 12:19:50 pm',  'EnBn', ''); // Friday 23rd Bhadra 1425 12:19:50 pm
        // $dateConverter->getConvertedDateTime('2018-09-07 12:19:50 pm',  'BnBn', ''); // শুক্রবার ২৩শে ভাদ্র ১৪২৫ দুপুর ১২:১৯:৫০
        // $bew = $dateConverter->getConvertedDateTime('2018-09-07 12:19:50 pm',  'BnEn', ''); // শুক্রবার ৭ই সেপ্টেম্বর ২০১৮ দুপুর ১২:১৯:৫০
        // $dateConverter->getConvertedDateTime('2018-09-07 12:19:50 pm',  'EnEn', ''); // Friday 7th September 2018 12:19:50 PM

        // dd($bew);


        // $currentDate = date("l, F j, Y");
        // $engDATE = array('1','2','3','4','5','6','7','8','9','0','January','February','March','April',
        // 'May','June','July','August','September','October','November','December','Saturday','Sunday',
        // 'Monday','Tuesday','Wednesday','Thursday','Friday');
        // $bangDATE = array('১','২','৩','৪','৫','৬','৭','৮','৯','০','জানুয়ারী','ফেব্রুয়ারী','মার্চ','এপ্রিল','মে',
        // 'জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর','শনিবার','রবিবার','সোমবার','মঙ্গলবার','
        // বুধবার','বৃহস্পতিবার','শুক্রবার' 
        // );
        // $convertedDATE = str_replace($engDATE, $bangDATE, $currentDate);
        // dd($convertedDATE);


        // Carbon::setLocale('bn');
        // echo $base_weight_category->created_at->translatedFormat('l F j, Y');

        // Carbon::setLocale('bn');
        // dd(Carbon::now()->addYear()->diffForHumans());

        // <a href="http://www.facebook.com/sharer.php?u=http://www.example.com" target="_blank">Share to FaceBook</a>

        $user = User::first();
        $now = new Carbon();
        $dt = new Carbon($user->created_at);
        $dt->setLocale('bn');
        $res = $dt->diffForHumans($now);


        // $res->split(3);
        $replaced = Str::replaceFirst('2', '২', $res);
        dd($replaced);


        // Carbon::setLocale('bn');
        // // $trd= $user->created_at->translatedFormat('l, F j, Y');
        // // $trd= $user->created_at->translatedFormat('l, F j, Y: h:i A');
        // $trd= $user->created_at->translatedFormat('F j, Y: h:i A');

        // $engDATE = array('1','2','3','4','5','6','7','8','9','0','01','02','03','04','05','06','07','08','09','10','11','12','January','February','March','April',
        // 'May','June','July','August','September','October','November','December','Saturday','Sunday',
        // 'Monday','Tuesday','Wednesday','Thursday','Friday');
        // $bangDATE = array('১','২','৩','৪','৫','৬','৭','৮','৯','০','০১','০২','০৩','০৪','০৫','০৬','০৭','০৮','০৯','১০','১১','১২','জানুয়ারী','ফেব্রুয়ারী','মার্চ','এপ্রিল','মে',
        // 'জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর','শনিবার','রবিবার','সোমবার','মঙ্গলবার','
        // বুধবার','বৃহস্পতিবার','শুক্রবার' 
        // );
        // $convertedDATE = str_replace($engDATE, $bangDATE, $trd);
        // dd($convertedDATE);



        $startTime = $request->start_time;
        $endTime = $request->end_time;
        $existingSlot = Bookings::where(function($query) use ($startTime,$endTime) {

            $query->where('start_time','>=',$startTime)->where('end_time','<=',$endTime);

        })->orWhere(function($query) use ($startTime,$endTime) {

            $query->where('start_time','<=',$startTime)->where('end_time','>=',$startTime);
            
        })->orWhere(function($query) use ($startTime,$endTime) {
            $query->where('start_time','<=',$endTime)->where('end_time','>=',$endTime);
        })->orWhere(function($query) use ($startTime,$endTime) {
            $query->where('start_time','<=',$startTime)->where('end_time','>=',$endTime);
        })->first();

        $data = [
            'table_id' => $request->tables_id,
            'users_id' => Auth::user()->id,
            'start_time'=> $request->start_time,
            'end_time'=> $request->end_time,
        ];
        $newBooking = Bookings::create($data);

        

    }
}
