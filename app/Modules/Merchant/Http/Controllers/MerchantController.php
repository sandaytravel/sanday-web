<?php

namespace App\Modules\Merchant\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Mail;
use App\Activity;
use App\ActivityPackageOptions;
use App\User;
use App\Orders;
use App\Notification;
use App\EmailTemplate;
use App\Pendingrefunds;
use PDF;
use Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MerchantController extends Controller {
    /*
     * Merchant Dashboard
     */

    public function dashboard() {
        if (Auth::user()->can('merchant', 'read') && Auth::user()->role_id == 3) {
            $publish_activity = Activity::where(['is_delete' => 0, "admin_approve" => 1, "status" => "active", 'merchant_id' => Auth::user()->id])->take(5)->get();
            $pending_activity = Activity::where(['is_delete' => 0, "admin_approve" => 0, "status" => "active", 'merchant_id' => Auth::user()->id])->take(5)->get();
            $decline_activity = Activity::where(['is_delete' => 0, "admin_approve" => 2, "status" => "active", 'merchant_id' => Auth::user()->id])->take(5)->get();
            $merchantId = Auth::user()->id;
            $pendingBooking = Orders::with(['activity'])->whereHas('activity', function ($query) use($merchantId) {
                        $query->where('merchant_id', $merchantId);
                    })
                    ->where('status', 0)
                    ->count();
            $canceledBooking = Orders::with(['activity'])->whereHas('activity', function ($query) use($merchantId) {
                        $query->where('merchant_id', $merchantId);
                    })
                    ->where('status', 1)
                    ->count();
            $confirmedBooking = Orders::with(['activity'])->whereHas('activity', function ($query) use($merchantId) {
                        $query->where('merchant_id', $merchantId);
                    })
                    ->where('status', 2)
                    ->count();
            $totalSales = Orders::with(['activity'])->whereHas('activity', function ($query) use($merchantId) {
                        $query->where('merchant_id', $merchantId);
                    })
                    ->where('status', 2)
                    ->sum('order_total');
            return View('merchant::dashboard', compact('publish_activity', 'pending_activity', 'decline_activity', 'pendingBooking', 'canceledBooking', 'confirmedBooking', 'totalSales'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('dashboard');
        }
    }

    /*
     * Merchant Booking List
     */

    public function bookingList(Request $request) {
//        echo QrCode::format('png')->size(175)->generate($order->voucher_number);exit;
        if (Auth::user()->can('merchant', 'read') && Auth::user()->role_id == 3) {
            $merchantId = Auth::user()->id;
            $bookings = Orders::with(['activity', 'oredr_ietms.activitypackageoptions', 'oredr_ietms.packagequantity', 'user'])->whereHas('activity', function ($query) use($merchantId) {
                        $query->where('merchant_id', $merchantId);
                    })
                    ->where('order_payment_status', 'Completed')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(10);
            $merchantActivities = Activity::where('merchant_id', $merchantId)->where('is_delete', 0)->pluck('title', 'id')->toArray();
            $packageLists = ActivityPackageOptions::where('is_delete', 0)->whereHas('activity', function ($query) use($merchantId) {
                        $query->where('merchant_id', $merchantId);
                    })->pluck('package_title', 'id')->toArray();
            return view("merchant::mybookings", compact('bookings', 'merchantActivities', 'packageLists'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('dashboard');
        }
    }

    /*
     * Filter booking
     * 
     */

    public function searchBookingList(Request $request) {
        if (Auth::user()->can('merchant', 'read') && Auth::user()->role_id == 3) {
            $merchantId = Auth::user()->id;
            $query = Orders::with(['activity', 'oredr_ietms.activitypackageoptions', 'oredr_ietms.packagequantity', 'user'])->whereHas('activity', function ($query) use($merchantId) {
                $query->where('merchant_id', $merchantId);
            });
            $from_date = $to_date = $booking_status = $activity = $package = $reference_number = $traveler_name = "";
            /* Booking Date Range */
            if (isset($request->from_date) && isset($request->to_date)) {
                $from_date = $request->from_date;
                $to_date = $request->to_date;
                $query = $query->whereBetween('booking_date', [$request->from_date . " 00:00:00", $request->to_date . " 23:59:59"]);
            }
            /* Booking Status */
            if (isset($request->booking_status) && $request->booking_status != "") {
                $booking_status = $request->booking_status;
                $query = $query->where('status', $request->booking_status);
            }
            /* Activity Name */
            if (isset($request->activity) && $request->activity != "") {
                $activity = $request->activity;
                $query = $query->where('activity_id', $request->activity);
            }
            /* Package Name */
            if (isset($request->pacakge) && $request->pacakge != "") {
                $package = $request->pacakge;
                $query = $query->whereHas('oredr_ietms', function ($q) use($package) {
                    $q->where('package_id', $package);
                });
            }
            /* Reference Number */
            if (isset($request->reference_number) && $request->reference_number != "") {
                $reference_number = $request->reference_number;
                $query = $query->where('order_number', $request->reference_number);
            }
            /* Traveler Name [ Customer Name ] */
            if (isset($request->traveler_name) && $request->traveler_name != "") {
                $traveler_name = $request->traveler_name;
                $query = $query->whereHas('user', function ($q) use($traveler_name) {
                    $q->where('name', 'LIKE', "%" . $traveler_name . "%");
                });
            }
            $bookings = $query->where('order_payment_status', 'Completed')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(10);

            $bookings->appends(['from_date' => $from_date, 'to_date' => $to_date, 'booking_status' => $booking_status, 'activity' => $activity, 'package' => $package, 'reference_number' => $reference_number, 'traveler_name' => $traveler_name])->render(); // Append query string to URL
            $merchantActivities = Activity::where('merchant_id', $merchantId)->where('is_delete', 0)->pluck('title', 'id')->toArray();
            /* Filter */
            if ($request->button == "search" || !isset($request->button)) {
                $packageLists = ActivityPackageOptions::where('is_delete', 0)->whereHas('activity', function ($query) use($merchantId) {
                            $query->where('merchant_id', $merchantId);
                        })->pluck('package_title', 'id')->toArray();
                return view("merchant::mybookings", compact('bookings', 'from_date', 'to_date', 'booking_status', 'activity', 'package', 'reference_number', 'traveler_name', 'merchantActivities', 'packageLists'));
            } else { /* Export */
                if (count($bookings)) {
                    return Excel::create('BookingReport', function($excel) use ($bookings) {
                                $excel->sheet('BookingReport', function($sheet) use ($bookings) {
                                    $sheet->loadView('merchant::booking_excel_report')->with('bookings', $bookings);
                                    $sheet->setOrientation('landscape');
                                });
                            })->download("xls");
                } else {
                    Session::flash('error', 'No booking found to export');
                    return redirect()->route('booking-list');
                }
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('dashboard');
        }
    }

    /*
     * Confirm Booking  
     */

    public function confirmBooking(Request $request) {
        if (Auth::user()->can('merchant', 'write') && Auth::user()->role_id == 3) {
            $merchantId = Auth::user()->id;
            $order = Orders::with(['activity.city', 'oredr_ietms.activitypackageoptions', 'oredr_ietms.packagequantity', 'user'])->whereHas('activity', function ($query) use($merchantId) {
                        $query->where('merchant_id', $merchantId);
                    })->where('id', $request->id)->first();
            if ($order != null) {
                $order->status = 2;
                $order->status_date = date("Y-m-d");
                $order->voucher_number = "SAN" . date('dm') . rand('6', '999999');
                $order->save();
                $message = "Your " . $order->activity->title . " has been  confirmed for " . $order->booking_date . ".";
                $notification = new Notification;
                $notification->sender_id = $order->customer_id;
                $notification->receiver_id = $order->activity->merchant_id;
                $notification->message = $message;
                $notification->type = 2;
                $notification->created_at = date('Y-m-d H:i:s', time());
                $notification->updated_at = date('Y-m-d H:i:s', time());
                $notification->save();
                $this->sendcustomerNotification($order->id, $order->customer_id, $message);
                $this->sendBookingConfirmEmail($order);
                Session::flash('success', 'Booking has been confirmed successfully');
                return redirect()->route('booking-list');
            } else {
                Session::flash('error', 'No order found in system');
                return redirect()->route('booking-list');
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('dashboard');
        }
    }

    public function sendBookingConfirmEmail($order) {
        $html = view('merchant::confirm_booking_pdf', compact("order"))->render();
        PDF::loadHTML($html)->save(public_path('/vouchers/' . $order->order_number . '.pdf'))->stream($order->order_number . '.pdf');
        $unit = "";
        if (count($order->oredr_ietms)) {
            foreach ($order->oredr_ietms as $key => $value) {
                $unit .= "Unit:" . $value->quantity . " x " . $value->packagequantity->name . "<br/>";
            }
        }
        /* Sending booking voucher to customer */
        $data = [
            "customer" => $order->user->name,
            "email" => (isset($order->user->voucher_email)) ? $order->user->voucher_email : $order->user->email,
            "activity" => $order->activity->title,
            "order_number" => $order->order_number,
            "booking_date" => date("d M Y", strtotime($order->created_at)),
            "traveler_name" => $order->user->title . " " . $order->user->name,
            "package_name" => $order->oredr_ietms[0]->activitypackageoptions->package_title,
            "participation_date" => date("d M Y", strtotime($order->booking_date)),
            "unit" => $unit,
            "image_url" => '<img alt="" class="CToWUd a6T" src="' . url("/public/img/activity/fullsized/" . $order->activity->image) . '" style="height:96px; margin-right:20px; width:120px" />',
            'city' => (isset($order->activity->city)) ? $order->activity->city->city : ""
        ];
        $template = EmailTemplate::where('name', 'confirm-booking')->first();
        Mail::send([], [], function($messages) use ($template, $data) {
            $dataToParse = [
                'customer' => $data['customer'],
                'activity_1' => $data['activity'],
                'activity_2' => $data['activity'],
                'order_number' => $data['order_number'],
                'booking_date' => $data['booking_date'],
                'traveler_name' => $data['traveler_name'],
                'package_name' => $data['package_name'],
                'participation_date' => $data['participation_date'],
                'unit' => $data['unit'],
                'image_url' => $data['image_url']
            ];
            $messages->to($data['email'], $data['customer'])
                    ->subject($template->subject . " - " . $data['activity'] . " - " . $data['participation_date'] . " - " . $data['traveler_name'] . " - [" . $data['city'] . ']')
                    ->setBody($template->parse($dataToParse), 'text/html')
                    ->attach(\Swift_Attachment::fromPath(public_path('/vouchers/' . $data['order_number'] . '.pdf')));
        });
        unlink(public_path('/vouchers/' . $data['order_number'] . '.pdf'));
        return;
    }

    /*
     * Send Notification
     * 
     */

    public function sendcustomerNotification($orderId, $userId, $message) {
        $receiver = User::where('id', $userId)->first();
        if ($receiver) {
            if ($receiver->device_type == 'android') {
                if ($receiver->device_token != null) {
                    $url = "https://fcm.googleapis.com/fcm/send";
                    $token = $receiver->device_token;
                    // $token = 'e98ZPKGevYY:APA91bHuXdazRNWocfc3iv6NiFfCfpzdllFHEqLr4Xw_WsoOmEWCBBNs4fm9tT0C8kHk_jySt6cwqGASn5Y4gRJBjd8TsriKaBISyk2smrTHs_lccIns8_ZL030wsW25lhwdp-jDW40P';
                    $serverKey = 'AAAAkyjOja0:APA91bG6RGN2auoxEd_JJqapCcREjVki_ytvmcLRJIkP36ioVa9FpbX2-6oIqD-sIIU2sv-JtQGqatTAuhKfHAfQD25K2enkMTY0Uk0OEFtMeoiQKPseRw5Uh-kk80G3B-xBc3R8vNfV';
                    $body = $message;
                    $notification = array('body' => $body, 'sound' => 'default', 'badge' => '1');
                    $arrayToSend = array('to' => $token, 'notification' => $notification, 'priority' => 'high');
                    $json = json_encode($arrayToSend);
                    $headers = array();
                    $headers[] = 'Content-Type: application/json';
                    $headers[] = 'Authorization: key=' . $serverKey;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    //Send the request
                    $response = curl_exec($ch);
                    //Close request
                    if ($response === FALSE) {
                        die('FCM Send Error: ' . curl_error($ch));
                    }
                    curl_close($ch);
                }
            }
        }
        return;
    }

    /*
     * Cancel Booking  
     */

    public function cancelBooking(Request $request) {
        if (Auth::user()->can('merchant', 'write') && Auth::user()->role_id == 3) {
            $merchantId = Auth::user()->id;
            $order = Orders::with(['transaction', 'activity.city', 'oredr_ietms.activitypackageoptions', 'oredr_ietms.packagequantity', 'user'])->whereHas('activity', function ($query) use($merchantId) {
                        $query->where('merchant_id', $merchantId);
                    })->where('id', $request->id)->first();
            if ($order != null) {
                $order->status = 1;
                $order->status_date = date("Y-m-d");
                $order->save();
                $message = "Your " . $order->activity->title . " has been  cancelled for " . $order->booking_date . ".";
                $notification = new Notification;
                $notification->sender_id = $order->customer_id;
                $notification->receiver_id = $order->activity->merchant_id;
                $notification->message = $message;
                $notification->type = 1;
                $notification->created_at = date('Y-m-d H:i:s', time());
                $notification->updated_at = date('Y-m-d H:i:s', time());
                $notification->save();
                $this->sendcustomerNotification($order->id, $order->customer_id, $message);
                $this->sendBookingCancelEmail($order);
                $this->saveRefundEntry($order->id, $order->customer_id);
                Session::flash('success', 'Booking has been cancelled successfully');
                return redirect()->route('booking-list');
            } else {
                Session::flash('error', 'No order found in system');
                return redirect()->route('booking-list');
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('dashboard');
        }
    }

    public function saveRefundEntry($orderId, $customerId) {
        $refund = new Pendingrefunds();
        $refund->order_id = $orderId;
        $refund->customer_id = $customerId;
        $refund->created_at = date('Y-m-d H:i:s', time());
        $refund->updated_at = date('Y-m-d H:i:s', time());
        $refund->save();
        return;
    }

    public function sendBookingCancelEmail($order) {
        $unit = "";
        if (count($order->oredr_ietms)) {
            foreach ($order->oredr_ietms as $key => $value) {
                $unit .= "Unit:" . $value->quantity . " x " . $value->packagequantity->name . "<br/>";
            }
        }
        $data = [
            "transaction_id" => $order->transaction->transaction_number,
            "customer" => $order->user->name,
            "email" => $order->user->email,
            "admin_email" => env("ADMIN_EMAIL"),
            "activity" => $order->activity->title,
            "order_number" => $order->order_number,
            "booking_date" => date("d M Y", strtotime($order->created_at)),
            "traveler_name" => $order->user->title . " " . $order->user->name,
            "package_name" => $order->oredr_ietms[0]->activitypackageoptions->package_title,
            "participation_date" => date("d M Y", strtotime($order->booking_date)),
            "unit" => $unit,
            "image_url" => '<img alt="" class="CToWUd a6T" src="' . url("/public/img/activity/fullsized/" . $order->activity->image) . '" style="height:96px; margin-right:20px; width:120px" />',
            'city' => (isset($order->activity->city)) ? $order->activity->city->city : ""
        ];
        $template = EmailTemplate::where('name', 'cancel-booking')->first();
        Mail::send([], [], function($messages) use ($template, $data) {
            $dataToParse = [
                'customer' => $data['customer'],
                'activity_1' => $data['activity'],
                'activity_2' => $data['activity'],
                'order_number' => $data['order_number'],
                'booking_date' => $data['booking_date'],
                'traveler_name' => $data['traveler_name'],
                'package_name' => $data['package_name'],
                'participation_date' => $data['participation_date'],
                'unit' => $data['unit'],
                'image_url' => $data['image_url']
            ];
            $messages->to($data['email'], $data['customer'])
                    ->subject($template->subject . " - " . $data['activity'] . " - " . $data['participation_date'] . " - " . $data['traveler_name'] . " - [" . $data['city'] . ']')
                    ->setBody($template->parse($dataToParse), 'text/html');
        });
        $template2 = EmailTemplate::where('name', 'customer-order-refund')->first();
        Mail::send([], [], function($messages) use ($template2, $data) {
            $dataToParse = [
                'activity' => $data['activity'],
                'traveler_name' => $data['traveler_name'],
                'transaction_id' => $data['transaction_id']
            ];
            $messages->to($data['admin_email'], "Admin")
                    ->subject($template2->subject . " - " . $data['activity'] . " - " . $data['participation_date'] . " - " . $data['traveler_name'] . " - [" . $data['city'] . ']')
                    ->setBody($template2->parse($dataToParse), 'text/html');
        });
        return true;
    }

    /*
     * Merchant Update Prfoile
     */

    public function updateProfile(Request $request) {
        if (Auth::user()->can('merchant', 'write') && Auth::user()->role_id == 3) {
            if (is_numeric($request->id)) {
                if ($request->isMethod('POST')) {
                    $country = DB::table('profile_country')->where('id', $request->country)->first();
                    $user = User::where('id', $request->id)->first();
                    $user->role_id = 3;
                    $user->name = $request->name;
                    $user->company_name = $request->companyname;
                    $user->email = $request->email;
                    $user->mobile_number = $request->phone;
                    if ($country) {
                        $user->country_name = $country->nicename;
                        $user->country_code = "+" . $country->phonecode;
                    }
                    $user->city_name = $request->city;
                    $user->website = $request->website;
                    $user->sst_certificate = $request->sst_certificate;
                    $user->status = $request->status;
                    $user->updated_at = date('Y-m-d H:i:s', time());
                    $user->save();
                    Session::flash('success', 'Your profile has been updated successfully.');
                    return redirect()->route('merchantdashboard');
                } else {
                    $user = User::where('id', $request->id)->first();
                    if ($user) {
                        $countries = DB::table('profile_country')->pluck('nicename')->toArray();
                        return view('merchant::updateprofile', compact('user', 'countries'));
                    } else {
                        Session::flash('error', 'No such merchant found.');
                        return redirect()->route('merchantdashboard');
                    }
                }
            } else {
                return view('errors.404');
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('dashboard');
        }
    }

    public function viewBooking(Request $request) {
        if (Auth::user()->can('merchant', 'read') && Auth::user()->role_id == 3) {
            $merchantId = Auth::user()->id;
            $booking = Orders::with(['activity', 'oredr_ietms.activitypackageoptions', 'oredr_ietms.packagequantity', 'user'])->whereHas('activity', function ($query) use($merchantId) {
                        $query->where('merchant_id', $merchantId);
                    })
                    ->where('id', $request->id)
                    ->first();
            if (count($booking)) {
                $html = view('merchant::view_order', compact('booking'))->render();
                return response()->json([
                            'code' => 200,
                            'html' => $html
                ]);
            } else {
                return response()->json([
                            'code' => 401,
                            'html' => ""
                ]);
            }
        } else {
            return response()->json([
                        'code' => 500,
                        'html' => ""
            ]);
        }
    }

    /*
     * Merchant Change Password
     */

    public function changePassMerchant(Request $request) {
        if (Auth::user()->can('merchant', 'write') && Auth::user()->role_id == 3) {
            $user = User::where('id', Auth::user()->id)->first();
            if ($request->isMethod('POST')) {
                if (!\Hash::check($request->oldpassword, $user->password)) {
                    Session::flash('error', 'Old password is wrong.');
                    return redirect()->route('mchange-password');
                } else {
                    $newPassword = \Hash::make($request->password);
                    $user->password = $newPassword;
                    $user->updated_at = date('Y-m-d H:i:s', time());
                    $user->save();
                    Session::flash('success', 'Your password has been changed successfully.');
                    return view('merchant::change_password', compact('user'));
                }
            } else {
                return view('merchant::change_password');
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('dashboard');
        }
    }

}
