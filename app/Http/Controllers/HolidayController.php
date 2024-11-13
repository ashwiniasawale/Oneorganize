<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Utility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event as GoogleEvent;

class HolidayController extends Controller
{

    public function index(Request $request)
    {
        if(\Auth::user()->can('manage holiday'))
        {
            $holidays=Holiday::select('id','date','holiday_year','occasion');
            if (!empty($request->holiday_year) && !empty($request->holiday_month)) {
                $holidays = $holidays->where('holiday_year', '=',$request->holiday_year)->whereMonth('date', $request->holiday_month)->orderBy('date','asc');
            }else{
                $holidays=$holidays->where('holiday_year', '=', date('Y'))->whereMonth('date', date('n'))->orderBy('date','asc');
            }
           
            $holidays = $holidays->get();

            return view('holiday.index', compact('holidays'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    

    public function create_default_holiday()
    {
       if(\Auth::user()->can('create holiday'))
       {
            return view('holiday.create_default_holiday');
       }else{
            return redirect()->back()->with('error',__('Permission denied.'));
       }
    }

    public function create()
    {
        if(\Auth::user()->can('create holiday'))
        {
            $settings = Utility::settings();
            return view('holiday.create',compact('settings'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }
    public function mark_holiday_store(Request $request)
    {
        if(\Auth::user()->can('create holiday'))
        {
           
            $validator=\Validator::make($request->all(),[
                'holiday_year'=>'required',
                'mark_days'=>'required'
            ]);
            if($validator->fails())
            {
                $messages=$validator->getMessageBag();
                
                return response()->json(['error'=>$messages->first()]);
            }
            $startDate = Carbon::create($request->holiday_year, 1, 1);
            $endDate = Carbon::create($request->holiday_year, 12, 31);
    
            $dayName = $request->mark_days;
           
              
            while ($startDate->lte($endDate)) 
            {
                if (in_array($startDate->format('l'), $dayName)) {
                    $holiday_date=$startDate->toDateString();
                    $check_date=Holiday::where('date','=',$holiday_date)->where('holiday_year','=',$request->holiday_year)->get()->count();
                    if($check_date==0)
                    {
                        $holiday             = new Holiday();
                        $holiday->date       = $holiday_date;
                        $holiday->holiday_year     = $request->holiday_year;
                        $holiday->occasion   = $startDate->format('l');
                        $holiday->created_by = \Auth::user()->id;
                        $holiday->save();
                    }
                }
              
             $startDate->addDay();
            }
          
            return response()->json(['success'=>'Holiday successfully created.']); 
            
        }else{
           
            return response()->json(['error'=>'Permission denied.']);
        }

    }
    public function store(Request $request)
    {
        if(\Auth::user()->can('create holiday'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'occasion' => 'required',
                                   'holiday_year'=>'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $total_count=count($request->holiday_year);
            for($i=0;$i<$total_count;$i++)
            {
                $check_date=Holiday::where('date','=',$request->date[$i])->where('holiday_year','=',$request->holiday_year[$i])->get()->count();
                if($check_date==0)
                {
                    $holiday             = new Holiday();
                    $holiday->date       = $request->date[$i];
                    $holiday->holiday_year     = $request->holiday_year[$i];
                    $holiday->occasion   = $request->occasion[$i];
                    $holiday->created_by = \Auth::user()->id;
                    $holiday->save();
                }
            }
          

            //For Notification
            $setting  = Utility::settings(\Auth::user()->creatorId());
            $holidayNotificationArr = [
                'holiday_title' => $request->occasion,
                'holiday_date' => $request->date,
            ];
            //Slack Notification
            if(isset($setting['holiday_notification']) && $setting['holiday_notification'] ==1)
            {
                Utility::send_slack_msg('new_holiday', $holidayNotificationArr);
            }
            //Telegram Notification
            if(isset($setting['telegram_holiday_notification']) && $setting['telegram_holiday_notification'] ==1)
            {
                Utility::send_telegram_msg('new_holiday', $holidayNotificationArr);
            }

            //For Google Calendar
            if($request->get('synchronize_type')  == 'google_calender')
            {

                $type ='holiday';
                $request1=new Holiday();
                $request1->title=$request->occasion;
                $request1->start_date=$request->date;
                $request1->end_date=$request->holiday_year;

                Utility::addCalendarData($request1 , $type);

            }

            //webhook
            $module ='New Holiday';
            $webhook =  Utility::webhookSetting($module);
            if($webhook)
            {
                $parameter = json_encode($holiday);
                $status = Utility::WebhookCall($webhook['url'],$parameter,$webhook['method']);

                if($status == true)
                {
                    return redirect()->route('holiday.index')->with('success', 'Holiday successfully created.');
                }
                else
                {
                    return redirect()->back()->with('error', __('Webhook call failed.'));
                }
            }

            return redirect()->route('holiday.index')->with('success', 'Holiday successfully created.');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function show(Holiday $holiday)
    {
        //
    }


    public function edit(Holiday $holiday)
    {
        if(\Auth::user()->can('edit holiday'))
        {
            return view('holiday.edit', compact('holiday'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function update(Request $request, Holiday $holiday)
    {
        if(\Auth::user()->can('edit holiday'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'occasion' => 'required',
                                   'holiday_year'=>'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $check_date=Holiday::where('date','=',$request->date)->where('holiday_year','=',$request->holiday_year)->get()->count();
           
            if($check_date>0)
            {
                $msg='Date already added for this year';
                return redirect()->route('holiday.index')->with('error',$msg);
            }else{
            $holiday->date     = $request->date;
            $holiday->holiday_year       = $request->holiday_year;
            $holiday->occasion = $request->occasion;
            $holiday->save();
            }
            return redirect()->route('holiday.index')->with('success', 'Holiday successfully updated.');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function destroy(Holiday $holiday)
    {
        if(\Auth::user()->can('delete holiday'))
        {
            $holiday->delete();

            return response()->json(['success'=>'Holiday successfully deleted.']);
          //  return redirect()->route('holiday.index')->with('success', 'Holiday successfully deleted.');
        }
        else
        {
            return response()->json(['error'=>'Permission denied.']);
          //  return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function calender(Request $request)
    {

        if(\Auth::user()->can('manage holiday'))
        {
            $transdate = date('Y-m-d', time());

            $holidays = Holiday::where('created_by', '=', \Auth::user()->creatorId());

            if(!empty($request->start_date))
            {
                $holidays->where('date', '>=', $request->start_date);
            }
            if(!empty($request->end_date))
            {
                $holidays->where('date', '<=', $request->end_date);
            }

            $holidays = $holidays->get();

            $arrHolidays = [];

            foreach($holidays as $holiday)
            {
                $arr['id']        = $holiday['id'];
                $arr['title']     = $holiday['occasion'];
                $arr['start']     = $holiday['date'];
                $arr['end']       = $holiday['end_date'];
                $arr['className'] = 'event-primary';
                $arr['url']       = route('holiday.edit', $holiday['id']);
                $arrHolidays[]    = $arr;
            }
            $arrHolidays = str_replace('"[', '[', str_replace(']"', ']', json_encode($arrHolidays)));

            return view('holiday.calender', compact('arrHolidays','transdate','holidays'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }

    //for Google Calendar
    public function get_holiday_data(Request $request)
    {

        if($request->get('calender_type') == 'goggle_calender')
        {
            $type ='holiday';
            $arrayJson =  Utility::getCalendarData($type);
        }
        else
        {
            $data =Holiday::where('created_by', '=', \Auth::user()->creatorId())->get();


            $arrayJson = [];
            foreach($data as $val)
            {
//                dd($val);

                $end_date=date_create($val->end_date);
                date_add($end_date,date_interval_create_from_date_string("1 days"));
                $arrayJson[] = [
                    "id"=> $val->id,
                    "title" => $val->occasion,
                    "start" => $val->date,
                    "end" => date_format($end_date,"Y-m-d H:i:s"),
                    "className" => 'event-primary',
                    "textColor" => '#51459d',
                    'url'      => route('holiday.edit', $val->id),
                    "allDay" => true,
                ];
            }
        }

        return $arrayJson;
    }

}
