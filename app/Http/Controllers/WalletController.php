<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Auth;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class WalletController extends Controller
{
    //
    public function index()
    {
        return view('wallet-request');
    }

    public function get_wallet_data_send()
    {
        if (Auth::user()->role == 'admin') {
            $data = Wallet::latest()->get();
        } else if (Auth::user()->role == 'reseller') {
            $data = Wallet::where('reseller_id', Auth::user()->id)->latest()->get();
        } else {
            $data = Wallet::where('reseller_id', 2)->latest()->get();
        }

        return Datatables::of($data)

            ->addIndexColumn()

            ->addColumn('reseller_name', function ($data) {
                if (Auth::user()->id == $data->reseller_id) {
                    return '';
                }
                return $data->user ? $data->user->first_name . " " . $data->user->last_name : '';

            })

            ->addColumn('request_date', function ($data) {
                return Carbon::parse($data->created_at)->format('d-m-Y H:i:s');

            })

            ->addColumn('accepted_date', function ($data) {
                if ($data->accepted_date) {
                    return Carbon::parse($data->accepted_date)->format('d-m-Y H:i:s');
                } else {
                    return 'Pending';
                }

            })
            ->addColumn('status', function ($data) {
                $status = $data->status == "pending" ? "badge-warning" : ($data->status == "confirm" ? "badge-success" : "badge-danger");
                $text = '<label class="badge ' . $status . '">' . $data->status . '</label>';
                return $text;

            })

            ->addColumn('request_type', function ($data) {

                if (Auth::user()->id == $data->reseller_id) {
                    $status = "badge-danger";
                    $type = "Send";
                } else {
                    $status = "badge-success";
                    $type = "Receive";
                }

                $text = '<label class="badge ' . $status . '">' . $type . '</label>';
                return $text;;

            })

            ->addColumn('document', function ($data) {
                $link = '<a href="storage/' . $data->document . '">File</a>';
                return $link;

            })

            ->addColumn('action', function ($data) {
                if (Auth::user()->id == $data->reseller_id) {
                    $button = '';
                } else if ($data->status != 'pending') {
                    $button = '';
                } else {
                    $button = '
                <button type="button" onclick="accept_request(' . $data->id . ')" class="btn btn-outline-secondary btn-rounded btn-icon">
                    <i class="fas fa-check text-success"></i>
                 </button>
                <button type="button" onclick="decline_request(' . $data->id . ')" class="btn btn-outline-secondary btn-rounded btn-icon">
                    <i class="fas fa-trash text-danger"></i>
                 </button>';
                }
                return $button;

            })
            ->rawColumns(['reseller_name', 'request_date', 'document', 'accepted_date', 'status', 'action', 'request_type'])
            ->make(true);
    }

    public function submit_wallet_request(Request $request)
    {

        $path = $request->document->store('image/paymentSlip', 'public');
        try {
            $wallet_request = Wallet::create([
                'reseller_id' => Auth::user()->id,
                'amount' => $request->amount,
                'document' => $path,

            ]);
        } catch (Throwable $th) {
            Log::error($th);
        }
    }

    public function accept_wallet_request(Request $request)
    {
        try {

            $wallet = Wallet::find($request->id);
            $wallet->status = 'confirm';
            $wallet->accepted_date = Carbon::now();
            $wallet->save();

            $user = User::find($wallet->reseller_id);

            if (Auth::user()->role == 'agent') {
                $user->wallet = $user->wallet - $wallet->amount;
            } else {
                $user->wallet = $user->wallet + $wallet->amount;
            }
            $user->save();

        } catch (Throwable $th) {
            Log::error($th);
        }
    }

    public function decline_wallet_request(Request $request)
    {
        try {
            Wallet::where('id', $request->id)->update([
                'status' => 'declined',
                'accepted_date' => Carbon::now(),
            ]);
        } catch (Throwable $th) {

        }
    }
}
