<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Trainer;
use Illuminate\Http\Request;

class TrainerController extends Controller
{

    public function index()
    {
      
        if(\Auth::user()->can('manage trainer'))
        {
            $trainers = Trainer::where('created_by', '=', \Auth::user()->creatorId())->with('branches')->get();

            return view('trainer.index', compact('trainers'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if(\Auth::user()->can('create trainer'))
        {
            $branches = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('trainer.create', compact('branches'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create trainer'))
        {

            $validator = \Validator::make(
                $request->all(), [
                    'branch' => 'required',
                    'firstname' => 'required|alpha',
                    'lastname' => 'required|alpha',
                    'contact' => ['required', 'regex:/^\d{10,12}$/'],
                    'email' => ['required','email', 'regex:/^[\w\-\.]+@[a-zA-Z0-9\-]+(\.[a-zA-Z]{2,})+$/'],
                    'address' => 'nullable|string',
                    'expertise' => 'nullable|string',
                ],
                [
                    'branch.required' => __('Branch is required.'),
                ],
                [
                    'firstname.required' => __('First name is required.'),
                    'firstname.alpha' => __('Enter a valid first name.'),
                ],[
                    'lastname.required' => __('Last name is required.'),
                    'lastname.alpha' => __('Enter a valid last name.'),
                    'contact.required' => __('Contact number is required.'),
                    'contact.regex' => __('Enter a valid contact number.'),
                    'email.required' => __('Email is required.'),
                    'email.email' => __('Enter a valid email address.'),
                    'address.string' => __('Enter a valid address.'),
                    'expertise.string' => __('Enter a valid expertise.'),
                
                    ]
            );
            if($validator->fails())
            {
             //   return redirect()->back()->withErrors($validator)->withInput();
           //  session()->put('modalData',$request->all());
            //  return redirect()->back()->withErrors($validator)->withInput()->with('modalStayOpen', true)->with('trainer.create', session('modalData'));
              return redirect()->back()->withInput()->withErrors($validator);
                // return redirect()->back()->with('error', $messages->first());
            }

            $trainer             = new Trainer();
            $trainer->branch     = $request->branch;
            $trainer->firstname  = $request->firstname;
            $trainer->lastname   = $request->lastname;
            $trainer->contact    = $request->contact;
            $trainer->email      = $request->email;
            $trainer->address    = $request->address;
            $trainer->expertise  = $request->expertise;
            $trainer->created_by = \Auth::user()->creatorId();
            $trainer->save();

            return redirect()->route('trainer.index')->with('success', __('Trainer  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(Trainer $trainer)
    {
        return view('trainer.show', compact('trainer'));
    }


    public function edit(Trainer $trainer)
    {
        if(\Auth::user()->can('edit trainer'))
        {
            $branches = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('trainer.edit', compact('branches', 'trainer'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function update(Request $request, Trainer $trainer)
    {
        if(\Auth::user()->can('edit trainer'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'branch' => 'required',
                                   'firstname' => 'required',
                                   'lastname' => 'required',
                                   'contact' => 'required',
                                   'email' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $trainer->branch    = $request->branch;
            $trainer->firstname = $request->firstname;
            $trainer->lastname  = $request->lastname;
            $trainer->contact   = $request->contact;
            $trainer->email     = $request->email;
            $trainer->address   = $request->address;
            $trainer->expertise = $request->expertise;
            $trainer->save();

            return redirect()->route('trainer.index')->with('success', __('Trainer  successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(Trainer $trainer)
    {
        if(\Auth::user()->can('delete trainer'))
        {
            if($trainer->created_by == \Auth::user()->creatorId())
            {
                $trainer->delete();

                return redirect()->route('trainer.index')->with('success', __('Trainer successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
