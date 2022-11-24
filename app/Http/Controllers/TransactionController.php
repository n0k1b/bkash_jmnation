<?php

namespace App\Http\Controllers;

use App\Models\transaction;
use App\Models\TransactionMapAgent;
use Illuminate\Http\Request;
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
        //
        return view('transaction');
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

    public function getNewTransaction()
    {
        try {
            $userId = 2;
            $existing_record = TransactionMapAgent::where('status', 'pending')->where('agent_id', $userId)->first();
            if ($existing_record) {
                $transaction = transaction::where('id', $existing_record->transaction_id)->first();
            } else {
                $transaction = transaction::where('status', 'pending')->latest()->first();
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
                return $this->successJsonResponse("Transaction Information Found!!", $transaction);
            }

        } catch (Throwable $th) {
            return $this->exceptionJsonResponse($th);
        }
    }
    public function saveTransaction(Request $request)
    {

        try {
            $userId = 2;
            $transactionId = $request->transactionId;
            $tranasactionNo = $request->transactionNo;
            $transaction = Transaction::find($transactionId);
            $transaction->status = 'complete';
            $transaction->transaction_id = $tranasactionNo;
            $transaction->save();
            TransactionMapAgent::where('transaction_id', $transactionId)->where('agent_id', $userId)->update([
                'status' => 'complete',
            ]);
            return $this->successJsonResponse("Transaction Information Updated!", $transaction);
        } catch (Throwable $th) {
            return $this->exceptionJsonResponse($th);
        }

    }

    public function getAllTransaction()
    {
        $userId = 2;
        try {
            $transaction = TransactionMapAgent::where('agent_id', $userId)->with('transaction')->get()->toArray();
            return $this->successJsonResponse("Transaction Information Updated!", $transaction);

        } catch (Throwable $th) {
            return $this->exceptionJsonResponse($th);
        }
    }
}
