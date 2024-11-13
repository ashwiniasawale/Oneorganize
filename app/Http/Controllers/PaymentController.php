<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BillAccount;
use App\Models\BillPayment;
use App\Models\ChartOfAccount;
use App\Models\Payment;
use App\Models\ProductServiceCategory;
use App\Models\Transaction;
use App\Models\TransactionLines;
use App\Models\Utility;
use App\Models\Vender;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function index(Request $request)
    {
        if (\Auth::user()->can('manage payment')) {
            $vender = Vender::get()->pluck('name', 'id');
            $vender->prepend('Select Vendor', '');

          
            $category = ProductServiceCategory::where('type', '=', 'expense')->get()->pluck('name', 'id');
            $category->prepend('Select Category', '');

            $query = Payment::query();

            if (count(explode('to', $request->date)) > 1) {
                $date_range = explode(' to ', $request->date);
                $query->whereBetween('date', $date_range);
            } elseif (!empty($request->date)) {
                $date_range = [$request->date, $request->date];
                $query->whereBetween('date', $date_range);
            }

            if (!empty($request->vender)) {
                $query->where('vender_id', '=', $request->vender);
            }
           

            if (!empty($request->category)) {
                $query->where('category_id', '=', $request->category);
            }

            $payments = $query->with(['vender','category'])->get();

            return view('payment.index', compact('payments', 'category', 'vender'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('create payment')) {
            $venders = Vender::get()->pluck('name', 'id');
            $venders->prepend('--', null);

//            $categories = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 2)->get()->pluck('name', 'id');
            $categories = ProductServiceCategory::whereNotIn('type', ['product & service', 'income'])
                ->get()->pluck('name', 'id');
            $categories->prepend('Select Category', '');

         
            return view('payment.create', compact('venders', 'categories'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('create payment')) {

            $validator = \Validator::make(
                $request->all(), [
                    'date' => 'required',
                    'amount' => 'required',
                   
                    'category_id' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return response()->json(['error'=>$messages->first()]);
            }

            $payment = new Payment();
            $payment->date = $request->date;
            $payment->amount = $request->amount;
          //  $payment->account_id = $request->account_id;
//            $payment->chart_account_id  = $request->chart_account_id;
            $payment->vender_id = $request->vender_id;
            $payment->category_id = $request->category_id;
            $payment->payment_method = 0;
            $payment->reference = $request->reference;
          
            $payment->description = $request->description;
            $payment->created_by = \Auth::user()->id;
            $payment->save();

         
            $category = ProductServiceCategory::where('id', $request->category_id)->first();
            $payment->payment_id = $payment->id;
            $payment->type = 'Payment';
            $payment->category = $category->name;
            $payment->user_id = $payment->vender_id;
            $payment->user_type = 'Vender';

           // Transaction::addTransaction($payment);

            $vender = Vender::where('id', $request->vender_id)->first();
            $payment = new BillPayment();
            $payment->name = !empty($vender) ? $vender['name'] : '';
            $payment->method = '-';
            $payment->date = \Auth::user()->dateFormat($request->date);
            $payment->amount = \Auth::user()->priceFormat($request->amount);
            $payment->bill = '';

            if (!empty($vender)) {
                Utility::userBalance('vendor', $vender->id, $request->amount, 'debit');
            }
            return response()->json(['success'=>'Payment successfully created.']);
           
        } else {
           
            return response()->json(['error'=>'Permission denied.']);
        }
    }

    public function edit(Payment $payment)
    {

        if (\Auth::user()->can('edit payment')) {
            $venders = Vender::get()->pluck('name', 'id');
            $venders->prepend('--', 0);

            $categories = ProductServiceCategory::whereNotIn('type', ['product & service', 'income'])
                ->get()->pluck('name', 'id');
            $categories->prepend('Select Category', '');
          

            return view('payment.edit', compact('venders', 'categories', 'payment'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Payment $payment)
    {
        if (\Auth::user()->can('edit payment')) {

            $validator = \Validator::make(
                $request->all(), [
                    'date' => 'required',
                    'amount' => 'required',
                   
                    'vender_id' => 'required',
                    'category_id' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return response()->json(['error'=>$messages->first()]);
                
            }
            $vender = Vender::where('id', $request->vender_id)->first();
            if (!empty($vender)) {
                Utility::userBalance('vendor', $vender->id, $payment->amount, 'credit');
            }
           
            $payment->date = $request->date;
            $payment->amount = $request->amount;
          
            $payment->vender_id = $request->vender_id;
            $payment->category_id = $request->category_id;
            $payment->payment_method = 0;
            $payment->reference = $request->reference;

          
            $payment->description = $request->description;
            $payment->save();

        
          
            if (!empty($vender)) {
                Utility::userBalance('vendor', $vender->id, $request->amount, 'debit');
            }

            return response()->json(['success'=>'Payment Updated Successfully.']);
           
        } else {
            return response()->json(['error'=>'Permission denied.']);
           
        }
    }

    public function destroy(Payment $payment)
    {
        if (\Auth::user()->can('delete payment')) {
       

                TransactionLines::where('reference_id', $payment->id)->where('reference', 'Payment')->delete();

                $payment->delete();
                $type = 'Payment';
                $user = 'Vender';
                Transaction::destroyTransaction($payment->id, $type, $user);

                if ($payment->vender_id != 0) {
                    Utility::userBalance('vendor', $payment->vender_id, $payment->amount, 'credit');
                }
                Utility::bankAccountBalance($payment->account_id, $payment->amount, 'credit');

                return redirect()->route('payment.index')->with('success', __('Payment successfully deleted.'));
           
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
