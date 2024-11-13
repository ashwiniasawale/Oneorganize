<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utility;
use App\Models\TemplateLetter;

class TemplateLetterController extends Controller
{
    public function offer_letter()
    {
        if (\Auth::user()->type=='HR' || \Auth::user()->type=='company') {
            $letter=TemplateLetter::get();
           
            return view('template_letter.offer_letter',compact('letter'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create_offer()
    {
        if (\Auth::user()->type=='HR' || \Auth::user()->type=='company') {
            return view('template_letter.create_offer');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function destroy($id)
    {
       
                $data=TemplateLetter::find($id);
                $data->delete();

                return redirect()->route('offer.index')->with('success', __('offer successfully deleted.'));
           
       
    }
    public function store_offer(Request $request)
    {
        if (\Auth::user()->type=='HR' || \Auth::user()->type=='company') {
            $offer=new TemplateLetter();
            $offer->employee_type=$request->employee_type;
            $offer->title=$request->title;
            $offer->employee_name=$request->employee_name;
            $offer->offer_date=$request->offer_date;
            $offer->joining_date=$request->joining_date;
            $offer->ref_no=$request->ref_no;
            $offer->address=$request->address;
            $offer->designation=$request->designation;
            $offer->probation=$request->probation;
            $offer->notice_period=$request->notice_period;
            $offer->salary=$request->salary;
            $offer->save();
            return response()->json(['success'=>'Offer Letter Successfully generated.']);
          
        } else {
            return response()->json(['error'=>'Permission Denied.']);
        }
    }
}
