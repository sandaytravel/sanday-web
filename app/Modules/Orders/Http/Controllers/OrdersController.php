<?php

namespace App\Modules\Orders\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Activity;
use App\Category;
use App\City;
use App\Transaction;
use App\Orders;
use App\Orderitems;
use Carbon\Carbon;
use Session;
use Auth;

class OrdersController extends Controller {

    //
    public function index(Request $request) {
        if (Auth::user()->can('orders', 'read') && Auth::user()->role_id == 1) {
            // $orderget = Orders::with('oredr_ietms.activitypackageoptions','oredr_ietms.packagequantity','activity','transaction','user')->orderBy('updated_at', 'DESC')->paginate(10);

            $orderget = Transaction::with('orders', 'orders.user')->orderBy('updated_at', 'DESC')->paginate(10);
            // echo '<pre>';
            // print_r($orderget->toArray());
            // echo '<pre>';
            // print_r($orderget->toArray());
            $orderstatus_list = array('0' => 'Pending',
                '1' => 'Canceled',
                '2' => 'Confirmed',
                '3' => 'Expired');
            $paymentstatus_list = array('Pending' => 'Pending',
                'Completed' => 'Completed',
                'Failed' => 'Failed');
            return view('orders::index', compact('orderget', 'orderstatus_list', 'paymentstatus_list'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /**
     * 
     * Serach Order number
     * 
     */
    public function searchOrder(Request $request) {
        if (Auth::user()->can('orders', 'read') && Auth::user()->role_id == 1) {
            $searchterm = $request->get('searchterm');
            $customer_id = $request->get('customerid');
            $transction_id = $request->get('transction_id');
            $order_id = $request->get('order_id');
            $orderstatus = $request->get('orderstatus');
            $payment_status = $request->get('payment_status');
            $parti_from_validity = $request->get('participate_from');
            $parti_to_validity = $request->get('participate_to');
            // $query = Orders::with('oredr_ietms.activitypackageoptions','oredr_ietms.packagequantity','activity','transaction','user');
            // if($request->get('transction_id') != null){
            //     $query->orWhereHas('transaction', function ($query) use($transction_id) {
            //         $query->where('transaction_number', 'LIKE', "%$transction_id%");
            //     });
            // }
            // if($request->get('order_id') != null){
            //     $query->where('order_number', $request->get('order_id'));
            // }
            // if($request->get('orderstatus') != null){
            //     $query->where('status', $request->get('orderstatus'));
            // }
            // if (isset($customer_id) && $customer_id != "" ) {
            //     $query->orWhereHas('user', function ($query) use($customer_id) {
            //         $query->where('id', "$customer_id");
            //     });
            // }
            // if (isset($searchterm) && $searchterm != "" ) {
            //     $query->orWhereHas('user', function ($query) use($searchterm) {
            //         $query->where('name', 'LIKE', "%$searchterm%");
            //     });
            // }
            // if (isset($parti_from_validity) && $parti_from_validity != "" && isset($parti_to_validity) && $parti_to_validity != "" ) {
            //     $query = $query->whereBetween('booking_date', [$parti_from_validity, $parti_to_validity]);
            // }
            // if(isset($parti_to_validity)|| empty($parti_to_validity)){
            //     if(isset($parti_from_validity) && $parti_from_validity != "" ){
            //         $query = $query->where('booking_date', '>=', $parti_from_validity);
            //     }
            // }
            // $orderget= $query->orderBy('updated_at', 'DESC')->paginate(10);
            $query = Transaction::with('orders', 'orders.user');
            if ($request->get('transction_id') != null) {
                $query->where('transaction_number', 'LIKE', "%$transction_id%");
            }
            if ($request->get('payment_status') != null) {
                $query->where('paymet_status', $request->get('payment_status'));
            }
            if ($request->get('order_id') != null) {
                $query->whereHas('orders', function ($query) use($order_id) {
                    $query->where('order_number', 'LIKE', "%$order_id%");
                });
            }
            if (isset($customer_id) && $customer_id != "") {
                $query->orWhereHas('orders.user', function ($query) use($customer_id) {
                    $query->where('id', "$customer_id");
                });
            }
            if (isset($parti_from_validity) && $parti_from_validity != "" && isset($parti_to_validity) && $parti_to_validity != "") {
                $parti_from_validity1 = $parti_from_validity . ' ' . date("h:i:s");
                $parti_to_validity1 = $parti_to_validity . ' ' . date("h:i:s");
                $start = Carbon::parse($parti_from_validity)->startOfDay();  //2016-09-29 00:00:00.000000
                $end = Carbon::parse($parti_to_validity)->endOfDay();
                $query = $query->whereBetween('created_at', [$start, $end]);
            }
            if (isset($parti_to_validity) || empty($parti_to_validity)) {
                if (isset($parti_from_validity) && $parti_from_validity != "") {
                    $query = $query->where('created_at', '>=', $parti_from_validity);
                }
            }
            $orderget = $query->orderBy('updated_at', 'DESC')->paginate(10);
            $orderget->appends(['transction_id' => $transction_id, 'order_id' => $order_id, 'orderstatus' => $orderstatus, 'participate_from' => $parti_from_validity, 'participate_to' => $parti_to_validity, 'customerid' => $customer_id, 'payment_status' => $payment_status])->render();
            // $orderget->appends(['searchterm' => $searchterm])->render(); // Append query string to URL
            $orderstatus_list = array('0' => 'Pending',
                '1' => 'Canceled',
                '2' => 'Confirmed',
                '3' => 'Expired');
            $paymentstatus_list = array('Pending' => 'Pending',
                'Completed' => 'Completed',
                'Failed' => 'Failed');
            // echo '<pre>';
            // print_r($orderget->toArray());
            return view('orders::index', compact('orderget', 'orderstatus_list', 'transction_id', 'order_id', 'orderstatus', 'paymentstatus_list', 'participate_from', 'participate_to'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Admin Order Status update
     * 
     *  
     */

    public function orderStatusUpdate(Request $request) {
        if (Auth::user()->can('orders', 'write') && Auth::user()->role_id == 1) {
            $order = Orders::where('id', $request->id)->first();
            if ($order != null) {
                // $dataArray = ["name" => $activity->merchant->name , "email" => $activity->merchant->email , "activityname" => $activity->title ,'resondecline' => $reasonDecline];
                if ($request->status == "pending") {
                    $order->status = '0';
                    // $template = EmailTemplate::where('name', 'approve-activity')->first();
                } elseif ($request->status == "canceled") {
                    $order->status = '1';
                    // $template = EmailTemplate::where('name', 'decline-activity')->first();
                } elseif ($request->status == "confirmed") {
                    $order->status = '2';
                    // $template = EmailTemplate::where('name', 'decline-activity')->first();
                } elseif ($request->status == "expired") {
                    $order->status = '3';
                    // $template = EmailTemplate::where('name', 'decline-activity')->first();
                }
                // Mail::send([], [], function($message) use ($template,$dataArray) {
                //     $data = [
                //         'toMerchantName' => $dataArray['name'],
                //         'activityName' => $dataArray['activityname'],
                //         'resonDecline' => $dataArray['resondecline'],
                //     ];
                //     $message->to($dataArray['email'], $dataArray['name'])
                //             ->subject($template->subject)
                //             ->setBody($template->parse($data), 'text/html');
                // });
                $order->save();

                Session::flash('success', 'Order has been ' . $request->status . '.');
                return redirect()->route('Orders');
            } else {
                Session::flash('error', 'Something went wrong please try again later.');
                return redirect()->route('Orders');
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /**
     * 
     * View Order Detail
     * 
     */
    public function orderView(Request $request) {
        if (Auth::user()->can('orders', 'read') && Auth::user()->role_id == 1) {
            // $orderView = Orders::with('oredr_ietms.activitypackageoptions','oredr_ietms.packagequantity','activity','transaction','user')->orderBy('updated_at', 'DESC')->where('id',$request->id)->first();
            $orderView = Transaction::with('orders', 'orders.user', 'orders.activity', 'orders.oredr_ietms.activitypackageoptions')->orderBy('updated_at', 'DESC')->where('id', $request->id)->first();
            if ($orderView != null) {
                // echo '<pre>';
                // print_r($orderView->toArray());
                // echo '</pre>';
                return view('orders::vieworder', compact('orderView'));
            } else {
                Session::flash('error', 'Something went wrong please try again later.');
                return redirect()->route('Orders');
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

}
