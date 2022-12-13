<?php

namespace App\Http\Controllers;

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
            $transaction->account_type = $request->account_type;
            $transaction->mobile_number = $request->mobile_number;
            $transaction->service_charge = $request->service_charge ?: null;
            $transaction->save();

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

    public function test()
    {
        return 'hello';
    }

    public function getPassTranasction()
    {
        try {
            $userId = auth('sanctum')->user()->id;
            $existing_record = TransactionMapAgent::where('status', '!=', 'complete')->where('agent_id', $userId)->where('pass_count', 1)->first();
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
            $transaction = TransactionMapAgent::where('agent_id', $userId)->with('transaction')->get()->toArray();
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
            $total_cost = $data->sum('amount');
            $total_service_charge = $data->sum('service_charge');
            $total_agent_profit = $data->sum('agent_profit');
            if (sizeof($data) > 0) {
                $data[0]['total_cost'] = round($total_cost, 2);
                $data[0]['total_service_charge'] = round($total_service_charge, 2);
                $data[0]['total_agent_profit'] = round($total_agent_profit, 2);
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
}
