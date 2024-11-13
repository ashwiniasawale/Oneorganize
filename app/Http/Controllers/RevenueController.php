<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Customer;
use App\Models\InvoicePayment;
use App\Models\ProductServiceCategory;
use App\Models\Revenue;
use App\Models\Transaction;
use App\Models\Utility;
use App\Models\TransactionLines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RevenueController extends Controller
{

    public function index(Request $request)
    {

        if(\Auth::user()->can('manage revenue'))
        {
            $customer = Customer::get()->pluck('name', 'id');
            $customer->prepend('Select Customer', '');

          
            $category = ProductServiceCategory::where('type', '=', 'income')->get()->pluck('name', 'id');
            $category->prepend('Select Category', '');


            $query = Revenue::query();


            if(count(explode('to', $request->date)) > 1)
            {
                $date_range = explode(' to ', $request->date);
                $query->whereBetween('date', $date_range);
            }
            elseif(!empty($request->date))
            {
                $date_range = [$request->date , $request->date];
                $query->whereBetween('date', $date_range);
            }

            if(!empty($request->customer))
            {
                $query->where('customer_id', '=', $request->customer);
            }
            if(!empty($request->account))
            {
                $query->where('account_id', '=', $request->account);
            }
            if(!empty($request->category))
            {
                $query->where('category_id', '=', $request->category);
            }

            if(!empty($request->payment))
            {
                $query->where('payment_method', '=', $request->payment);
            }

            $revenues = $query->with(['customer','category'])->get();

            return view('revenue.index', compact('revenues', 'customer', 'category'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {

        if(\Auth::user()->can('create revenue'))
        {
            $customers = Customer::get()->pluck('name', 'id');
            $customers->prepend('--', 0);
            $categories = ProductServiceCategory::where('type', '=', 'income')->get()->pluck('name', 'id');
           
            return view('revenue.create', compact('customers', 'categories'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create revenue'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'amount' => 'required',
                                 
                                   'category_id' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return response()->json(['error'=>$messages->first()]);
            }

            $revenue                 = new Revenue();
            $revenue->date           = $request->date;
            $revenue->amount         = $request->amount;
          
            $revenue->customer_id    = $request->customer_id;
            $revenue->category_id    = $request->category_id;
            $revenue->payment_method = 0;
            $revenue->reference      = $request->reference;
            $revenue->description    = $request->description;
           


            $revenue->created_by     = \Auth::user()->id;
            $revenue->save();

            $category            = ProductServiceCategory::where('id', $request->category_id)->first();
            $revenue->payment_id = $revenue->id;
            $revenue->type       = 'Revenue';
            $revenue->category   = $category->name;
            $revenue->user_id    = $revenue->customer_id;
            $revenue->user_type  = 'Customer';
           
          

            $customer         = Customer::where('id', $request->customer_id)->first();
            $payment          = new InvoicePayment();
            $payment->name    = !empty($customer) ? $customer['name'] : '';
            $payment->date    = \Auth::user()->dateFormat($request->date);
            $payment->amount  = \Auth::user()->priceFormat($request->amount);
            $payment->invoice = '';

            if(!empty($customer))
            {
                Utility::userBalance('customer', $customer->id, $revenue->amount, 'credit');
            }

            return response()->json(['success'=>'Revenue successfully created.']);
        }
        else
        {
           
            return response()->json(['error'=>'Permission denied.']);
        }
    }
    public function show()
    {
        return redirect()->route('revenue.index');
    }


    public function edit(Revenue $revenue)
    {
        if(\Auth::user()->can('edit revenue'))
        {
            $customers = Customer::get()->pluck('name', 'id');
            $customers->prepend('--', 0);
            $categories = ProductServiceCategory::where('type', '=', 'income')->get()->pluck('name', 'id');
          
            return view('revenue.edit', compact('customers', 'categories', 'revenue'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function update(Request $request, Revenue $revenue)
    {

        if(\Auth::user()->can('edit revenue'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'amount' => 'required',
                                   'category_id' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return response()->json(['error'=>$messages->first()]);
               
            }

            $customer = Customer::where('id', $request->customer_id)->first();
            if(!empty($customer))
            {
                Utility::userBalance('customer', $revenue->customer_id, $revenue->amount, 'debit');
            }

           

            if(!empty($customer))
            {
                Utility::userBalance('customer', $customer->id, $request->amount, 'credit');
            }

            
            $revenue->date           = $request->date;
            $revenue->amount         = $request->amount;
            $revenue->customer_id    = $request->customer_id;
            $revenue->category_id    = $request->category_id;
            $revenue->payment_method = 0;
            $revenue->reference      = $request->reference;
            $revenue->description    = $request->description;
           

            $revenue->save();

            return response()->json(['success'=>'Revenue Updated Successfully.']);
        }
        else
        {
            return response()->json(['error'=>'Permission denied.']);
        }
    }


    public function destroy(Revenue $revenue)
    {

        if(\Auth::user()->can('delete revenue'))
        {
           
               
                TransactionLines::where('reference_id',$revenue->id)->where('reference','Revenue')->delete();        
                $revenue->delete();
                $type = 'Revenue';
                $user = 'Customer';
                Transaction::destroyTransaction($revenue->id, $type, $user);

                if($revenue->customer_id != 0)
                {
                    Utility::userBalance('customer', $revenue->customer_id, $revenue->amount, 'debit');
                }


                Utility::bankAccountBalance($revenue->account_id, $revenue->amount, 'debit');

                return redirect()->route('revenue.index')->with('success', __('Revenue successfully deleted.'));
          
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
