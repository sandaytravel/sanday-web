<?php

namespace App\Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use Session;
use App\User;
use App\Orders;
use App\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class UsersController extends Controller {

    protected $limit = 10;

    // Dashboard
    public function dashboard() {
        if (Auth::user()->role_id == 1) {
            $weekStartDate = Carbon::now()->subDay(0)->startOfWeek()->toDateString(); // or ->format(..)
            $weekEndDate = Carbon::now()->subDay(0)->toDateString();
            $usersCount = User::where('role_id', 2)
                    ->where('is_delete', 0)
                    ->whereBetween('created_at', [$weekStartDate . ' 00:00:00', $weekEndDate . ' 23:59:59'])
                    ->orderBy('id', 'DESC')
                    ->count();

            $customer = User::where('role_id', 2)
                    ->where('is_delete', 0)
                    ->orderBy('id', 'DESC')
                    ->take(5)
                    ->get();

            $ordersCount = Orders::whereBetween('created_at', [$weekStartDate . ' 00:00:00', $weekEndDate . ' 23:59:59'])->orderBy('id', 'DESC')
                    ->count();

            $orders = Orders::with('oredr_ietms.activitypackageoptions', 'oredr_ietms.packagequantity', 'activity', 'transaction', 'user')
                    ->orderBy('id', 'DESC')
                    ->get()
                    ->take(5);
            $activityCount = Activity::where('is_delete', 0)
                    ->whereBetween('created_at', [$weekStartDate . ' 00:00:00', $weekEndDate . ' 23:59:59'])
                    ->orderBy('id', 'DESC')
                    ->count();

            $totalRevenue = Orders::whereBetween('created_at', [$weekStartDate . ' 00:00:00', $weekEndDate . ' 23:59:59'])->sum('order_total');
            $activity = Activity::with(['activitypackageoptions.packagequantity'])
                    ->where('is_delete', 0)
                    ->orderBy('id', 'DESC')
                    ->get()
                    ->take(5);

            return view('users::dashboard', compact('usersCount', 'ordersCount', 'activityCount', 'customer', 'orders', 'activity', 'totalRevenue'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    // List of all users
    public function index() {
        if (Auth::user()->can('system_users', 'read') && Auth::user()->role_id == 1) {
            $users = DB::table('users')
                    ->where('role_id', 1)
                    ->where('is_delete', 0)
                    ->orderBy('id', 'DESC')
                    ->paginate($this->limit);
            return view('users::users', compact('users'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    // Search users
    public function search(Request $request) {
        if (Auth::user()->can('system_users', 'read') && Auth::user()->role_id == 1) {
            $searchterm = $request->get('searchterm');
            $users = DB::table('users')
                    ->where(function($query) use($searchterm) {
                        $query->where('name', 'LIKE', '%' . $searchterm . '%');
                        $query->orWhere('email', 'LIKE', '%' . $searchterm . '%');
                        $query->orWhere('status', 'LIKE', '%' . $searchterm . '%');
                    })
                    ->where('role_id', 1)
                    ->where('is_delete', 0)
                    ->orderBy('users.id', 'DESC')
                    ->paginate($this->limit);
            $users->appends(['searchterm' => $searchterm])->render(); // Append query string to URL
            return view('users::users', compact('users', 'searchterm'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    // Add new user
    public function add(Request $request) {
        if (Auth::user()->can('system_users', 'write') && Auth::user()->role_id == 1) {
            if ($request->isMethod('POST')) { // Post
                $user = new User();
                $user->role_id = 1;
                $user->email = $request->email;
                if ($request->password != "") {
                    $user->password = \Hash::make($request->password);
                }
                $user->name = $request->name;
                $user->status = $request->status;
                $user->created_at = date('Y-m-d H:i:s', time());
                $user->updated_at = date('Y-m-d H:i:s', time());
                $user->save();

                Session::flash('success', 'System user has been saved successfully.');
                return redirect()->route('users');
            } else { // Get
                return view('users::add');
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    // Edit user
    public function edit(Request $request, $id) {
        if (Auth::user()->can('system_users', 'write') && Auth::user()->role_id == 1) {
            if (is_numeric($id)) {
                $user = DB::table('users')->where('id', $id)->first();
                if ($user) {
                    return view('users::edit', compact('user'));
                } else {
                    Session::flash('error', 'No such user found.');
                    return redirect()->route('users');
                }
            } else {
                return view('errors.404');
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    // Update user details
    public function update(Request $request, $id) {
        if (Auth::user()->can('system_users', 'write') && Auth::user()->role_id == 1) {
            if (is_numeric($id)) {
                $user = User::findOrFail($id);
                $user->name = $request->name;
                $user->email = $request->email;
                if (isset($request->status)) {
                    $user->status = $request->status;
                }
                $user->updated_at = date('Y-m-d h:i:s', time());
                $user->save();
                Session::flash('success', 'System user has been updated successfully.');
                return redirect()->route('users');
            } else {
                return view('errors.404');
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    // Delete particular user
    public function delete(Request $request, $id) {
        if (Auth::user()->can('system_users', 'write') && Auth::user()->role_id == 1) {
            DB::table('users')->where('id', $id)->update([
                'is_delete' => 1
            ]);
            Session::flash('success', 'System user has been deleted successfully.');
            return redirect()->route('users');
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    // Delete multiple users
    public function multipleDelete(Request $request) {
        if (Auth::user()->can('system_users', 'write') && Auth::user()->role_id == 1) {
            $ids = $request->get('ids');
            if ($ids) {
                $users = explode(',', $ids);
                for ($i = 0; $i < count($users); $i++) {
                    DB::table('users')->where('id', $users[$i])->update([
                        'is_delete' => 1
                    ]);
                }
                Session::flash('success', 'System user(s) have been deleted successfully.');
                $data['success'] = true;
                $data['message'] = 'System user(s) have been deleted successfully.';
            } else {
                $data['success'] = false;
                $data['message'] = 'some error during deleting process.';
            }
            return response()->json([$data]);
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    // Change user status
    public function changeStatus(Request $request) {
        DB::table('users')->where('id', $request->id)->update(['status' => $request->status]);
        if ($request->data_type == "system user") {
            return response()->json([
                        'status' => "System user has been " . $request->status . " successfully."
            ]);
        } else {
            if ($request->status == "Active") {
                $status = "Active";
            } else {
                $status = "Inactive";
            }
            return response()->json([
                        'status' => "Customer has been " . $status . " successfully."
            ]);
        }
    }

    // Check if email is already exists or not
    public function emailExists() {
        $user = DB::table('users')->where('email', '=', $_POST['email'])->where('is_delete', 0)->first();
        if ($_POST['action'] == 'edituser') { // Check For Edit User
            if ($user === null) {
                $isValid = true;
            } else {
                if (count($user) == 1) {
                    if ($user->id == $_POST['userid']) {
                        $isValid = true;
                    } else {
                        $isValid = false;
                    }
                } else {
                    $isValid = false;
                }
            }
        } else { // Check For New User
            if ($user === null) {
                $isValid = true;
            } else {
                $isValid = false;
            }
        }
        echo json_encode(array(
            'valid' => $isValid,
        ));
    }

    // Change Password
    public function changepassword(Request $request) {
        if (Auth::user()->can('system_users', 'write') && Auth::user()->role_id == 1) {
            $user = DB::table('users')->where('id', Auth::user()->id)->first();
            if (!\Hash::check($request->oldpassword, $user->password)) {
                Session::flash('error', 'Old password is wrong.');
                return redirect()->route('dashboard');
            } else {
                $newPassword = \Hash::make($request->password);
                DB::table('users')
                        ->where('id', Auth::user()->id)
                        ->update(['updated_at' => date('Y-m-d h:i:s', time()),
                            'password' => $newPassword]);
                Session::flash('success', 'Your password has been changed successfully.');
                return redirect()->route('dashboard');
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    //Chnage password merchant get 
    public function changePassMerchant(Request $request) {
        if (Auth::user()->can('system_users', 'write') && Auth::user()->role_id == 1) {
            $user = DB::table('users')->where('id', Auth::user()->id)->first();
            if ($request->isMethod('POST')) {
                if (!\Hash::check($request->oldpassword, $user->password)) {
                    Session::flash('error', 'Old password is wrong.');
                    return view('users::merchantchangepassword', compact('user'));
                } else {
                    $newPassword = \Hash::make($request->password);
                    DB::table('users')
                            ->where('id', Auth::user()->id)
                            ->update(['updated_at' => date('Y-m-d h:i:s', time()),
                                'password' => $newPassword]);
                    Session::flash('success', 'Your password has been changed successfully.');
                    return view('users::merchantchangepassword', compact('user'));
                }
            } else {
                return view('users::merchantchangepassword', compact('user'));
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    // List of all customers
    public function customers() {
        if (Auth::user()->can('customers', 'read') && Auth::user()->role_id == 1) {
            $customers = DB::table('users')
                    ->where('role_id', 2)
                    ->where('is_delete', 0)
                    ->orderBy('id', 'DESC')
                    ->paginate($this->limit);
            return view('users::customers', compact('customers'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    // Search customer
    public function searchCustomer(Request $request) {
        if (Auth::user()->can('customers', 'read') && Auth::user()->role_id == 1) {
            $searchterm = $request->get('searchterm');
            $customers = DB::table('users')
                    ->where(function($query) use($searchterm) {
                        $query->where('name', 'LIKE', '%' . $searchterm . '%');
                        $query->orWhere('email', 'LIKE', '%' . $searchterm . '%');
                        $query->orWhere('mobile_number', 'LIKE', '%' . $searchterm . '%');
                        $query->orWhere('status', 'LIKE', '%' . $searchterm . '%');
                        $query->orWhere('device_type', 'LIKE', '%' . $searchterm . '%');
                        $query->when(($searchterm == 'Normal' || $searchterm == 'normal'), function ($q) {
                            return $q->orWhere('registration_type', 1);
                        });
                        $query->when(($searchterm == 'Facebook' || $searchterm == 'facebook'), function ($q) {
                            return $q->orWhere('registration_type', 2);
                        });
                    })
                    ->where('role_id', 2)
                    ->where('is_delete', 0)
                    ->orderBy('users.id', 'DESC')
                    ->paginate($this->limit);
            $customers->appends(['searchterm' => $searchterm])->render(); // Append query string to URL
            return view('users::customers', compact('customers', 'searchterm'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    // Delete particular Customer
    public function deleteCustomer(Request $request, $id) {
        if (Auth::user()->can('customers', 'write') && Auth::user()->role_id == 1) {
            DB::table('users')->where('id', $id)->where('role_id', '2')->update([
                'is_delete' => 1
            ]);
            Session::flash('success', 'Customer has been deleted successfully');
            return redirect()->route('customers');
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /**
     * View Customers 
     * 
     */
    public function viewCustomer(Request $request, $id) {
        if (Auth::user()->can('customers', 'read') && Auth::user()->role_id == 1) {
            if (is_numeric($id)) {
                $user = User::where('id', $id)
                        ->where('role_id', 2)
                        ->where('is_delete', 0)
                        ->first();
                if ($user) {
                    return view('users::viewcustomer', compact('user'));
                } else {
                    Session::flash('error', 'No such customer found.');
                    return redirect()->route('customers');
                }
            } else {
                return view('errors.404');
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    // Merchant Get list
    public function listMerchant(Request $request) {
        if (Auth::user()->can('merchant', 'read') && Auth::user()->role_id == 1) {
            $merchants = DB::table('users')
                    ->where('role_id', 3)
                    ->where('is_delete', 0)
                    ->orderBy('id', 'DESC')
                    ->paginate($this->limit);
            return view('users::listmerchant', compact('merchants'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    //Merachant 
    public function serachMerchant(Request $request) {
        if (Auth::user()->can('merchant', 'read') && Auth::user()->role_id == 1) {
            $merchantterm = $request->get('searchterm');

            $merchants = DB::table('users')
                    ->where(function($query) use($merchantterm) {
                        $query->where('name', 'LIKE', '%' . $merchantterm . '%');
                        $query->orWhere('email', 'LIKE', '%' . $merchantterm . '%');
                        $query->orWhere('mobile_number', 'LIKE', '%' . $merchantterm . '%');
                        $query->orWhere('status', 'LIKE', '%' . $merchantterm . '%');
                        $query->orWhere('device_type', 'LIKE', '%' . $merchantterm . '%');
                        $query->when(($merchantterm == 'Normal' || $merchantterm == 'normal'), function ($q) {
                            return $q->orWhere('registration_type', 3);
                        });
                    })
                    ->where('role_id', 3)
                    ->where('is_delete', 0)
                    ->orderBy('users.id', 'DESC')
                    ->paginate($this->limit);

            $merchants->appends(['merchantterm' => $merchantterm])->render(); // Append query string to URL

            return view('users::listmerchant', compact('merchants', 'merchantterm'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    //Add New Merchant get
    public function addMerchant(Request $request) {
        if (Auth::user()->can('merchant', 'write') && Auth::user()->role_id == 1) {
            if ($request->isMethod('POST')) {
                $country = DB::table('profile_country')->where('id', $request->country)->first();
                $user = new User();
                $user->role_id = 3;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->company_name = $request->companyname;
                $user->mobile_number = $request->phone;
                if (count($country)) {
                    $user->country_name = $country->nicename;
                    $user->country_code = "+" . $country->phonecode;
                }
                $user->city_name = $request->city;
                $user->website = $request->website;
                $user->sst_certificate = $request->sst_certificate;
                $user->status = $request->status;
                $user->created_at = date('Y-m-d H:i:s', time());
                $user->updated_at = date('Y-m-d H:i:s', time());
                $user->save();
                $id = Crypt::encryptString($user->id);
                $dataArray = ["name" => $request->name, "email" => $request->email, 'createurl' => url('/merchant/create-password/' . $id)];

                $template = EmailTemplate::where('name', 'merchant-register')->first();
                Mail::send([], [], function($message) use ($template, $dataArray) {
                    $data = [
                        'toMerchantName' => $dataArray['name'],
                        'createlink' => $dataArray['createurl']
                    ];

                    $message->to($dataArray['email'], $dataArray['name'])
                            ->subject($template->subject)
                            ->setBody($template->parse($data), 'text/html');
                });
                Session::flash('success', 'Merchant has been added successfully.');
                return redirect()->route('merchantlist');
            } else {
                $countries = DB::table('profile_country')->pluck('nicename', 'id')->toArray();
                return view('users::merchantadd', compact('countries'));
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /* Create Merchant Password by Admin */

    public function createPassword(Request $request) {
        if (Auth::user()->can('merchant', 'write') && Auth::user()->role_id == 1) {
            $user = User::where('id', $request->id)->first();
            if ($user != null) {
                $user->password = \Hash::make($request->newpassword);
                $user->save();
                Session::flash('success', 'Merchant password created successfully');
                return redirect()->route('merchantlist');
            } else {
                Session::flash('error', 'No such registered merchant found');
                return redirect()->route('merchantlist');
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /* Create Merchant Password by Merchant */

    public function merchantCreatePassword(Request $request) {
        $id = Crypt::decryptString($request->id);
        $user = User::where('id', $id)->first();
        if ($request->isMethod('POST')) {
            if ($user != null) {
                $user->password = \Hash::make($request->newpassword);
                $user->save();
                Session::flash('success', 'Your password has been created successfully');
                return redirect()->route('login');
            } else {
                Session::flash('error', 'No such registered merchant found');
                return redirect()->route('login');
            }
        } else {
            $diff = strtotime(date("Y-m-d H:i:s")) - strtotime($user->created_at);
            $minuteDifference = round(abs($diff) / 60, 2);
            if ($minuteDifference > 30) { // Check if token is expires or not. Token will be expire after 30 minutes
                Session::flash('error', 'Token has been expired');
                return redirect()->route('login');
            } else { // Show reset password form
                return view('users::create_merchant_password', compact('user'));
            }
        }
    }

    /* ------------Edit merchant edit and update ----------- */

    public function editMerchant(Request $request, $id) {
        if (Auth::user()->can('merchant', 'write') && Auth::user()->role_id == 1) {
            if (is_numeric($id)) {
                if ($request->isMethod('POST')) {
                    $country = DB::table('profile_country')->where('id', $request->country)->first();
                    $user = User::where('id', $id)->first();
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
                    // if ($request->password != "") {
                    //     $user->password = \Hash::make($request->password);
                    // }
                    $user->updated_at = date('Y-m-d H:i:s', time());
                    $user->save();
                    Session::flash('success', 'Merchant has been edited successfully.');
                    if (Auth::user()->role_id == 1) {
                        return redirect()->route('merchantlist');
                    } else {
                        return redirect()->route('merchantdashboard');
                    }
                } else {
                    $user = User::where('id', $id)->first();
                    if ($user) {
                        $countries = DB::table('profile_country')->pluck('nicename', 'id')->toArray();
                        return view('users::merchantedit', compact('user', 'countries'));
                    } else {
                        Session::flash('error', 'No such merchant found.');
                        if (Auth::user()->role_id == 1) {
                            return redirect()->route('merchantlist');
                        } else {
                            return redirect()->route('merchantdashboard');
                        }
                    }
                }
            } else {
                return view('errors.404');
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /* ------------------Delete merchant ---------------- */

    public function deleteMerchant(Request $request, $id) {
        if (Auth::user()->can('merchant', 'write') && Auth::user()->role_id == 1) {
            DB::table('users')->where('id', $id)->update([
                'is_delete' => 1
            ]);
            DB::table('activity')->where('merchant_id', $id)->update([
                'is_delete' => 1
            ]);
            Session::flash('success', 'Merchant has been deleted successfully');
            return redirect()->route('merchantlist');
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    // Change Merchant user status
    public function merchantChangeStatus(Request $request) {
        DB::table('users')->where('id', $request->id)->update(['status' => $request->status]);
        if ($request->data_type == "system_user") {
            return response()->json([
                        'status' => "Merchant has been " . $request->status . " successfully."
            ]);
        } else {
            if ($request->status == "Active") {
                $status = "Active";
            } else {
                $status = "Inactive";
            }
            return response()->json([
                        'status' => "Merchant has been " . $status . " successfully."
            ]);
        }
    }

}
