<?php

namespace App\Http\Controllers;

use App\Events\TransactionEvent;
use App\Models\transaction;
use App\Models\TransactionMapAgent;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DataTables;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $transaction = transaction::where('reseller_id', $user_id)->latest()->take(10)->get();
        if (Auth::user()->role == 'agent') {
            return view('transaction-agent');
        } else if (Auth::user()->role == 'reseller') {
            return view('transaction-reseller', compact('transaction'));
        } else if (Auth::user()->role == 'admin') {
            $resellers = User::where('role', 'reseller')->get();
            $agents = User::where('role', 'agent')->get();
            return view('report', compact('resellers', 'agents'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {

            if (!Hash::check($request->pin, Auth::user()->pin)) {
                return back()->withError("Pin is not correct")->withInput();
            }
            $pendingTransaction = transaction::where('reseller_id', Auth::user()->id)->where('status', 'pending')->sum('amount');
            if ($pendingTransaction + $request->amount > Auth::user()->wallet) {
                return back()->withError("Transaction Not Created! Wallet Credit Exceeded")->withInput();

            }

            $transaction = new transaction();
            $transaction->reseller_id = auth()->user()->id;
            $transaction->amount = $request->amount;
            $transaction->type = $request->type;

            $transaction_no = date('dmYHis') . str_pad(auth()->user()->id, 4, "0", STR_PAD_LEFT);
            $transaction->transaction_no = $transaction_no;
            $transaction->account_type = $request->account_type;
            $transaction->mobile_number = $request->mobile_number;
            $transaction->service_charge = $request->service_charge ?: null;
            $transaction->save();
            event(new TransactionEvent());
            $this->send_message();
            return back()->withSuccess("Transaction Created Successfully!");
            //return view('transaction-reseller', compact($transaction));

        } catch (Throwable $th) {
            Log::error($th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getPassTranasction()
    {
        try {
            $userId = auth('sanctum')->user()->id;
            $existing_record = TransactionMapAgent::where('status', '!=', 'complete')->where('agent_id', $userId)->orderBy('pass_count', 'ASC')->first();
            if ($existing_record) {
                $transaction = transaction::find($existing_record->transaction_id);
                if ($transaction->status == 'pending') {
                    $transaction->status = 'locked';
                    $transaction->save();
                    TransactionMapAgent::where('transaction_id', $transaction->id)->where('agent_id', $userId)->update([
                        'status' => 'pending',
                    ]);
                    return $this->successJsonResponse("Transaction Information Found!", $transaction);
                }
            }
            return $this->errorJsonResponse("Transaction Information Not Found!!");

        } catch (Throwable $th) {
            return $this->exceptionJsonResponse($th);
        }
    }

    public function getNewTransaction()
    {
        try {
            $userId = auth('sanctum')->user()->id;
            $existing_record = TransactionMapAgent::where('status', 'pending')->where('agent_id', $userId)->first();
            $passedTransaction = TransactionMapAgent::select('transaction_id')
                ->where('status', 'passed')
                ->where('agent_id', $userId)
                ->get()
                ->toArray();
            if ($existing_record) {

                $transaction = transaction::where('id', $existing_record->transaction_id)->first();
            } else {
                $transaction = transaction::whereNotIn('id', $passedTransaction)->where('status', 'pending')->latest()->first();
            }
            if ($transaction) {
                if (!$existing_record) {
                    TransactionMapAgent::create([
                        'transaction_id' => $transaction->id,
                        'agent_id' => $userId,
                        'status' => 'pending',
                    ]);
                    transaction::where('id', $transaction->id)->update([
                        'status' => 'locked',
                    ]);
                }

                return $this->successJsonResponse("Transaction Information Found!", $transaction);
            } else {
                return $this->errorJsonResponse("Transaction Information Not Found!!", $transaction);
            }
        } catch (Throwable $th) {
            return $this->exceptionJsonResponse($th);
        }
    }

    public function saveErrorTransaction(Request $request)
    {
        try {
            $userId = auth('sanctum')->user()->id;
            $transactionId = $request->transactionId;
            $error_messaege = $request->error_message;
            $transaction = transaction::find($transactionId);
            $transaction->error_message = $error_messaege;
            $transaction->status = 'error';
            $transaction->save();
            TransactionMapAgent::where('transaction_id', $transactionId)->where('agent_id', $userId)->update([
                'status' => 'error',
            ]);

            return $this->successJsonResponse("Transaction Information Updated!", $transaction);
        } catch (Throwable $th) {
            return $this->exceptionJsonResponse($th);
        }
    }
    public function saveTransaction(Request $request)
    {

        try {

            $userId = auth('sanctum')->user()->id;
            $transactionId = $request->transactionId;
            $tranasactionNo = $request->transactionNo;
            $transaction = transaction::find($transactionId);
            $transaction->status = 'complete';
            $transaction->agent_id = $userId;
            $transaction->transaction_id = $tranasactionNo;
            $transaction->last_four_digit = $request->lastFourDigit;

            $agent_profit = ($transaction->amount * 0.004) * 0.5;
            $admin_profit = (($transaction->amount * 0.004) * 0.5) + ($transaction->amount * 0.025);
            $transaction->agent_profit = $agent_profit;
            $transaction->admin_profit = $admin_profit;
            $transaction->transaction_date = Carbon::now()->setTimezone('Asia/Dhaka');
            $transaction->save();
            TransactionMapAgent::where('transaction_id', $transactionId)->where('agent_id', $userId)->update([
                'status' => 'complete',
            ]);
            $user = User::find($userId);
            $user->wallet = $user->wallet + $transaction->amount;
            $user->save();

            $reseller = User::find($transaction->reseller_id);
            $reseller->wallet = $reseller->wallet - $transaction->amount;
            $reseller->save();
            event(new TransactionEvent());

            return $this->successJsonResponse("Transaction Information Updated!", $transaction);
        } catch (Throwable $th) {
            Log::info($th);
            return $this->exceptionJsonResponse($th);
        }
    }

    public function passTransaction(Request $request)
    {
        try {
            $userId = auth('sanctum')->user()->id;
            $transactionId = $request->transactionId;
            $transaction = transaction::find($transactionId);
            $transaction->status = 'pending';
            $transaction->save();
            $transactionMapAgent = TransactionMapAgent::where('transaction_id', $transactionId)->where('agent_id', $userId)->first();
            $currentCount = $transactionMapAgent->pass_count;
            TransactionMapAgent::where('transaction_id', $transactionId)->where('agent_id', $userId)->update([
                'status' => 'passed',
                'pass_count' => $currentCount + 1,
            ]);
            return $this->successJsonResponse("Transaction Information Passed!", $transaction);
        } catch (Throwable $th) {
            return $this->exceptionJsonResponse($th);
        }
    }

    public function getAllTransaction()
    {
        $userId = auth('sanctum')->user()->id;
        try {
            $transaction = TransactionMapAgent::where('agent_id', $userId)->with('transaction')->latest()->get()->toArray();
            return $this->successJsonResponse("Transaction Information Updated!", $transaction);
        } catch (Throwable $th) {
            return $this->exceptionJsonResponse($th);
        }
    }

    public function report()
    {
        $resellers = User::where('role', 'reseller')->get();
        $agents = User::where('role', 'agent')->get();
        return view('report', compact('resellers', 'agents'));
    }
    public function get_all_report(Request $request)
    {

        $start_date = Carbon::parse($request->start_date)->toDateTimeString();
        $end_date = Carbon::parse($request->end_date)->addDays(1)->toDateTimeString();
        $reseller_id = $request->retailer_id;
        $agent_id = $request->agent_id;
        $total_cost = 0;
        if ($request->ajax()) {
            if ($reseller_id) {
                $data = transaction::where('reseller_id', $reseller_id)->whereBetween('created_at', [$start_date, $end_date])->latest()->get(['*']);
            } else if ($agent_id) {
                $data = transaction::where('agent_id', $agent_id)->whereBetween('created_at', [$start_date, $end_date])->latest()->get(['*']);
            } else if ($reseller_id && $agent_id) {
                $data = transaction::where('reseller_id', $reseller_id)->where('agent_id', $agent_id)->whereBetween('created_at', [$start_date, $end_date])->latest()->get(['*']);
            } else {
                if (Auth::user()->role == 'admin') {
                    $data = transaction::whereBetween('created_at', [$start_date, $end_date])->latest()->get(['*']);
                } else if (Auth::user()->role == 'reseller') {
                    $data = transaction::where('reseller_id', Auth::user()->id)->whereBetween('created_at', [$start_date, $end_date])->latest()->get(['*']);
                } else {
                    $data = transaction::where('agent_id', Auth::user()->id)->whereBetween('created_at', [$start_date, $end_date])->latest()->get(['*']);
                }
            }
            $total_cost = $data->where('status', '!=', 'error')->sum('amount');
            $total_service_charge = $data->where('status', '!=', 'error')->sum('service_charge');
            $total_agent_profit = $data->where('status', '!=', 'error')->sum('agent_profit');
            $total_admin_profit = $data->where('status', '!=', 'error')->sum('admin_profit');
            if (sizeof($data) > 0) {
                $data[0]['total_cost'] = round($total_cost, 2);
                $data[0]['total_service_charge'] = round($total_service_charge, 2);
                $data[0]['total_agent_profit'] = round($total_agent_profit, 2);
                $data[0]['total_admin_profit'] = round($total_admin_profit, 2);
            }
            return Datatables::of($data)

                ->addIndexColumn()

                ->addColumn('reseller_name', function ($data) {
                    return $data->reseller ? $data->reseller->first_name . " " . $data->reseller->last_name : '';
                })

                ->addColumn('agent_name', function ($data) {
                    return $data->agent ? $data->agent->first_name . " " . $data->agent->last_name : '';
                })

                ->addColumn('date', function ($data) {
                    return Carbon::parse($data->created_at)->format('d-m-Y H:i:s');
                })
                ->rawColumns(['reseller_name', 'agent_name'])
                ->make(true);
        }
    }
    public function changeStatus(Request $request)
    {

        $user = User::find($request->user_id);
        $user->status = $request->status;
        $user->save();

        return response()->json(['message' => 'success']);
    }

    public function deleteTransaction(Request $request)
    {
        $transaction = transaction::find($request->id);
        if ($transaction->status == 'locked') {
            return response()->json(['message' => 'Transaction can not be deleted due to locked state', 'status' => false]);
        }
        $transaction->delete();
        return response()->json(['message' => 'Transaction deleted successfully', 'status' => true]);
    }

    public function invoice($invoiceId)
    {
        $data = transaction::find($invoiceId);
        return view('invoice', compact('data'));
    }

    public function general_notification_count()
    {
        $data = 0;
        if (auth()->user()->role == 'reseller') {
            $data = transaction::where('reseller_id', auth()->user()->id)->where('status', 'pending')->count();
        } else if (auth()->user()->role == 'agent') {
            $data = transaction::where('status', 'pending')->count();
        } else if (auth()->user()->role == 'admin') {
            $data = transaction::where('status', 'pending')->count();
        }
        return $data;
    }

    public function send_message()
    {
        $mobile_numbers = ['8801845318609,8801886318609'];

        $url = "http://g.dianasms.com/smsapi";
        $data = [
            "api_key" => "C200034363dd56240c1351.59351193",
            "type" => "text",
            "contacts" => "8801908376350",
            "senderid" => "8809601001329",
            "msg" => "You have one transaction srequest",
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        Log::info($response);
        curl_close($ch);

        //return $response;

    }

    // function send_sms() {
    //     $url = "http://g.dianasms.com/smsapi";
    //     $data = [
    //       "api_key" => "your_api-key",
    //       "type" => "{content type}",
    //       "contacts" => "88017xxxxxxxx+88018xxxxxxxx"
    //       "senderid" => "{sender id}",
    //       "msg" => "{your message}",
    //     ];
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_POST, 1);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //     $response = curl_exec($ch);
    //     curl_close($ch);
    //     return $response;
    //   }
}
