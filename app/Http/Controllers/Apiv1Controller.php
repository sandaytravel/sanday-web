<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\EmailTemplate;
use App\Activity;
use App\Continent;
use App\Country;
use App\City;
use App\AboutUs;
use App\Cart;
use App\WhishList;
use App\ActivityPackageOptions;
use App\Transaction;
use App\Orders;
use App\Orderitems;
use App\Notification;
use App\ActivityReview;
use App\Reviewimages;
use App\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\ImageManagerStatic as Image;
use Artisan;
use JWTAuth;
use App\Explore;
use Fahim\PaypalIPN\PaypalIPNListener;
use Srmklive\PayPal\Services\ExpressCheckout;
use Illuminate\Support\Facades\Crypt;
use App\Modules\Merchant\Http\Controllers\MerchantController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Apiv1Controller extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $token;

    public function __construct(Request $request) {
        Artisan::call('route:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        $this->token = $request->header('Authorization');
    }

    /*
     * 
     * Custommer Registration
     * 
     */

    public function customerRegistration(Request $request) {
        if (isset($request->email)) {
            $userExists = DB::table('users')->where('email', $request->email)->where('is_delete', 0)->first();
            if ($userExists != null) {
                $response['code'] = 401;
                if ($userExists->registration_type == 1) {
                    $response['message'] = 'This email is already registered';
                } else {
                    $response['message'] = 'This email is already registered with facebook';
                }
            } else {
                $user = new User;
                $user->role_id = 2; // Customer
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = \Hash::make($request->password);
                $user->device_type = $request->device_type;
                $user->device_token = $request->device_token;
                $user->save();
                $this->sendRegistrationEmail($user, $request->password);
                $registeredUser = User::where('id', $user->id)->first();
                $response = $this->jwtAuthentication($request, $registeredUser);
            }
        } else {
            $response['code'] = 401;
            $response['message'] = 'Email address is required';
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    public function sendRegistrationEmail($user, $password) {
        $template = EmailTemplate::where('name', 'registration')->first();
        if ($template != null) {
            Mail::send([], [], function($message) use ($template, $user, $password) {
                $data = [
                    'email' => $user->email,
                    'password' => $password
                ];

                $message->to($user->email, $user->name)
                        ->subject($template->subject)
                        ->setBody($template->parse($data), 'text/html');
            });
        }
        return;
    }

    /*
     * Normal Login by Username/Email and Password
     * 
     */

    public function simpleLogin(Request $request) {
        $response = [];
        $user = DB::table('users')->where('email', $request->email)->where('role_id', 2)->where('is_delete', 0)->first();
        if ($user != null) {
            if ($user->registration_type == 1) {
                if (\Hash::check($request->password, $user->password)) { // Check for password is correct or not
                    if ($user->is_delete == 0) {
                        if ($user->status == "Active") {
                            $response = $this->jwtAuthentication($request, $user);
                            if ($response['code'] == 200) {
                                DB::table('users')->where('id', $user->id)->update(['device_token' => $request->device_token, 'device_type' => $request->device_type]);
                            }
                        } else { // Account has been deactivated
                            $response['code'] = 401;
                            $response['message'] = "Your account has been deactivated. Please contact san app management.";
                        }
                    } else {
                        $response['code'] = 401;
                        $response['message'] = "This user has been deleted";
                    }
                } else { // Password does not match
                    $response['code'] = 401;
                    $response['message'] = "Please enter valid password";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "You are registered with facebook. Please login with facebook";
            }
        } else { // No such registered user found
            $response['code'] = 401;
            $response['message'] = "No such registered user found.";
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    /*
     * Facebook Login
     * 
     */

    public function facebookLogin(Request $request) {
        $user = DB::table('users')->where('facebook_id', $request->facebook_id)->where('role_id', 2)->first();
        if ($user != null) { // Do login
            if ($user->is_delete == 0) {
                if ($user->status == "Active") {
                    if ($user->registration_type == 2) {
                        $response = $this->jwtAuthentication($request, $user);
                        if ($response['code'] == 200) {
                            DB::table('users')->where('id', $user->id)->update(['device_token' => $request->device_token, 'device_type' => $request->device_type]);
                        }
                    } else {
                        $response['code'] = 401;
                        $response['message'] = "You are manually registered. Please login manually";
                    }
                } else { // Account has been deactivated
                    $response['code'] = 401;
                    $response['message'] = "Your account has been deactivated. Please contact san app management";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "Your account has been deleted";
            }
        } else { // Register first then login
            if ($request->email != "" || $request->email != null) {
                $user = DB::table('users')->where('email', $request->email)->where('role_id', 2)->first();
                if (count($user)) {
                    if ($user->registration_type == 1) {
                        $response['code'] = 401;
                        $response['message'] = "You are manually registered. Please login manually";
                    } else {
                        $response['code'] = 401;
                        $response['message'] = "You are registered with facebook. Please login with facebook";
                    }
                } else {
                    $user = new User();
                    $user->role_id = 2;
                    $user->name = (isset($request->name)) ? $request->name : null;
                    $user->email = (isset($request->email)) ? $request->email : null;
                    $user->mobile_number = (isset($request->phone)) ? $request->phone : null;
                    $user->profile_photo = (isset($request->profile_pic)) ? $request->profile_pic : null;
                    $user->facebook_id = $request->facebook_id;
                    $user->registration_type = 2;
                    $user->device_type = $request->device_type;
                    $user->device_token = $request->device_token;
                    $user->created_at = date('Y-m-d H:i:s', time());
                    $user->updated_at = date('Y-m-d H:i:s', time());
                    $user->save();
                    $response = $this->jwtAuthentication($request, $user);
                }
            } else {
                $user = new User();
                $user->role_id = 2;
                $user->name = (isset($request->name)) ? $request->name : null;
                $user->email = (isset($request->email)) ? $request->email : null;
                $user->mobile_number = (isset($request->phone)) ? $request->phone : null;
                $user->profile_photo = (isset($request->profile_pic)) ? $request->profile_pic : null;
                $user->facebook_id = $request->facebook_id;
                $user->registration_type = 2;
                $user->device_type = $request->device_type;
                $user->device_token = $request->device_token;
                $user->created_at = date('Y-m-d H:i:s', time());
                $user->updated_at = date('Y-m-d H:i:s', time());
                $user->save();
                $response = $this->jwtAuthentication($request, $user);
            }
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    /*
     * Change Password
     * 
     */

    public function changePassword(Request $request) {
        $token = $this->validateToken($this->token);
        if ($token == "varified") {// Varify the token whether it's correct or not.
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
            if ($user) {
                if ($request->old_password != "" && $request->new_password != "") {
                    if (\Hash::check($request->old_password, $user->password)) { // Check for password is correct or not
                        if ($request->old_password != $request->new_password) {
                            if (isset($request->new_password)) {
                                $newpassword = \Hash::make($request->new_password);
                                DB::table('users')
                                        ->where('id', $user->id)
                                        ->update(['password' => $newpassword, 'updated_at' => date('Y-m-d h:i:s', time())]);
                                $response['code'] = 200;
                                $response['message'] = "Password changed successfully";
                            } else {
                                $response['code'] = 401;
                                $response['message'] = "Please enter new password";
                            }
                        } else {
                            $response['code'] = 401;
                            $response['message'] = "New password must be different from current password";
                        }
                    } else {
                        $response['code'] = 401;
                        $response['message'] = "Old password is wrong";
                    }
                } else {
                    $response['code'] = 401;
                    $response['message'] = "Password can not empty";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "No such registered user found";
            }
        } else {
            return $token;
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    /*
     * 
     * Forgot Password
     * 
     */

    public function forgotPassword(Request $request) {
        if (isset($request->email)) {
            $user = User::Where('email', '=', $request->email)->first();
            if ($user) {
                DB::table('users')
                        ->where('id', "=", $user->id)
                        ->update(['reset_token' => date('Y-m-d h:i:s', time()),
                            'updated_at' => date('Y-m-d h:i:s', time())]);
                $this->sendforgotPasswordEmail($user);
                $response['code'] = 200;
                $response['message'] = "Reset password email sent to your registered account.";
            } else {
                $response['code'] = 401;
                $response['message'] = "No such registered user found.";
            }
        } else { // Enter valid username and password
            $response['code'] = 401;
            $response['message'] = "Please enter valid email address.";
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    public function sendforgotPasswordEmail($user) {
        $template = EmailTemplate::where('name', 'forgot-password')->first();
        if ($template != null) {
            $dataArray = ["id" => $user->id, "name" => $user->name, "email" => $user->email, 'reseturl' => url('/resetpassword/' . $user->id)];
            Mail::send([], [], function($message) use ($template, $dataArray) {
                $data = [
                    'toUserName' => $dataArray['name'],
                    'resetLink' => $dataArray['reseturl'],
                ];

                $message->to($dataArray['email'], $dataArray['name'])
                        ->subject($template->subject)
                        ->setBody($template->parse($data), 'text/html');
            });
        }
        return;
    }

    /*
     * Location Listing
     * 
     */

    public function getDestination(Request $request) {
        $hotDestination = $this->getHotDestinations($request->continent);

        $query = Country::with(['continent' => function ($query) {
                        $query->where('is_delete', 0);
                    }, 'city' => function($query) {
                        $query->where('is_delete', 0);
                    }, 'city.activities' => function($query) {
                        $query->where('is_delete', 0);
                    }]);
        if ($request->continent != "All") {
            $continent = $request->continent;
            $query = $query->whereHas('continent', function ($query) use($continent) {
                $query->where('name', $continent);
            });
        }
        $destinations = $query->where([
                    'status' => 'Active',
                    'is_delete' => 0
                ])->orderBy('updated_at', 'desc')->get();
        $locations = [];
        if ($destinations != null && count($destinations)) {
            foreach ($destinations as $key => $value) {
                $locations[$key]['continent_id'] = $value->continent->id;
                $locations[$key]['continent'] = $value->continent->name;
                $locations[$key]['country_id'] = $value->id;
                $locations[$key]['country'] = $value->country;
                if (count($value->city)) {
                    foreach ($value->city as $cityKey => $cityValue) {
                        $locations[$key]['cities'][$cityKey]['city_id'] = $cityValue->id;
                        $locations[$key]['cities'][$cityKey]['city'] = $cityValue->city;
                        $locations[$key]['cities'][$cityKey]['image_fullsize'] = url("/public/img/cityimages/fullsize/" . $cityValue->image);
                        $locations[$key]['cities'][$cityKey]['image_resized'] = url("/public/img/cityimages/resized/" . $cityValue->image);
                        $locations[$key]['cities'][$cityKey]['description'] = ($cityValue->description ) ? $cityValue->description : "";
                        $locations[$key]['cities'][$cityKey]['timezone'] = $cityValue->timezone;
                        $locations[$key]['cities'][$cityKey]['zone_name'] = $cityValue->zone_name;
                        $locations[$key]['cities'][$cityKey]['activity_count'] = count($cityValue->activities);
                        $locations[$key]['cities'][$cityKey]['created_date'] = date('Y-m-d', strtotime($cityValue->created_at));
                    }
                } else {
                    $locations[$key]['cities'] = [];
                }
            }
        }

        $response['code'] = 200;
        $response['message'] = "Destinations found successfully";
        $response['payload']['hot_destination'] = $hotDestination;
        $response['payload']['locations'] = $locations;
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    /**
     * 
     * 
     * City or category search terms
     * 
     */
    public function CityCategorySearch(Request $request) {
        $response = [];
        $response['payload'] = [];
        $searchterm = $request->searchterm;
        $query = City::where('is_delete', 0);
        $query = $query->where(function ($query) use($searchterm) {
            $query->orWhere('city', 'LIKE', "%$searchterm%");
        });
        $City = $query->orderBy('updated_at', 'desc')->get();

        $query1 = Activity::with('city')->where('is_delete', 0);
        $query1 = $query1->where(function ($query1) use($searchterm) {
            $query1->orWhere('title', 'LIKE', "%$searchterm%");
        });
        $activities = $query1->where('admin_approve', '1')->where('status', 'Active')->orderBy('updated_at', 'desc')->get();
        $response['payload']['city'] = [];
        $response['payload']['activity'] = [];
        if (count($City)) {
            foreach ($City as $key => $value) {
                $response['payload']['city'][$key]['city_id'] = $value->id;
                $response['payload']['city'][$key]['city_name'] = $value->city;
            }
        } else {
            if (count($activities)) {
                foreach ($activities as $k => $v) {
                    $response['payload']['activity'][$k]['activity_id'] = $v->id;
                    $response['payload']['activity'][$k]['activity_title'] = $v->title;
                    $response['payload']['activity'][$k]['city_name'] = $v->city->city;
                }
            }
        }
        $response['code'] = 200;
        $response['message'] = "Destinations found successfully";
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    /*
     * 
     * Getting Top 10 Hot Destination By Continent 
     */

    public function getHotDestinations($continent) {
        $query = City::with(['country' => function ($query) {
                        $query->where('is_delete', 0);
                    }, 'country.continent' => function($query) {
                        $query->where('is_delete', 0);
                    }, 'activities' => function($query) {
                        $query->where('is_delete', 0);
                    }]);
        if ($continent != "All") {
            $query = $query->whereHas('country.continent', function ($q1) use($continent) {
                $q1->where('name', $continent);
            });
        }
        $hotDestination = $query->where('is_delete', 0)->get()->take(10);
        $data = [];
        if ($hotDestination != null && count($hotDestination)) {
            foreach ($hotDestination as $key => $value) {
                $data[$key]['city_id'] = $value->id;
                $data[$key]['city'] = $value->city;
                $data[$key]['image_fullsize'] = url("/public/img/cityimages/fullsize/" . $value->image);
                $data[$key]['image_resized'] = url("/public/img/cityimages/resized/" . $value->image);
                $data[$key]['description'] = $value->description;
                $data[$key]['timezone'] = $value->timezone;
                $data[$key]['zone_name'] = $value->zone_name;
                $data[$key]['activity_count'] = count($value->activities);
                $data[$key]['created_date'] = date('Y-m-d', strtotime($value->created_at));
            }
        }
        return $data;
    }

    /*
     * View complete city details
     * 
     */

    public function viewCity(Request $request) {
        if (isset($request->city_id)) {
            $cityResult = City::with(['categories.category.subcategories'])
                            ->where([
                                'id' => $request->city_id
                            ])->first();
            if ($cityResult != null) {
                if ($cityResult->is_delete == 0) {
                    if ($cityResult->status == "Active") {
                        $response['code'] = 200;
                        $response['message'] = "City details found successfully";
                        /* City Details */
                        $city = [];

                        $city['city_id'] = $cityResult->id;
                        $city['city'] = $cityResult->city;
                        $city['image_fullsize'] = url("/public/img/cityimages/fullsize/" . $cityResult->image);
                        $city['image_resized'] = url("/public/img/cityimages/resized/" . $cityResult->image);
                        $city['description'] = ($cityResult->description != null) ? $cityResult->description : '';
                        $city['timezone'] = $cityResult->timezone;
                        $city['zone_name'] = $cityResult->zone_name;
                        $city['created_date'] = date('Y-m-d', strtotime($cityResult->created_at));
                        $response['payload']['city'] = $city;
                        /* Category Details */
                        if (count($cityResult->categories)) {
                            foreach ($cityResult->categories as $categoryKey => $categoryValue) {

                                $response['payload']['categories'][$categoryKey]['category_id'] = $categoryValue->category_id;
                                $response['payload']['categories'][$categoryKey]['category_name'] = $categoryValue->category->name;
                                $response['payload']['categories'][$categoryKey]['category_image'] = url("/public/img/icons/resized/" . $categoryValue->category->icon);
                                $response['payload']['categories'][$categoryKey]['status'] = $categoryValue->category->status;
                                if (count($categoryValue->category->subcategories)) {
                                    $response['payload']['categories'][$categoryKey]['subcategories'][0]['subcategory_id'] = '0';
                                    $response['payload']['categories'][$categoryKey]['subcategories'][0]['subcategory_name'] = 'All';
                                    $response['payload']['categories'][$categoryKey]['subcategories'][0]['status'] = 'Active';
                                    foreach ($categoryValue->category->subcategories as $subcategorykey => $subcategoryvalue) {
                                        $subcategorykey += 1;
                                        $response['payload']['categories'][$categoryKey]['subcategories'][$subcategorykey]['subcategory_id'] = $subcategoryvalue->id;
                                        $response['payload']['categories'][$categoryKey]['subcategories'][$subcategorykey]['subcategory_name'] = $subcategoryvalue->name;
                                        $response['payload']['categories'][$categoryKey]['subcategories'][$subcategorykey]['status'] = $subcategoryvalue->status;
                                    }
                                } else {
                                    $response['payload']['categories'][$categoryKey]['subcategories'] = [];
                                }
                            }
                        } else {
                            $response['payload']['categories'] = [];
                        }

                        $recentAdded = $this->recentAdded($cityResult->id);
                        $response['payload']['recentlyadded'] = $recentAdded;
                        $popularActivity = $this->popularActivity($cityResult->id);
                        $response['payload']['popularActivity'] = $popularActivity;
                    } else {
                        $response['code'] = 401;
                        $response['message'] = "This city has been deactivated";
                    }
                } else {
                    $response['code'] = 401;
                    $response['message'] = "This city has been deleted";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "No city found";
            }
        } else {
            $response['code'] = 401;
            $response['message'] = "Please enter required details";
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    /*
     * List and Search Activity
     * 
     */

    public function getActivityList(Request $request) {
        if (isset($request->page) && $request->page != "") {
            $pageNumber = $request->page;
        } else {
            $pageNumber = 1;
        }
        $query = Activity::with('city', 'category', 'reviews.images', 'activitypackageoptions');
        /* Search by City */
        if (isset($request->city_id) && $request->city_id != "") {
            $query = $query->where('city_id', $request->city_id);
        }
        /* Search by Category */
        if (isset($request->category_id) && $request->category_id != "") {
            $query = $query->where('category_id', $request->category_id);
        }
        /* Search by Subcategory */
        if (isset($request->subcategory_id) && $request->subcategory_id != "") {
            $query = $query->where('subcategory_id', $request->subcategory_id);
        }
        /* Search by Activity Title */
        if (isset($request->title) && $request->title != "") {
            $query = $query->where('title', 'LIKE', "%" . $request->title . "%");
        }
        $query = $query->whereHas('city', function ($q) {
            $q->where([
                'status' => 'Active',
                'is_delete' => 0
            ]);
        });
        $query = $query->whereHas('category', function ($q) {
            $q->where([
                'status' => 'Active',
                'is_delete' => 0
            ]);
        });
        $query = $query->where(['status' => 'Active', 'is_delete' => 0, 'admin_approve' => 1]);
        /* Sort By */
        if (isset($request->sort_by) && $request->sort_by != "") {
            if ($request->sort_by == "price") {
                $query = $query->orderBy('display_price', 'asc');
            } else if ($request->sort_by == "recently_added") {
                $query = $query->orderBy('created_at', 'desc');
            } else {
                $query = $query->orderBy('created_at', 'asc');
            }
        } else {
            $query = $query->orderBy('created_at', 'asc');
        }
        $activities = $query->paginate(20, ['*'], 'page', $pageNumber);
        /* Preparing Result For Activity */
        $response = $this->setActivityList($activities, $pageNumber);
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    public function setActivityList($activities, $pageNumber) {
        if (count($activities) && $activities != null) {
            $response['code'] = 200;
            $response['message'] = "Activities found successfully";
            $response['page'] = $pageNumber + 1;
            foreach ($activities as $key => $value) {
                // print_r($value->toArray());
                $response['payload'][$key]['activity_id'] = $value->id;
                $response['payload'][$key]['city_id'] = $value->city_id;
                $response['payload'][$key]['title'] = $value->title;
                $response['payload'][$key]['subtitle'] = $value->title;
                $response['payload'][$key]['fullsize_image'] = url("/public/img/activity/fullsized/" . $value->image);
                $response['payload'][$key]['resized_image'] = url("/public/img/activity/resized/" . $value->image);
                $counttotal = count($value->reviews);
                $max = 0;
                foreach ($value->reviews as $rate => $count) {
                    $max = $max + $count['rating'];
                }
                $avrage_ratting = '';
                if (count($value->reviews)) {
                    $avrage_ratting = $max / $counttotal;
                }
                $response['payload'][$key]['total_review'] = ($counttotal != "") ? $counttotal : 0;
                $response['payload'][$key]['average_review'] = ($avrage_ratting != "") ? $avrage_ratting : 0;
                $total_booked = Orders::where('activity_id', $value->id)->count();
                $response['payload'][$key]['total_booked'] = $total_booked;
                if ($value->activitypackageoptions != null) {
                    $response['payload'][$key]['actual_price'] = ($value->activitypackageoptions[0]->actual_price) ? $value->activitypackageoptions[0]->actual_price : "";
                    if ($value->activitypackageoptions[0]->display_price > 0) {
                        $response['payload'][$key]['display_price'] = ($value->activitypackageoptions[0]->display_price) ? $value->activitypackageoptions[0]->display_price : "";
                    } else {
                        $response['payload'][$key]['display_price'] = ($value->activitypackageoptions[0]->actual_price) ? $value->activitypackageoptions[0]->actual_price : "";
                    }
                }
            }
        } else {
            $response['code'] = 200;
            $response['message'] = "No more activity found";
            $response['page'] = (int) $pageNumber;
            $response['payload'] = [];
        }
        return $response;
    }

    /*
     * Activity Package Add to Cart
     * 
     */

    public function sameBookingdate($request, $package_qty_array, $user, $cart) {
        foreach ($cart as $cartkey => $cartvalue) {
            foreach ($package_qty_array as $packagekey => $packagevalue) {
                if ($cartvalue->package_quantity_id == $packagevalue->id) {
                    $cartvalue->package_quantity_id = $packagevalue->id;
                    $cartvalue->quantity = $packagevalue->quantity + $cartvalue->quantity;
                }
            }
            $cartvalue->save();
        }
        return true;
    }

    public function diifBookingdate($request, $package_qty_array, $user) {
        foreach ($package_qty_array as $package_qty_array_key => $package_qty_array_value) {
            $cart = new Cart;
            $cart->user_id = $user->id;
            $cart->activity_id = $request->activity_id;
            $cart->package_id = $request->package_id;
            $cart->package_quantity_id = $package_qty_array_value->id;
            $cart->booking_date = $request->booking_date;
            $cart->quantity = $package_qty_array_value->quantity;
            $cart->save();
        }
        return true;
    }

    public function addToCart(Request $request) {
        $token = $this->validateToken($this->token);
        $response = [];
        if ($token == "varified") {
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
            if ($user) {
                $package_qty_array = json_decode($request->package_quantity);
                if ($package_qty_array != null) {
                    $cart = Cart::where(['user_id' => $user->id, 'activity_id' => $request->activity_id, 'package_id' => $request->package_id, 'booking_date' => $request->booking_date])->get();
                    // print_r($cart->toArray());
                    // exit;
                    if (count($cart)) {
                        $this->sameBookingdate($request, $package_qty_array, $user, $cart);
                        $response['code'] = 200;
                        $response['message'] = "This item was already in your cart,the quantity hasbeen updated";
                    } else {
                        $this->diifBookingdate($request, $package_qty_array, $user);
                        $response['code'] = 200;
                        $response['message'] = "Added to Shopping Cart!";
                    }
                } else {
                    $response['code'] = 401;
                    $response['message'] = "Please insert details";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "Please login first";
            }
            return response()->json($response)->setStatusCode($response['code'], $response['message']);
        } else {
            return $token;
        }
    }

    /*
     * Activity Package Edit Cart
     * 
     */

    public function editQuantity($request, $package_qty_array, $user, $cart) {
        foreach ($cart as $cartkey => $cartvalue) {
            foreach ($package_qty_array as $packagekey => $packagevalue) {
                if ($cartvalue->package_quantity_id == $packagevalue->id) {
                    $cartvalue->package_quantity_id = $packagevalue->id;
                    $cartvalue->quantity = $packagevalue->quantity;
                    $cartvalue->booking_date = $request->booking_date;
                }
            }
            $cartvalue->save();
        }
        return true;
    }

    public function editPackagid($request, $package_qty_array, $user, $cart) {
        foreach ($cart as $cartkey => $cartvalue) {
            $cartvalue->delete();
        }
        foreach ($package_qty_array as $package_qty_array_key => $package_qty_array_value) {
            $cart = new Cart;
            $cart->user_id = $user->id;
            $cart->activity_id = $request->activity_id;
            $cart->package_id = $request->package_id;
            $cart->package_quantity_id = $package_qty_array_value->id;
            $cart->booking_date = $request->booking_date;
            $cart->quantity = $package_qty_array_value->quantity;
            $cart->save();
        }
        return true;
    }

    public function editCart(Request $request) {
        $token = $this->validateToken($this->token);
        $response = [];
        if ($token == "varified") {
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
            if ($user) {
                $package_qty_array = json_decode($request->package_quantity);
                if ($package_qty_array != null) {

                    $cart = Cart::where(['user_id' => $user->id, 'activity_id' => $request->activity_id, 'package_id' => $request->oldpackage_id, 'booking_date' => $request->oldbooking_date])->get();
                    if ($request->oldpackage_id == $request->package_id) {
                        $this->editQuantity($request, $package_qty_array, $user, $cart);
                    } else {
                        $this->editPackagid($request, $package_qty_array, $user, $cart);
                    }
                    $response['code'] = 200;
                    $response['message'] = "Your cart has been updated!";
                } else {
                    $response['code'] = 401;
                    $response['message'] = "Please insert details";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "Please login first";
            }
            return response()->json($response)->setStatusCode($response['code'], $response['message']);
        } else {
            return $token;
        }
    }

    /*
     * Activity Package Delete to Cart
     * 
     */

    public function deleteCart(Request $request) {
        $token = $this->validateToken($this->token);
        $response = [];
        if ($token == "varified") {
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
            if ($user) {
                if ($request->activity_id != null && $request->package_id != null && $request->booking_date != null) {
                    $cart = Cart::where(['user_id' => $user->id, 'activity_id' => $request->activity_id, 'package_id' => $request->package_id, 'booking_date' => $request->booking_date])->delete();
                    $response['code'] = 200;
                    $response['message'] = "Activity has been deleted from your cart.";
                } else {
                    $response['code'] = 401;
                    $response['message'] = "Please insert details";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "Please login first";
            }
            return response()->json($response)->setStatusCode($response['code'], $response['message']);
        } else {
            return $token;
        }
    }

    /*
     * Activity Package View Cart
     * 
     */

    public function viewCart(Request $request) {
        $token = $this->validateToken($this->token);
        $response = [];
        if ($token == "varified") {
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();

            if ($user) {

                $packageIds = Cart::with(['activity.merchant', 'package', 'package_quntity'])->where('user_id', $user->id)->groupBy(['package_id', 'booking_date'])->get();
                $response['payload'] = [];
                if (count($packageIds)) {
                    $response['code'] = 200;
                    $response['message'] = "View you are shopping Cart.";

                    foreach ($packageIds as $packagekey => $value) {

                        $response['payload'][$packagekey]['activity_id'] = $value->activity_id;
                        $response['payload'][$packagekey]['activity_title'] = $value->activity->title;
                        $response['payload'][$packagekey]['activity_image'] = url("/public/img/activity/fullsized/" . $value->activity->image);
                        $response['payload'][$packagekey]['package_id'] = $value->package_id;
                        $response['payload'][$packagekey]['package_title'] = $value->package->package_title;

                        $packageOptions = Cart::with(['activity.merchant', 'package', 'package_quntity'])->where([
                                    'package_id' => $value->package_id,
                                    'activity_id' => $value->activity_id,
                                    'booking_date' => $value->booking_date,
                                ])->get();
                        if (count($packageOptions)) {
                            $total_price = 0;
                            foreach ($packageOptions as $p1 => $v1) {

                                $validity_date = date("Y-m-d");
                                $activitystatus = "";
                                if ($value->activity->is_delete == "1") {
                                    $activitystatus = "Delete";
                                } else if ($value->activity->status == "Inactive") {
                                    $activitystatus = "Inactive";
                                } else if (strtotime($validity_date) >= strtotime($v1->booking_date)) {
                                    $activitystatus = "Expire";
                                } else {
                                    $activitystatus = "Current";
                                }

                                $response['payload'][$packagekey]['activity_status'] = $activitystatus;

                                $response['payload'][$packagekey]['booking_date'] = $v1->booking_date;
                                $response['payload'][$packagekey]['Quantity'][$p1]['quantity_id'] = $v1->package_quntity->id;
                                $response['payload'][$packagekey]['Quantity'][$p1]['quantity_name'] = $v1->package_quntity->name;
                                $response['payload'][$packagekey]['Quantity'][$p1]['actual_price'] = $v1->package_quntity->actual_price;
                                $response['payload'][$packagekey]['Quantity'][$p1]['display_price'] = $v1->package_quntity->display_price;
                                $response['payload'][$packagekey]['Quantity'][$p1]['quantity'] = $v1->quantity;
                                $price = ($v1->package_quntity->display_price) ? $v1->package_quntity->display_price : $v1->package_quntity->actual_price;
                                $price * $v1->quantity;
                                $response['payload'][$packagekey]['Quantity'][$p1]['total_price'] = $price * $v1->quantity;
                                $total_price += $price * $v1->quantity;
                            }
                            $response['payload'][$packagekey]['total_price'] = $total_price;
                            $errors = $this->validateItem($value, $packageOptions);
                            $response['payload'][$packagekey]['errors'] = $errors;
                        }
                    }
                } else {
                    $response['code'] = 200;
                    $response['message'] = "Your shopping cart is empty!";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "Please login first";
            }
            return response()->json($response)->setStatusCode($response['code'], $response['message']);
        } else {
            return $token;
        }
    }

    public function validateItem($package, $packageOptions) {
        $errors['message'] = [];
        foreach ($packageOptions as $packagekey => $packagevalue) {
            if ($packagevalue->activity->merchant->is_delete == 1) { // If merchant deleted
                if (isset($errors) && !empty($errors) && isset($errors['message']) && !empty($errors['message'])) {
                    if ($packagevalue->activity->id != $errors['activity_id'] && $packagevalue->package->id != $errors['package_id'] && $packagevalue->booking_date != $errors['booking_date']) {
                        $errors['activity_id'] = $packagevalue->activity->id;
                        $errors['package_id'] = $packagevalue->package->id;
                        $errors['booking_date'] = $packagevalue->booking_date;
                        /* End */
                        $errors['message'][] = "Merchant has been deleted for the activity " . $packagevalue->activity->title;
                    }
                } else {
                    $errors['activity_id'] = $packagevalue->activity->id;
                    $errors['package_id'] = $packagevalue->package->id;
                    $errors['booking_date'] = $packagevalue->booking_date;
                    /* End */
                    $errors['message'][] = "Merchant has been deleted for the activity " . $packagevalue->activity->title;
                }
            } else {
                if ($packagevalue->activity->is_delete == 1) { // Activity deleted
                    if (isset($errors) && !empty($errors) && isset($errors['message']) && !empty($errors['message'])) {
                        if ($packagevalue->activity->id != $errors['activity_id'] && $packagevalue->package->id != $errors['package_id'] && $packagevalue->booking_date != $errors['booking_date']) {
                            $errors['activity_id'] = $packagevalue->activity->id;
                            $errors['package_id'] = $packagevalue->package->id;
                            $errors['booking_date'] = $packagevalue->booking_date;
                            /* End */
                            $errors['message'][] = $packagevalue->activity->title . " has been deleted";
                        }
                    } else {
                        $errors['activity_id'] = $packagevalue->activity->id;
                        $errors['package_id'] = $packagevalue->package->id;
                        $errors['booking_date'] = $packagevalue->booking_date;
                        /* End */
                        $errors['message'][] = $packagevalue->activity->title . " has been deleted";
                    }
                } else {
                    if ($packagevalue->activity->status == "Inactive") { // Activity status inactiavated
                        if (isset($errors) && !empty($errors) && isset($errors['message']) && !empty($errors['message'])) {
                            if ($packagevalue->activity->id != $errors['activity_id'] && $packagevalue->package->id != $errors['package_id'] && $packagevalue->booking_date != $errors['booking_date']) {
                                $errors['activity_id'] = $packagevalue->activity->id;
                                $errors['package_id'] = $packagevalue->package->id;
                                $errors['booking_date'] = $packagevalue->booking_date;
                                /* End */
                                $errors['message'][] = $packagevalue->activity->title . " has been inactivated";
                            }
                        } else {
                            $errors['activity_id'] = $packagevalue->activity->id;
                            $errors['package_id'] = $packagevalue->package->id;
                            $errors['booking_date'] = $packagevalue->booking_date;
                            /* End */
                            $errors['message'][] = $packagevalue->activity->title . " has been inactivated";
                        }
                    } else {
                        $date = date('Y-m-d');
                        if ($packagevalue->quantity > 0 && $packagevalue->package->is_delete == 1) { // Package Deleted
                            /* Preparing Array For Same Package With Different Booking Date */
                            if (isset($errors) && !empty($errors) && isset($errors['message']) && !empty($errors['message'])) {
                                if ($packagevalue->activity->id != $errors['activity_id'] && $packagevalue->package->id != $errors['package_id'] && $packagevalue->booking_date != $errors['booking_date']) {
                                    $errors['activity_id'] = $packagevalue->activity->id;
                                    $errors['package_id'] = $packagevalue->package->id;
                                    $errors['booking_date'] = $packagevalue->booking_date;
                                    /* End */
                                    $errors['message'][] = "Package " . $packagevalue->package->package_title . " has been deleted";
                                }
                            } else {
                                $errors['activity_id'] = $packagevalue->activity->id;
                                $errors['package_id'] = $packagevalue->package->id;
                                $errors['booking_date'] = $packagevalue->booking_date;
                                /* End */
                                $errors['message'][] = "Package " . $packagevalue->package->package_title . " has been deleted";
                            }
                        } else if ($packagevalue->quantity > 0 && $packagevalue->package->is_delete == 0 && (strtotime($date . "-1 days") >= strtotime($packagevalue->booking_date) || strtotime($packagevalue->package->validity . "+1 days") <= strtotime($packagevalue->booking_date))) {

                            /* Preparing Array For Same Package With Different Booking Date */
                            if (isset($errors) && !empty($errors) && isset($errors['message']) && !empty($errors['message'])) {
                                if ($packagevalue->activity->id != $errors['activity_id'] && $packagevalue->package->id != $errors['package_id'] && $packagevalue->booking_date != $errors['booking_date']) {
                                    $errors['activity_id'] = $packagevalue->activity->id;
                                    $errors['package_id'] = $packagevalue->package->id;
                                    $errors['booking_date'] = $packagevalue->booking_date;
                                    /* End */
                                    $errors['message'][] = $packagevalue->package->package_title . " has been expired";
                                }
                            } else {
                                $errors['activity_id'] = $packagevalue->activity->id;
                                $errors['package_id'] = $packagevalue->package->id;
                                $errors['booking_date'] = $packagevalue->booking_date;
                                /* End */
                                $errors['message'][] = $packagevalue->package->package_title . " has been expired";
                            }
                        } else { // Booking Details LIKE [Adult child gets deleted ]
                            if (count($packagevalue->package_quntity)) {
                                if ($packagevalue->quantity > 0 && $packagevalue->package_quntity->is_delete == 1 && $packagevalue->package_quantity_id == $packagevalue->package_quntity->id) {
                                    $errors['activity_id'] = $packagevalue->activity->id;
                                    $errors['package_id'] = $packagevalue->package->id;
                                    $errors['booking_date'] = $packagevalue->booking_date;
                                    /* End */
                                    $errors['message'][] = "Booking option " . $packagevalue->package_quntity->name . " for package " . $packagevalue->package->package_title . " has been deleted";
                                }
                            } else {
                                $errors['activity_id'] = $packagevalue->activity->id;
                                $errors['package_id'] = $packagevalue->package->id;
                                $errors['booking_date'] = $packagevalue->booking_date;
                                $errors['message'][] = "Booking detail for pacakge " . $packagevalue->package->package_title . "not configured";
                            }
                        }
                    }
                }
            }
        }
        return $errors['message'];
    }

    /* Country List For Profile Screen */

    public function countryList(Request $request) {
        $countryList = DB::table('profile_country')->get();
        if (count($countryList)) {
            $response['code'] = 200;
            $response['message'] = "Country found successfully";
            foreach ($countryList as $key => $value) {
                $response['payload'][$key]['country_name'] = $value->nicename;
                $response['payload'][$key]['country_code'] = $value->phonecode;
            }
        } else {
            $response['code'] = 401;
            $response['message'] = "No country found";
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    /*
     * Place order 
     * 
     * 
     */

    public function placeOrder(Request $request) {
        $token = $this->validateToken($this->token);
        $response = [];
        if ($token == "varified") {
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
            if ($user) {
                $order_array = json_decode($request->order_value, true);
                // echo $merchant_id = Activity::where('id',7)->pluck('merchant_id')->first();
                // echo $activity_title = Activity::where('id',7)->pluck('title')->first();
                // exit;
                $user->title = (isset($request->title)) ? $request->title : null;
                $user->name = (isset($request->first_name)) ? $request->first_name : null;
                $user->family_name = (isset($request->family_name)) ? $request->family_name : null;
                $user->country_name = (isset($request->country_name)) ? $request->country_name : null;
                $user->country_code = (isset($request->country_code)) ? $request->country_code : null;
                $user->mobile_number = (isset($request->mobile_number)) ? $request->mobile_number : null;
                $user->voucher_email = (isset($request->voucher_email)) ? $request->voucher_email : null;
                $user->save();
                $response['payload']['id'] = (int) $user->id;
                $response['payload']['email'] = $user->email;
                $response['payload']['title'] = ($user->title) ? $user->title : "";
                $response['payload']['family_name'] = ($user->family_name) ? $user->family_name : "";
                $response['payload']['name'] = ($user->name) ? $user->name : "";
                $response['payload']['country_name'] = ($user->country_name) ? $user->country_name : "";
                $response['payload']['voucher_email'] = ($user->voucher_email) ? $user->voucher_email : $user->email;
                $response['payload']['country_code'] = ($user->country_code) ? $user->country_code : "";
                $response['payload']['phone'] = ($user->mobile_number) ? $user->mobile_number : "";
                $response['payload']['registration_type'] = $user->registration_type;
                $response['payload']['status'] = ($user->status != "") ? $user->status : "";
                if ($user->registration_type == 1) {
                    $response['payload']['profile_pic'] = ($user->profile_photo != "") ? url("/public/img/profileimage/" . $user->profile_photo) : "";
                } else {
                    $response['payload']['profile_pic'] = ($user->profile_photo != "") ? $user->profile_photo : "";
                }
                if (!empty($order_array)) {
                    $transactions = new Transaction;
                    $transactions->transaction_number = $this->generate_txnid();
                    $transactions->created_at = date('Y-m-d H:i:s', time());
                    $transactions->updated_at = date('Y-m-d H:i:s', time());
                    /* temp */
                    $transactions->paymet_status = "Completed";
                    $transactions->payment_response = "Completed";
                    $transactions->save();

                    foreach ($order_array as $orderkey => $ordervalue) {
                        $orders = new Orders;
                        $orders->transaction_id = $transactions->id;
                        $orders->order_number = $this->generate_orderno();
                        $orders->customer_id = $user->id;
                        $orders->activity_id = $ordervalue['activity_id'];
                        $orders->booking_date = $ordervalue['booking_date'];
                        $orders->order_total = $ordervalue['total_price'];
                        $orders->status = 0;
                        $orders->created_at = date('Y-m-d H:i:s', time());
                        $orders->updated_at = date('Y-m-d H:i:s', time());
                        /* --temp--- */
                        $orders->order_payment_status = "Completed";
                        $orders->save();
                        $merchant_id = Activity::where('id', $ordervalue['activity_id'])->pluck('merchant_id')->first();
                        $activity_title = Activity::where('id', $ordervalue['activity_id'])->pluck('title')->first();
                        $message = "Your " . $activity_title . " Has been booked for " . $ordervalue['booking_date'] . ".";
                        $notification = new Notification;
                        $notification->sender_id = $user->id;
                        $notification->receiver_id = $merchant_id;
                        $notification->message = $message;
                        $notification->created_at = date('Y-m-d H:i:s', time());
                        $notification->updated_at = date('Y-m-d H:i:s', time());
                        $notification->save();
                        $this->sendmerchantNotification($orders->id, $merchant_id, $message);
                        // $this->sendcustomerNotification($orders->id,$user->id,$message);
                        foreach ($ordervalue['package_quantity'] as $key => $value) {
                            $orderitem = New Orderitems;
                            $orderitem->order_id = $orders->id;
                            $orderitem->package_id = $ordervalue['package_id'];
                            $orderitem->package_quantity_id = $value['quantity_id'];
                            $orderitem->quantity = $value['quantity'];
                            $orderitem->package_price = $value['display_price'];
                            $orderitem->total = $value['quantity'] * $value['display_price'];
                            $orderitem->created_at = date('Y-m-d H:i:s', time());
                            $orderitem->updated_at = date('Y-m-d H:i:s', time());
                            $orderitem->save();
                        }
                    }
                    $response['code'] = 200;
                    $response['message'] = "your order has been placed successfully";
                    $response['transaction_id'] = $transactions->id;
                    $transactionId = Crypt::encryptString($transactions->id);
                    $response['webviewurl'] = Url('makepayment/' . $transactionId);
                } else {
                    $response['code'] = 401;
                    $response['message'] = "Please insert detials";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "Please login first";
            }
            return response()->json($response)->setStatusCode($response['code'], $response['message']);
        } else {
            return $token;
        }
    }

    public function makePayment(Request $request) {
        $transaction_id = Crypt::decryptString($request->id);
        $orders = Orders::with('oredr_ietms.packagequantity')->where('transaction_id', $transaction_id)->get();
        return view('paymentform', compact('orders', 'transaction_id'));
    }

    /* Check Payment Status */

    public function checkPaymentStatus(Request $request) {
        $token = $this->validateToken($this->token);
        $response = [];
        if ($token == "varified") {
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
            if ($user) {
                $transaction = Transaction::where('id', $request->transaction_id)->first();
                if ($transaction != null) {
                    $response['code'] = 200;
                    $response['message'] = "your order has been placed successfully";
                    $response['payment_status'] = $transaction->paymet_status;
                    $response['weburl'] = Url('/thankyou');
                    if ($transaction->paymet_status == "Completed" && $request->is_cart == 1) {
                        Cart::where(['user_id' => $user->id])->delete();
                    }
                } else {
                    $response['code'] = 401;
                    $response['message'] = "No transaction found";
                }
                return $response;
            } else {
                $response['code'] = 401;
                $response['message'] = "Your session has been expired. Please login again";
            }
        } else {
            return $token;
        }
    }

    public function validate_ipn() {
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
        // read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
        $req = 'cmd=_notify-validate';
        if (function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }

        // Step 2: POST IPN data back to PayPal to validate

        $ch = curl_init(env('PAYPAL_IPN_URL'));
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

        // In wamp-like environments that do not come bundled with root authority certificates,
        // please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set
        // the directory path of the certificate as shown below:
        // curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
        if (!($res = curl_exec($ch))) {
            // error_log("Got " . curl_error($ch) . " when processing IPN data");
            curl_close($ch);
            exit;
        }
        curl_close($ch);
        if (strcmp($res, "VERIFIED") == 0) { // Valid IPN transaction.
            if (isset($_POST['custom'])) {
                $custom = json_decode($_POST['custom']);
                $transaction = Transaction::with('orders')->where('id', $custom->transaction_id)->first();
                if ($transaction != null) {
                    $transaction->paymet_status = $_POST['payment_status'];
                    $transaction->payment_response = json_encode($_POST);
                    $transaction->save();
                    foreach ($transaction->orders as $key => $value) {
                        $value->order_payment_status = $_POST['payment_status'];
                        $value->save();
                        $merchant_id = Activity::where('id', $value['activity_id'])->pluck('merchant_id')->first();
                        $activity_title = Activity::where('id', $value['activity_id'])->pluck('title')->first();
                        $message = "Your " . $activity_title . " Has been booked for " . $value['booking_date'] . ".";
                        $notification = new Notification;
                        $notification->sender_id = $value->customer_id;
                        $notification->receiver_id = $merchant_id;
                        $notification->message = $message;
                        $notification->created_at = date('Y-m-d H:i:s', time());
                        $notification->updated_at = date('Y-m-d H:i:s', time());
                        $notification->save();
                        $this->sendmerchantNotification($value->id, $merchant_id, $message);
                    }
                    \Mail::raw('verified ' . json_encode($_POST) . $res, function ($message) {
                        $message->to('test@gmail.com');
                    });
                    return true;
                } else {
                    \Mail::raw('no transaction found in db ' . json_encode($_POST) . $res, function ($message) {
                        $message->to('test@gmail.com');
                    });
                    return false;
                }
            } else {
                return false;
            }
        } else if (strcmp($res, "INVALID") == 0) {
            \Mail::raw('Not verified ' . json_encode($_POST) . $res, function ($message) {
                $message->to('test@gmail.com');
            });
            return false;
        }
    }

    public function ipnNotify(Request $request) {
        $this->validate_ipn();
//        $provider = new ExpressCheckout;
//
//        $request->merge(['cmd' => '_notify-validate']);
//        $post = $request->all();
//
//        $response = (string) $provider->verifyIPN($post);
//        if ($response == 'VERIFIED') {
//            $this->ipn_paypal_success();
//            \Mail::raw('verified ' . json_encode($post) . $response, function ($message) {
//                $message->to('test@gmail.com');
//            });
//        } else {
//            \Mail::raw('Not verified ' . json_encode($post) . $response, function ($message) {
//                $message->to('test@gmail.com');
//            });
//        }
    }

    /*
     * View order 
     * 
     * 
     */

    public function viewOrder(Request $request) {
        $token = $this->validateToken($this->token);
        $response = [];
        if ($token == "varified") {
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
            $userID = $user->id;
            if ($user) {
                if (isset($request->page) && $request->page != "") {
                    $pageNumber = $request->page;
                } else {
                    $pageNumber = 1;
                }
                $orderget = Orders::with('transaction', 'oredr_ietms.activitypackageoptions', 'oredr_ietms.packagequantity', 'activity.reviews')->where('customer_id', $user->id)->orderBy('updated_at', 'desc')->get();
                // $transactionget = Transaction::with(['orders'=> function($query) use($userID){
                //     $query->where('customer_id', $userID);
                // },'orders.user','orders.activity','orders.oredr_ietms.activitypackageoptions'])->orderBy('updated_at', 'DESC')->get();
                $query = Transaction::with(['orders', 'orders.user', 'orders.activity', 'orders.oredr_ietms.activitypackageoptions']);
                $query->orWhereHas('orders.user', function ($query) use($userID) {
                    $query->where('customer_id', "$userID");
                });
                $transactionget = $query->orderBy('updated_at', 'DESC')->paginate(10, ['*'], 'page', $pageNumber);

                $response['payload'] = [];
                // print_r($transactionget->toArray());
                if (count($transactionget)) {
                    foreach ($transactionget as $key => $value) {
                        if ($value->paymet_status != 'Completed') {
                            $transactionId = Crypt::encryptString($value->id);
                            $response['payload'][$key]['webviewurl'] = Url('makepayment/' . $transactionId);
                        } else {
                            $response['payload'][$key]['webviewurl'] = "";
                        }

                        $response['payload'][$key]['transaction_id'] = $value->id;
                        $response['payload'][$key]['transaction_number'] = $value->transaction_number;
                        $response['payload'][$key]['payment_status'] = $value->paymet_status;
                        $response['payload'][$key]['transaction_date'] = date_format($value->created_at, 'Y-m-d');

                        $sum = 0;
                        foreach ($value->orders as $orderkey => $ordervalue) {
                            $sum += $ordervalue->order_total;
                        }
                        $response['payload'][$key]['total_amount'] = $sum;
                        foreach ($value->orders as $key1 => $value1) {
                            $response['payload'][$key]['orders'][$key1]['order_id'] = $value1->id;
                            $response['payload'][$key]['orders'][$key1]['customer_id'] = $value1->customer_id;
                            $response['payload'][$key]['orders'][$key1]['order_number'] = $value1->order_number;
                            $response['payload'][$key]['orders'][$key1]['order_payment_status'] = $value1->order_payment_status;
                            $response['payload'][$key]['orders'][$key1]['booking_date'] = date_format($value1->created_at, 'Y-m-d');
                            $response['payload'][$key]['orders'][$key1]['status_date'] = ($value1->status_date) ? $value1->status_date : "";
                            $response['payload'][$key]['orders'][$key1]['participation_date'] = $value1->booking_date;
                            $response['payload'][$key]['orders'][$key1]['order_status'] = $value1->status;
                            $response['payload'][$key]['orders'][$key1]['total_price'] = $value1->order_total;
                            $response['payload'][$key]['orders'][$key1]['activity_id'] = $value1->activity->id;
                            $response['payload'][$key]['orders'][$key1]['activity_name'] = $value1->activity->title;
                            // $response['payload'][$key]['orders'][$key1]['payment_status'] = $value1->transaction->paymet_status;
                            $response['payload'][$key]['orders'][$key1]['voucher_url'] = Url('voucher/' . $value1->id);
                            $response['payload'][$key]['orders'][$key1]['voucher_number'] = $value1->voucher_number;
                            if ($value1->voucher_number != null || $value1->voucher_number != "") {
                                $response['payload'][$key]['orders'][$key1]['voucher_qrcode'] = base64_encode(\QrCode::format('png')->size(128)->generate($value1->voucher_number));
                            } else {
                                $response['payload'][$key]['orders'][$key1]['voucher_qrcode'] = "";
                            }

                            if (count($value1->activity->reviews)) {
                                foreach ($value1->activity->reviews as $rkey1 => $rvalue) {
                                    if ($rvalue->user_id == $user->id) {
                                        $response['payload'][$key]['orders'][$key1]['is_review_given'] = "1";
                                    } else {
                                        $response['payload'][$key]['orders'][$key1]['is_review_given'] = "0";
                                    }
                                }
                            } else {
                                $response['payload'][$key]['orders'][$key1]['is_review_given'] = "0";
                            }

                            $response['payload'][$key]['orders'][$key1]['activity_image'] = url("/public/img/activity/fullsized/" . $value1->activity->image);

                            foreach ($value1->oredr_ietms as $orderkey1 => $ordervalue) {
                                $response['payload'][$key]['orders'][$key1]['package_title'] = $ordervalue->activitypackageoptions->package_title;
                                $response['payload'][$key]['orders'][$key1]['package_id'] = $ordervalue->activitypackageoptions->id;
                                $response['payload'][$key]['orders'][$key1]['packagequantity'][$orderkey1]['quantity'] = $ordervalue->quantity;
                                $response['payload'][$key]['orders'][$key1]['packagequantity'][$orderkey1]['quantity_id'] = $ordervalue->packagequantity->id;
                                $response['payload'][$key]['orders'][$key1]['packagequantity'][$orderkey1]['quantity_name'] = $ordervalue->packagequantity->name;
                                $response['payload'][$key]['orders'][$key1]['packagequantity'][$orderkey1]['quantity_price'] = $ordervalue->package_price;
                            }
                        }
                    }
                    $response['page'] = $pageNumber + 1;
                    $response['code'] = 200;
                    $response['message'] = "View your order.";
                } else {
                    $response['page'] = $pageNumber;
                    $response['code'] = 200;
                    $response['message'] = "No Order...yet";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "Please login first";
            }
            return response()->json($response)->setStatusCode($response['code'], $response['message']);
        } else {
            return $token;
        }
    }

    public function viewVoucher(Request $request) {
        $order = Orders::with(['activity.city', 'oredr_ietms.activitypackageoptions', 'oredr_ietms.packagequantity', 'user'])->where('id', $request->id)->first();
        return view('merchant::confirm_booking_pdf', compact("order"));
    }

    //================Place order genrate Transction id and ordernumber ===========
    public function generate_txnid() {
        return "TXN" . time() . 'SAN' . rand('11', '99');
    }

    public function generate_orderno() {
        $order = Orders::orderBy('id', 'desc')->limit(1)->first();
        if (count($order) > 0) {
            $orderId = $order->id + 1;
        } else {
            $orderId = 1;
        }
        return "SAN" . date('dm') . rand('4', '9999') . $orderId;
    }

    //========================= Validate User Token API ============================
    public static function validateToken($token) {
        try {
            $user = User::whereNotNull('user_token')->where('user_token', $token)->where('status', 'Active')->where('is_delete', 0)->get();
            if (count($user) == 1) {
                return "varified";
            } else {
                return response()->json([
                            "code" => 500,
                            "message" => 'You have been logged out of this device because you logged into another device with the same credentials.'
                        ])->setStatusCode(500, 'You have been logged out of this device because you logged into another device with the same credentials.'); // Unauthorized request exception
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                        "code" => 500,
                        "message" => 'You have been logged out of this device because you logged into another device with the same credentials.'
                    ])->setStatusCode(500, 'You have been logged out of this device because you logged into another device with the same credentials.'); // Unauthorized request exception
        }
    }

    // JWT Login
    public function jwtAuthentication($request, $user) {
        $request->only('email', 'password');
        $token = null;
        try {
            if (!$token = JWTAuth::fromUser($user)) {
                $response['code'] = 401;
                $response['message'] = "Invalid username/email or password";
            } else {
                DB::table('users')->where('id', $user->id)->update(['user_token' => $token]); // Updating Last Login Type

                $response['code'] = 200;
                $response['message'] = "You are successfully logged in.";
                $response['payload']['id'] = (int) $user->id;
                $response['payload']['role_id'] = $user->role_id;
                $response['payload']['email'] = $user->email;
                $response['payload']['title'] = ($user->title) ? $user->title : "";
                $response['payload']['name'] = ($user->name) ? $user->name : "";
                $response['payload']['family_name'] = ($user->family_name != "") ? $user->family_name : "";
                $response['payload']['voucher_email'] = ($user->voucher_email) ? $user->voucher_email : $user->email;
                $response['payload']['country_name'] = ($user->country_name) ? $user->country_name : "";
                $response['payload']['country_code'] = ($user->country_code) ? $user->country_code : "";
                $response['payload']['phone'] = ($user->mobile_number) ? $user->mobile_number : "";
                $response['payload']['registration_type'] = $user->registration_type;
                $response['payload']['status'] = ($user->status != "") ? $user->status : "";
                if ($user->registration_type == 1) {
                    $response['payload']['profile_pic'] = ($user->profile_photo != "") ? url("/public/img/profileimage/" . $user->profile_photo) : "";
                } else {
                    $response['payload']['profile_pic'] = ($user->profile_photo != "") ? $user->profile_photo : "";
                }
                $response['_token'] = $token;
            }
        } catch (JWTAuthException $e) {
            $response['code'] = 401;
            $response['message'] = "Failed to create token.";
        }
        return $response;
    }

    /* -----------------View activity Details  ------------------- */

    public function viewActivity(Request $request) {
        if (isset($request->activity_id)) {
            $date = date("Y-m-d");
            // $activityResults = Activity::with(['city', 'category', 'subcategory', 'faqs', 'policy.general_policy', 'activitypackageoptions' => function ($query)  use($date) {
            //         $query->whereDate( 'validity' , '>=' , $date )->where('is_delete', 0);
            //     }, 'activitypackageoptions.packagequantity' => function ($query) {
            //         $query->where('is_delete', 0);
            //     }, 'whishlist', 'reviews.images', 'reviews.user'])
            // ->where(['id' => $request->activity_id, 'is_delete' => 0, 'admin_approve' => 1, 'status' => 'Active'])
            // ->first();
            $activityResults = Activity::with(['city', 'category', 'subcategory', 'faqs', 'policy.general_policy', 'activitypackageoptions' => function ($query) use($date) {
                            $query->where('is_delete', 0);
                        }, 'activitypackageoptions.packagequantity' => function ($query) {
                            $query->where('is_delete', 0);
                        }, 'whishlist', 'reviews.images', 'reviews.user'])
                    ->where(['id' => $request->activity_id, 'is_delete' => 0, 'admin_approve' => 1, 'status' => 'Active'])
                    ->first();
            // print_r($activityResults->toArray());
            // exit;
            $response = [];
            if ($activityResults != null) {
                if ($activityResults->is_delete == 0) {
                    $token = $this->validateToken($this->token);
                    if ($token == "varified") {
                        $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
                        if ($activityResults->whishlist['user_id'] == $user->id) {
                            $wishlist_value = '1';
                        } else {
                            $wishlist_value = '0';
                        }
                    } else {
                        $wishlist_value = '0';
                    }
                    $response['code'] = 200;
                    $response['message'] = "Activity details found successfully";
                    $basicdetails['activity_id'] = $activityResults->id;
                    $basicdetails['title'] = $activityResults->title;
                    $basicdetails['subtitle'] = ($activityResults->subtitle) ? $activityResults->subtitle : "";
                    $basicdetails['image'] = url("/public/img/activity/fullsized/" . $activityResults->image);
                    // if ($activityResults->actual_price > $activityResults->display_price) {
                    //     $basicdetails['actual_price'] = $activityResults->actual_price;
                    // }
                    // $basicdetails['display_price'] = ($activityResults->display_price != "") ? $activityResults->display_price : "";
                    $basicdetails['description'] = ($activityResults->description) ? $activityResults->description : "";
                    $basicdetails['status'] = $activityResults->status;
                    $basicdetails['city'] = $activityResults->city->city;
                    $basicdetails['category'] = $activityResults->category['name'];
                    $basicdetails['category_icon_fullsized'] = url("/public/img/icons/fullsized/" . $activityResults->category['icon']);
                    $basicdetails['category_icon_resized'] = url("/public/img/icons/resized/" . $activityResults->category['icon']);
                    $basicdetails['wishlist'] = $wishlist_value;
                    $basicdetails['subcategory'] = ($activityResults->subcategory['name']) ? $activityResults->subcategory['name'] : "";

                    $what_to_expect = [];
                    if ($activityResults->is_what_to_expect == 1) {
                        $what_to_expect['what_to_expect_description'] = $activityResults->what_to_expect_description;
                    } else {
                        $what_to_expect['what_to_expect_description'] = "";
                    }

                    $activity_information = [];
                    if ($activityResults->is_activity_information == 1) {
                        $activity_information['activity_information_description'] = $activityResults->activity_information_description;
                    } else {
                        $activity_information['activity_information_description'] = "";
                    }

                    $how_to_use = [];
                    if ($activityResults->is_how_to_use == 1) {
                        $how_to_use['how_to_use_description'] = $activityResults->how_to_use_description;
                    } else {
                        $how_to_use['how_to_use_description'] = "";
                    }

                    $cancellation_policy = [];
                    if ($activityResults->is_cancellation_policy == 1) {
                        $cancellation_policy['cancellation_policy_description'] = $activityResults->cancellation_policy_description;
                    } else {
                        $cancellation_policy['cancellation_policy_description'] = "";
                    }

                    $faqdetail = [];
                    if ($activityResults->faqs != null) {
                        foreach ($activityResults->faqs as $activityfaqkey => $activityfaqvalue) {
                            $faqdetail[$activityfaqkey]['id'] = $activityfaqvalue->id;
                            $faqdetail[$activityfaqkey]['question'] = $activityfaqvalue->question;
                            $faqdetail[$activityfaqkey]['answer'] = $activityfaqvalue->answer;
                        }
                    }

                    $policydetail = [];
                    if ($activityResults->policy != null) {
                        foreach ($activityResults->policy as $policykey => $policyvalue) {
                            $policydetail[$policykey]['id'] = $policyvalue->policy_id;
                            $policydetail[$policykey]['name'] = $policyvalue->general_policy->name;
                            $policydetail[$policykey]['icon_fullsized'] = url("/public/img/icons/fullsized/" . $policyvalue->general_policy->icon);
                            $policydetail[$policykey]['icon_resized'] = url("/public/img/icons/resized/" . $policyvalue->general_policy->icon);
                        }
                    }

                    $packageoptions = [];
                    if ($activityResults->activitypackageoptions != null && count($activityResults->activitypackageoptions)) {
                        $basicdetails['actual_price'] = ($activityResults->activitypackageoptions[0]->actual_price) ? $activityResults->activitypackageoptions[0]->actual_price : "";
                        $basicdetails['display_price'] = ($activityResults->activitypackageoptions[0]->display_price) ? $activityResults->activitypackageoptions[0]->display_price : "";
                        $dateValidity = 'false';
                        $i = 0;
                        foreach ($activityResults->activitypackageoptions as $packageoptionskey => $packageoptionsvalue) {
                            // echo $activityResults->validity;
                            // exit;
                            if (!empty($packageoptionsvalue->validity)) {
                                $validityDate = $packageoptionsvalue->validity;
                            } else {
                                $validityDate = date("Y-m-d", time() + 86400);
                                ;
                            }
                            if (strtotime($validityDate) >= strtotime($date)) {
                                $dateValidity = 'true';
                            }
                            if ($dateValidity == 'true') {
                                $packageoptions[$i]['id'] = $packageoptionsvalue->id;
                                $packageoptions[$i]['activity_id'] = $packageoptionsvalue->activity_id;
                                $packageoptions[$i]['package_title'] = $packageoptionsvalue->package_title;
                                $packageoptions[$i]['description'] = ($packageoptionsvalue->description) ? $packageoptionsvalue->description : "";
                                $packageoptions[$i]['actual_price'] = ($packageoptionsvalue->actual_price) ? $packageoptionsvalue->actual_price : "";
                                $packageoptions[$i]['display_price'] = ($packageoptionsvalue->display_price) ? $packageoptionsvalue->display_price : $packageoptionsvalue->actual_price;
                                $packageoptions[$i]['is_delete'] = $packageoptionsvalue->is_delete;
                                $packageoptions[$i]['validity'] = ($packageoptionsvalue->validity != "") ? $packageoptionsvalue->validity : "";
                                $packageoptions[$i]['package_quantity'] = [];
                                foreach ($packageoptionsvalue->packagequantity as $packagequantitykey => $packagequantityvalue) {
                                    $packageoptions[$i]['package_quantity'][$packagequantitykey]['id'] = $packagequantityvalue->id;
                                    $packageoptions[$i]['package_quantity'][$packagequantitykey]['activity_package_id'] = $packagequantityvalue->activity_package_id;
                                    $packageoptions[$i]['package_quantity'][$packagequantitykey]['name'] = $packagequantityvalue->name;
                                    $packageoptions[$i]['package_quantity'][$packagequantitykey]['minimum_quantity'] = $packagequantityvalue->minimum_quantity;
                                    $packageoptions[$i]['package_quantity'][$packagequantitykey]['maximum_quantity'] = ($packagequantityvalue->maximum_quantity != "") ? $packagequantityvalue->maximum_quantity : "";
                                    $packageoptions[$i]['package_quantity'][$packagequantitykey]['actual_price'] = $packagequantityvalue->actual_price;
                                    $packageoptions[$i]['package_quantity'][$packagequantitykey]['display_price'] = ($packagequantityvalue->display_price) ? $packagequantityvalue->display_price : $packagequantityvalue->actual_price;
                                    $packageoptions[$i]['package_quantity'][$packagequantitykey]['is_delete'] = $packagequantityvalue->is_delete;
                                }
                                $i++;
                            }
                        }
                    }
                    $counttotal = count($activityResults->reviews);
                    $max = 0;
                    foreach ($activityResults->reviews as $rate => $count) {
                        $max = $max + $count['rating'];
                    }
                    $avrage_ratting = '';
                    if (count($activityResults->reviews)) {
                        $avrage_ratting = $max / $counttotal;
                    }
                    $basicdetails['total_review'] = ($counttotal != "") ? $counttotal : 0;
                    $basicdetails['average_review'] = ($avrage_ratting != "") ? $avrage_ratting : 0;

                    $reviews = [];
                    if (count($activityResults->reviews)) {

                        foreach ($activityResults->reviews as $reviewkey => $reviewvalue) {
                            if ($reviewkey <= 4) {
                                $reviews[$reviewkey]['review_id'] = $reviewvalue->id;
                                $reviews[$reviewkey]['review_date'] = date('Y-m-d', strtotime($reviewvalue->created_at));
                                $reviews[$reviewkey]['activity_id'] = $reviewvalue->activity_id;
                                $reviews[$reviewkey]['rating'] = $reviewvalue->rating;
                                $reviews[$reviewkey]['review'] = $reviewvalue->review;
                                $reviews[$reviewkey]['customer_id'] = $reviewvalue->user->id;
                                $reviews[$reviewkey]['customer_name'] = $reviewvalue->user->name;
                                if ($reviewvalue->user->registration_type == 1) {
                                    $reviews[$reviewkey]['profile_pic'] = ($reviewvalue->user->profile_photo != "") ? url("/public/img/profileimage/" . $reviewvalue->user->profile_photo) : "";
                                } else {
                                    $reviews[$reviewkey]['profile_pic'] = ($reviewvalue->user->profile_photo != "") ? $reviewvalue->user->profile_photo : "";
                                }
                                $reviews[$reviewkey]['review_images'] = [];
                                foreach ($reviewvalue->images as $key => $value) {
                                    $reviews[$reviewkey]['review_images'][$key]['fullsize_image'] = url("public/img/activity/review/fullsized/" . $value->image);
                                    $reviews[$reviewkey]['review_images'][$key]['resize_image'] = url("public/img/activity/review/fullsized/" . $value->image);
                                }
                            }
                        }
                    }
                    $response['payload']['basicdetails'] = $basicdetails;
                    $response['payload']['what_to_expect'] = $what_to_expect;
                    $response['payload']['activity_information'] = $activity_information;
                    $response['payload']['how_to_use'] = $how_to_use;
                    $response['payload']['cancellation_policy'] = $cancellation_policy;
                    $response['payload']['faqdetail'] = $faqdetail;
                    $response['payload']['policydetail'] = $policydetail;
                    $response['payload']['packageoptions'] = $packageoptions;
                    $response['payload']['reviews'] = $reviews;
                    $recentAdded = $this->recentAdded($activityResults->city->id);
                    $response['payload']['recentlyadded'] = $recentAdded;
                    $popularActivity = $this->popularActivity($activityResults->city->id);
                    $response['payload']['popularActivity'] = $popularActivity;
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "No activity detail found";
            }
        } else {
            $response['code'] = 401;
            $response['message'] = "Please enter required details";
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    /* --------------Recently Activity by city id ------------- */

    public function recentAdded($city_id) {
        $date = date("Y-m-d");
        $recentAdded = Activity::with(['city', 'activitypackageoptions' => function ($query) use($date) {
                        $query->where('is_delete', 0)->orderBy('updated_at', 'asc');
                    }, 'reviews.images'])
                ->where(['city_id' => $city_id, 'is_delete' => 0, 'admin_approve' => 1, 'status' => 'Active'])
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->get();
        // print_r($recentAdded->toArray());
        // exit;
        $recentlyadded = [];
        if ($recentAdded != null) {
            $i = 0;
            foreach ($recentAdded as $recentAddedkey => $recentAddedvalue) {
                // print_r($recentAddedvalue->toArray());
                $dateValidity = 'false';
                foreach ($recentAddedvalue->activitypackageoptions as $key => $value) {
                    if (!empty($value->validity)) {
                        $validityDate = $value->validity;
                    } else {
                        $validityDate = date("Y-m-d", time() + 86400);
                        ;
                    }
                    if (strtotime($validityDate) >= strtotime($date)) {
                        $dateValidity = 'true';
                    }
                }
                // if(!empty($value->validity)){
                //     $validityDate =$value->validity;
                // }else{
                //     $validityDate = date("Y-m-d", time() + 86400);;
                // }
                // if(strtotime($validityDate) >= strtotime($date)){
                if ($dateValidity == 'true') {

                    $counttotal = count($recentAddedvalue->reviews);
                    $max = 0;
                    foreach ($recentAddedvalue->reviews as $rate => $count) {
                        $max = $max + $count['rating'];
                    }
                    $avrage_ratting = '';
                    if (count($recentAddedvalue->reviews)) {
                        $avrage_ratting = $max / $counttotal;
                    }
                    $recentlyadded[$i]['activity_id'] = $recentAddedvalue->id;
                    $recentlyadded[$i]['title'] = $recentAddedvalue->title;
                    $recentlyadded[$i]['subtitle'] = ($recentAddedvalue->subtitle != "" ) ? $recentAddedvalue->subtitle : "";
                    $recentlyadded[$i]['total_review'] = ($counttotal != "") ? $counttotal : 0;
                    $recentlyadded[$i]['average_review'] = ($avrage_ratting != "") ? $avrage_ratting : 0;
                    $total_booked = Orders::where('activity_id', $recentAddedvalue->id)->count();
                    $recentlyadded[$i]['total_booked'] = $total_booked;
                    $recentlyadded[$i]['image'] = url("/public/img/activity/fullsized/" . $recentAddedvalue->image);

                    // foreach ($recentAddedvalue->activitypackageoptions as $packagekey => $packagevalue) {
                    //     $recentlyadded[$i]['actual_price'][$packagekey] = $recentAddedvalue->activitypackageoptions[0]->actual_price;
                    //     $recentlyadded[$i]['display_price'][$packagekey] = ($recentAddedvalue->activitypackageoptions[0]->display_price != "" ) ? $recentAddedvalue->activitypackageoptions[0]->display_price : "";
                    // }

                    if (count($recentAddedvalue->activitypackageoptions)) {
                        $recentlyadded[$i]['actual_price'] = $value->actual_price;
                        $recentlyadded[$i]['display_price'] = ($value->display_price != "" ) ? $value->display_price : "";
                    } else {
                        $recentlyadded[$i]['actual_price'] = "";
                        $recentlyadded[$i]['display_price'] = "";
                    }
                    $i++;
                }
                // exit;
                // return $recentlyadded;
                // // echo count($recentAddedvalue->activitypackageoptions);
                // // if (count($recentAddedvalue->activitypackageoptions)) {
                //     $counttotal = count($recentAddedvalue->reviews);
                //     $max = 0;
                //     foreach ($recentAddedvalue->reviews as $rate => $count) {
                //         $max = $max + $count['rating'];
                //     }
                //     $avrage_ratting = '';
                //     if (count($recentAddedvalue->reviews)) {
                //         $avrage_ratting = $max / $counttotal;
                //     }
                //     $recentlyadded[$recentAddedkey]['activity_id'] = $recentAddedvalue->id;
                //     $recentlyadded[$recentAddedkey]['title'] = $recentAddedvalue->title;
                //     $recentlyadded[$recentAddedkey]['subtitle'] = ($recentAddedvalue->subtitle != "" ) ? $recentAddedvalue->subtitle : "";
                //     $recentlyadded[$recentAddedkey]['total_review'] = ($counttotal != "") ? $counttotal : 0;
                //     $recentlyadded[$recentAddedkey]['average_review'] = ($avrage_ratting != "") ? $avrage_ratting : 0;
                //     $total_booked = Orders::where('activity_id', $recentAddedvalue->id)->count();
                //     $recentlyadded[$recentAddedkey]['total_booked'] = $total_booked;
                //     $recentlyadded[$recentAddedkey]['image'] = url("/public/img/activity/fullsized/" . $recentAddedvalue->image);
                //     // foreach ($recentAddedvalue->activitypackageoptions as $packagekey => $packagevalue) {
                //     //     $recentlyadded[$recentAddedkey]['actual_price'][$packagekey] = $recentAddedvalue->activitypackageoptions[0]->actual_price;
                //     //     $recentlyadded[$recentAddedkey]['display_price'][$packagekey] = ($recentAddedvalue->activitypackageoptions[0]->display_price != "" ) ? $recentAddedvalue->activitypackageoptions[0]->display_price : "";
                //     // }
                //     foreach ($recentAddedvalue->activitypackageoptions as $key => $value) {
                //         $recentlyadded[$recentAddedkey][$key]['actual_price'] = $value->actual_price;
                //     }
                //     if (count($recentAddedvalue->activitypackageoptions)) {
                //         // $recentlyadded[$recentAddedkey]['actual_price'] = $recentAddedvalue->activitypackageoptions[0]->actual_price;
                //         $recentlyadded[$recentAddedkey]['display_price'] = ($recentAddedvalue->activitypackageoptions[0]->display_price != "" ) ? $recentAddedvalue->activitypackageoptions[0]->display_price : "";
                //     } else {
                //         $recentlyadded[$recentAddedkey]['actual_price'] = "";
                //         $recentlyadded[$recentAddedkey]['display_price'] = "";
                //     }
                // $i++;
                // }
            }
            // exit;
        }
        return $recentlyadded;
    }

    /* --------------Popular Activity by city id ------------- */

    public function popularActivity($city_id) {
        $date = date("Y-m-d");
        // if ($city_id != "") {
        //     $popularActivity = Activity::with(['city', 'activitypackageoptions' => function ($query)  use($date) {
        //     $query->whereDate( 'validity' , '>=' , $date )->where('is_delete', 0);
        // }, 'reviews.images'])
        //             ->where(['city_id' => $city_id, 'is_delete' => 0, 'admin_approve' => 1, 'status' => 'Active', 'popular_activity' => 1])
        //             ->orderBy('updated_at', 'desc')
        //             ->take(10)
        //             ->get();
        // } else {
        //     $popularActivity = Activity::with(['city', 'activitypackageoptions' => function ($query)  use($date) {
        //     $query->whereDate( 'validity' , '>=' , $date )->where('is_delete', 0);
        // }, 'reviews.images'])
        //             ->where(['is_delete' => 0, 'popular_activity' => 1, 'admin_approve' => 1, 'status' => 'Active'])
        //             ->orderBy('updated_at', 'desc')
        //             ->take(10)
        //             ->get();
        // }
        if ($city_id != "") {
            $popularActivity = Activity::with(['city', 'activitypackageoptions' => function ($query) use($date) {
                            $query->where('is_delete', 0)->orderBy('updated_at', 'asc');
                        }, 'reviews.images'])
                    ->where(['city_id' => $city_id, 'is_delete' => 0, 'admin_approve' => 1, 'status' => 'Active', 'popular_activity' => 1])
                    ->orderBy('updated_at', 'desc')
                    ->take(10)
                    ->get();
        } else {
            $popularActivity = Activity::with(['city', 'activitypackageoptions' => function ($query) use($date) {
                            $query->where('is_delete', 0)->orderBy('updated_at', 'asc');
                        }, 'reviews.images'])
                    ->where(['is_delete' => 0, 'popular_activity' => 1, 'admin_approve' => 1, 'status' => 'Active'])
                    ->orderBy('updated_at', 'desc')
                    ->take(10)
                    ->get();
        }
        // print_r($popularActivity->toArray());

        $popularActivityshow = [];
        if ($popularActivity != null) {
            $i = 0;
            foreach ($popularActivity as $popularActivitykey => $popularActivityvalue) {
                $dateValidity = 'false';
                foreach ($popularActivityvalue->activitypackageoptions as $key => $value) {

                    if (!empty($value->validity)) {
                        $validityDate = $value->validity;
                    } else {
                        $validityDate = date("Y-m-d", time() + 86400);
                        ;
                    }
                    if (strtotime($validityDate) >= strtotime($date)) {
                        $dateValidity = 'true';
                    }
                }
                // if(strtotime($validityDate) >= strtotime($date)){
                if ($dateValidity == 'true') {
                    $counttotal = count($popularActivityvalue->reviews);
                    $max = 0;
                    foreach ($popularActivityvalue->reviews as $rate => $count) {
                        $max = $max + $count['rating'];
                    }
                    $avrage_ratting = '';
                    if (count($popularActivityvalue->reviews)) {
                        $avrage_ratting = $max / $counttotal;
                    }
                    $booked_activity = Orders::where('activity_id', $popularActivityvalue->id)->count();

                    $popularActivityshow[$i]['total_booked'] = $booked_activity;
                    $popularActivityshow[$i]['activity_id'] = $popularActivityvalue->id;
                    $popularActivityshow[$i]['title'] = $popularActivityvalue->title;
                    $popularActivityshow[$i]['subtitle'] = ($popularActivityvalue->subtitle != "" ) ? $popularActivityvalue->subtitle : "";
                    $popularActivityshow[$i]['total_review'] = ($counttotal != "") ? $counttotal : 0;
                    $popularActivityshow[$i]['average_review'] = ($avrage_ratting != "") ? $avrage_ratting : 0;
                    $popularActivityshow[$i]['image'] = url("/public/img/activity/fullsized/" . $popularActivityvalue->image);
                    if (count($popularActivityvalue->activitypackageoptions)) {
                        $popularActivityshow[$i]['actual_price'] = ($value->actual_price) ? $value->actual_price : "";
                        $popularActivityshow[$i]['display_price'] = ($value->display_price) ? $value->display_price : "";
                    } else {
                        $popularActivityshow[$i]['actual_price'] = "";
                        $popularActivityshow[$i]['display_price'] = "";
                    }
                    $i++;
                }

                // if (count($popularActivityvalue->activitypackageoptions)) {
                //     $counttotal = count($popularActivityvalue->reviews);
                //     $max = 0;
                //     foreach ($popularActivityvalue->reviews as $rate => $count) {
                //         $max = $max + $count['rating'];
                //     }
                //     $avrage_ratting = '';
                //     if (count($popularActivityvalue->reviews)) {
                //         $avrage_ratting = $max / $counttotal;
                //     }
                //     $booked_activity = Orders::where('activity_id', $popularActivityvalue->id)->count();
                //     $popularActivityshow[$i]['total_booked'] = $booked_activity;
                //     $popularActivityshow[$i]['activity_id'] = $popularActivityvalue->id;
                //     $popularActivityshow[$i]['title'] = $popularActivityvalue->title;
                //     $popularActivityshow[$i]['subtitle'] = ($popularActivityvalue->subtitle != "" ) ? $popularActivityvalue->subtitle : "";
                //     $popularActivityshow[$i]['total_review'] = ($counttotal != "") ? $counttotal : 0;
                //     $popularActivityshow[$i]['average_review'] = ($avrage_ratting != "") ? $avrage_ratting : 0;
                //     $popularActivityshow[$i]['image'] = url("/public/img/activity/fullsized/" . $popularActivityvalue->image);
                //     if (count($popularActivityvalue->activitypackageoptions)) {
                //         $popularActivityshow[$i]['actual_price'] = ($popularActivityvalue->activitypackageoptions[0]->actual_price) ? $popularActivityvalue->activitypackageoptions[0]->actual_price : "";
                //         $popularActivityshow[$i]['display_price'] = ($popularActivityvalue->activitypackageoptions[0]->display_price) ? $popularActivityvalue->activitypackageoptions[0]->display_price : "";
                //     } else {
                //         $popularActivityshow[$i]['actual_price'] = "";
                //         $popularActivityshow[$i]['display_price'] = "";
                //     }
                //     // if ($popularActivityvalue->actual_price > $popularActivityvalue->display_price) {
                //     //     $popularActivityshow[$i]['actual_price'] = $popularActivityvalue->actual_price;
                //     // }
                //     // $popularActivityshow[$i]['display_price'] = ($popularActivityvalue->display_price != "") ? $popularActivityvalue->display_price : "";
                //     $i++;
                // }
            }
        }
        return $popularActivityshow;
    }

    public function popularDestination($city_id) {
        if ($city_id != "") {
            $popularActivity = Activity::with(['city'])
                    ->where(['city_id' => $city_id, 'is_delete' => 0, 'status' => 'Active', 'admin_approve' => 1, 'popular_destination' => 1])
                    ->orderBy('updated_at', 'desc')
                    ->take(10)
                    ->get();
        } else {
            $popularActivity = Activity::with(['city'])
                    ->where(['is_delete' => 0, 'popular_destination' => 1, 'admin_approve' => 1, 'status' => 'Active'])
                    ->orderBy('updated_at', 'desc')
                    ->take(10)
                    ->get();
        }
        $popularDestinationshow = [];
        if ($popularActivity != null) {
            foreach ($popularActivity as $popularActivitykey => $popularActivityvalue) {
                $popularDestinationshow[$popularActivitykey]['activity_id'] = $popularActivityvalue->id;
                $popularDestinationshow[$popularActivitykey]['title'] = $popularActivityvalue->title;
                $popularDestinationshow[$popularActivitykey]['subtitle'] = ($popularActivityvalue->subtitle != "" ) ? $popularActivityvalue->subtitle : "";
                $popularDestinationshow[$popularActivitykey]['image'] = url("/public/img/activity/fullsized/" . $popularActivityvalue->image);
                if ($popularActivityvalue->actual_price > $popularActivityvalue->display_price) {
                    $popularDestinationshow[$popularActivitykey]['actual_price'] = $popularActivityvalue->actual_price;
                }
                $popularDestinationshow[$popularActivitykey]['display_price'] = ($popularActivityvalue->display_price != "") ? $popularActivityvalue->display_price : "";
            }
        }
        return $popularDestinationshow;
    }

    /*     * *
     * 
     * customer review
     * 
     */

    public function reviewActivity(Request $request) {
        $token = $this->validateToken($this->token);
        $response = [];
        if ($token == "varified") {
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
            if ($user) {
                if ($request->activity_id != null) {
                    $activityreview = new ActivityReview;
                    $activityreview->activity_id = $request->activity_id;
                    $activityreview->order_id = $request->order_id;
                    $activityreview->user_id = $user->id;
                    $activityreview->rating = $request->rating;
                    $activityreview->review = $request->review;
                    $activityreview->created_at = date('Y-m-d H:i:s', time());
                    $activityreview->updated_at = date('Y-m-d H:i:s', time());
                    $activityreview->save();
                    if (!empty($request->file()['review_images'])) {
                        if ($request->file()['review_images'] != null) {
                            foreach ($request->file()['review_images'] as $key => $value) {
                                $filename = "";
                                $filename = time() . '-' . $value->getClientOriginalName();
                                $path = public_path('img/activity/review/fullsized/' . $filename);
                                ;
                                $resizedPath = public_path('img/activity/review/resized/' . $filename);
                                Image::make($value->getRealPath())->save($path);
                                // Resize image with resolution 2040 X 1360
                                Image::make($value->getRealPath())->resize(2040, 1360)->save($resizedPath);
                                // Saving Product Images
                                $image = new Reviewimages();
                                $image->activity_reviews_id = $activityreview->id;
                                $image->image = $filename;
                                $image->created_at = date('Y-m-d h:i:s', time());
                                $image->updated_at = date('Y-m-d h:i:s', time());
                                $image->save();
                                $ids[] = $image->id;
                            }
                        }
                    }
                    $response['code'] = 200;
                    $response['message'] = "Thanks for submitting review.";
                } else {
                    $response['code'] = 401;
                    $response['message'] = "Incorrect details.";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "No such registered user found";
            }
            return response()->json($response)->setStatusCode($response['code'], $response['message']);
        } else {
            return $token;
        }
    }

    /*     * *
     * 
     * customer review list by activity
     * 
     */

    public function activityReview(Request $request) {
        if (isset($request->activity_id)) {
            $activityResults = Activity::with(['reviews.images', 'reviews.user'])
                    ->where(['id' => $request->activity_id, 'is_delete' => 0, 'admin_approve' => 1, 'status' => 'Active'])
                    ->first();
            if (isset($request->page) && $request->page != "") {
                $pageNumber = $request->page;
            } else {
                $pageNumber = 1;
            }
            $reviewActvity = ActivityReview::with('images', 'user', 'activity')->where('activity_id', $request->activity_id)->paginate(10, ['*'], 'page', $pageNumber);
            // print_r($reviewActvity->toArray());
            // exit;


            $response = [];
            $response['payload'] = [];
            $response['payload']['reviews'] = [];
            if (count($reviewActvity)) {
                $reviews = [];
                foreach ($reviewActvity as $reviewkey => $reviewvalue) {
                    $reviews[$reviewkey]['review_id'] = $reviewvalue->id;
                    $reviews[$reviewkey]['review_date'] = date('Y-m-d', strtotime($reviewvalue->created_at));
                    $reviews[$reviewkey]['activity_id'] = $reviewvalue->activity_id;
                    $reviews[$reviewkey]['rating'] = $reviewvalue->rating;
                    $reviews[$reviewkey]['review'] = $reviewvalue->review;
                    $reviews[$reviewkey]['customer_id'] = $reviewvalue->user->id;
                    $reviews[$reviewkey]['customer_name'] = $reviewvalue->user->name;
                    if ($reviewvalue->user->registration_type == 1) {
                        $reviews[$reviewkey]['profile_pic'] = ($reviewvalue->user->profile_photo != "") ? url("/public/img/profileimage/" . $reviewvalue->user->profile_photo) : "";
                    } else {
                        $reviews[$reviewkey]['profile_pic'] = ($reviewvalue->user->profile_photo != "") ? $reviewvalue->user->profile_photo : "";
                    }

                    $reviews[$reviewkey]['review_images'] = [];
                    foreach ($reviewvalue->images as $key => $value) {
                        $reviews[$reviewkey]['review_images'][$key]['fullsize_image'] = url("public/img/activity/review/fullsized/" . $value->image);
                        $reviews[$reviewkey]['review_images'][$key]['resize_image'] = url("public/img/activity/review/fullsized/" . $value->image);
                    }
                }
                $response['code'] = 200;
                $response['message'] = "Activity review found successfully";
                $response['page'] = $pageNumber + 1;
                $response['payload']['reviews'] = $reviews;
            } else {
                $response['code'] = 200;
                $response['message'] = "No activity review detail found";
                $response['page'] = 1;
            }
        } else {
            $response['code'] = 401;
            $response['message'] = "Please enter required details";
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    /* Explore */

    public function explore(Request $request) {
        $explore = Explore::with(['images'])->first();
        $response = [];
        if ($explore != null) {
            $response['code'] = 200;
            $response['message'] = "Explore data found";
            $token = $this->validateToken($this->token);
            if ($token == "varified") {
                $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
                if ($user) {
                    $carttotal = Cart::with(['activity', 'package', 'package_quntity'])->where('user_id', $user->id)->where('booking_date', '>=', date('Y-m-d'))->groupBy(['package_id', 'booking_date'])->get();
                    if (count($carttotal)) {
                        $response['payload']['cart_total'] = count($carttotal);
                    } else {
                        $response['payload']['cart_total'] = "0";
                    }
                }
            } else {
                $response['payload']['cart_total'] = "0";
            }

            $response['payload']['id'] = $explore->id;
            $response['payload']['title'] = $explore->title;
            $response['payload']['description'] = ($explore->description) ? $explore->description : "";
            $response['payload']['created_date'] = date('Y-m-d', strtotime($explore->created_at));
            if (count($explore->images)) {
                foreach ($explore->images as $key => $value) {
                    $response['payload']['images'][$key]['image_id'] = $value->id;
                    $response['payload']['images'][$key]['fullsized_image'] = url("/public/img/explore/fullsized/" . $value->image);
                    $response['payload']['images'][$key]['resized_image'] = url("/public/img/explore/resized/" . $value->image);
                }
            } else {
                $response['payload']['images'] = [];
            }
            $city_id = "";
            $response['payload']['popular_destination'] = $this->getPopularDestination();
            $response['payload']['popular_activity'] = $this->popularActivity($city_id);
        } else {
            $response['code'] = 401;
            $response['message'] = "No data found";
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    /* ------------get popular destination for explore page ----- */

    public function getPopularDestination() {
        $query1 = City::with(['activities' => function($query1) {
                        $query1->where('is_delete', 0);
                    }])->where('is_delete', 0)->where('popular_destination', 1)->get()->take(10);

        $data = [];
        if ($query1 != null && count($query1)) {
            foreach ($query1 as $key => $value) {
                $data[$key]['city_id'] = $value->id;
                $data[$key]['city'] = $value->city;
                $data[$key]['image_fullsize'] = url("/public/img/cityimages/fullsize/" . $value->image);
                $data[$key]['image_resized'] = url("/public/img/cityimages/resized/" . $value->image);
                $data[$key]['description'] = ($value->description) ? $value->description : "";
                $data[$key]['timezone'] = $value->timezone;
                $data[$key]['zone_name'] = $value->zone_name;
                $data[$key]['activity_count'] = count($value->activities);
                $data[$key]['created_date'] = date('Y-m-d', strtotime($value->created_at));
            }
        }
        return $data;
    }

    /* -----------------update profile pic ------------------- */

    public function updateProfilepic(Request $request) {
        $token = $this->validateToken($this->token);
        $response = [];
        if ($token == "varified") {
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
            if ($user) {
                if ($request->hasFile('profile_img')) {
                    $response['code'] = 200;
                    $response['message'] = "Profile image updated successfully";
                    $file = $request->file()['profile_img'];
                    $filename = "";
                    $filename = time() . '-' . $file->getClientOriginalName();
                    $filename = str_replace(" ", "-", $filename);
                    $path = public_path('img/profileimage/' . $filename);
                    Image::make($file->getRealPath())->save($path);
                    $response['profile_img'] = url('/public/img/profileimage/' . $filename);
                    if (!empty($filename)) {
                        DB::table('users')
                                ->where('id', $user->id)
                                ->update(['profile_photo' => $filename, 'updated_at' => date('Y-m-d h:i:s', time())]);
                    }
                } else {
                    $response['code'] = 401;
                    $response['message'] = "Please enter required details";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "No such registered user found";
            }
        } else {
            return $token;
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    /* -----------------update profile Details  ------------------- */

    public function updateProfile(Request $request) {
        $token = $this->validateToken($this->token);
        $response = [];
        if ($token == "varified") {
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
            if ($user) {
                $user->title = (isset($request->title)) ? $request->title : null;
                $user->name = (isset($request->first_name)) ? $request->first_name : null;
                $user->family_name = (isset($request->family_name)) ? $request->family_name : null;
                $user->country_name = (isset($request->country_name)) ? $request->country_name : null;
                $user->country_code = (isset($request->country_code)) ? $request->country_code : null;
                $user->mobile_number = (isset($request->mobile_number)) ? $request->mobile_number : null;
                $user->voucher_email = (isset($request->voucher_email)) ? $request->voucher_email : null;
                $user->save();
                $response['code'] = 200;
                $response['message'] = "Users Profile updated successfully";
                $response['payload']['id'] = (int) $user->id;
                $response['payload']['email'] = $user->voucher_email;
                $response['payload']['title'] = ($user->title) ? $user->title : "";
                $response['payload']['family_name'] = ($user->family_name) ? $user->family_name : "";
                $response['payload']['name'] = ($user->name) ? $user->name : "";
                $response['payload']['country_name'] = ($user->country_name) ? $user->country_name : "";
                $response['payload']['voucher_email'] = ($user->voucher_email) ? $user->voucher_email : $user->email;
                $response['payload']['country_code'] = ($user->country_code) ? $user->country_code : "";
                $response['payload']['phone'] = ($user->mobile_number) ? $user->mobile_number : "";
                $response['payload']['registration_type'] = $user->registration_type;
                $response['payload']['status'] = ($user->status != "") ? $user->status : "";
                if ($user->registration_type == 1) {
                    $response['payload']['profile_pic'] = ($user->profile_photo != "") ? url("/public/img/profileimage/" . $user->profile_photo) : "";
                } else {
                    $response['payload']['profile_pic'] = ($user->profile_photo != "") ? $user->profile_photo : "";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "No such registered user found";
            }
            return response()->json($response)->setStatusCode($response['code'], $response['message']);
        } else {
            return $token;
        }
    }

    /* -----------------Logout ------------------- */

    public function logout(Request $request) {
        $token = $this->validateToken($this->token);
        $response = [];
        if ($token == "varified") {
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
            $user->user_token = '';
            $user->device_token = '';
            $user->device_type = '';
            $user->save();
            $response['code'] = 200;
            $response['message'] = "Users have successfully logged out!";
            return response()->json($response)->setStatusCode($response['code'], $response['message']);
        } else {
            return $token;
        }
    }

    /* -----------------Get About details ------------------- */

    public function getAboutus(Request $request) {
        $AboutUs = AboutUs::first();
        if ($AboutUs != null) {
            $response['payload']['content'] = $AboutUs->content;
            $response['code'] = 200;
            $response['message'] = "About us details found successfully";
        } else {
            $response['code'] = 401;
            $response['message'] = "Please insert about us details";
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    /* -----------------Get cart details ------------------- */

    public function getCart(Request $request) {
        $token = $this->validateToken($this->token);
        $response = [];

        if ($token == "varified") {
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
            if ($user) {
                $response['code'] = 200;
                $response['message'] = "Activity carts details found successfully";
            } else {
                $response['code'] = 401;
                $response['message'] = "No such registered user found";
            }
            return response()->json($response)->setStatusCode($response['code'], $response['message']);
        } else {
            return $token;
        }
    }

    /* -----------------Whishlist List  ------------------- */

    public function wishList(Request $request) {
        $token = $this->validateToken($this->token);
        $response = [];
        if ($token == "varified") {
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
            if ($user) {
                $wishlistget = WhishList::with('activity.activitypackageoptions', 'activity.reviews.images')->where(['user_id' => $user->id])->get();
                // print_r($wishlistget->toArray());
                // exit;
                $response['code'] = 200;
                $response['message'] = "View activity wishlist";
                $response['payload'] = [];
                if ($wishlistget != null) {
                    foreach ($wishlistget as $wishlistkey => $wishlistvalue) {
                        if ($wishlistvalue->activity->whishlist->user_id == $user->id) {
                            $wishlist_value = '1';
                        } else {
                            $wishlist_value = '0';
                        }
                        $booked_activity = Orders::where('activity_id', $wishlistvalue->activity->id)->count();
                        $response['payload'][$wishlistkey]['id'] = $wishlistvalue->activity->id;
                        $response['payload'][$wishlistkey]['total_booked'] = $booked_activity;
                        $response['payload'][$wishlistkey]['title'] = $wishlistvalue->activity->title;
                        $response['payload'][$wishlistkey]['subtitle'] = ($wishlistvalue->activity->subtitle != "") ? $wishlistvalue->activity->subtitle : "";
                        $response['payload'][$wishlistkey]['image'] = url("/public/img/activity/fullsized/" . $wishlistvalue->activity->image);
                        if (count($wishlistvalue->activity->activitypackageoptions)) {
                            $response['payload'][$wishlistkey]['actual_price'] = $wishlistvalue->activity->activitypackageoptions[0]->actual_price;
                            $response['payload'][$wishlistkey]['display_price'] = ($wishlistvalue->activity->activitypackageoptions[0]->display_price) ? $wishlistvalue->activity->activitypackageoptions[0]->display_price : "";
                        } else {
                            $response['payload'][$wishlistkey]['actual_price'] = "";
                            $response['payload'][$wishlistkey]['display_price'] = "";
                        }
                        $counttotal = count($wishlistvalue->activity->reviews);
                        $max = 0;
                        foreach ($wishlistvalue->activity->reviews as $rate => $count) {
                            $max = $max + $count['rating'];
                        }
                        $avrage_ratting = '';
                        if (count($wishlistvalue->activity->reviews)) {
                            $avrage_ratting = $max / $counttotal;
                        }
                        $response['payload'][$wishlistkey]['total_review'] = ($counttotal != "") ? $counttotal : 0;
                        $response['payload'][$wishlistkey]['average_review'] = ($avrage_ratting != "") ? $avrage_ratting : 0;
                        $response['payload'][$wishlistkey]['wishlilst'] = $wishlist_value;
                    }
                } else {
                    $response['code'] = 401;
                    $response['message'] = "No whish list founded";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "No register user found";
            }
        } else {
            return $token;
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    /* -----------------Whishlist add  ------------------- */

    public function addDeleteWhishlist(Request $request) {
        $token = $this->validateToken($this->token);
        $response = [];
        if ($token == "varified") {
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
            if ($user) {
                if ($request->activity_id) {
                    $whishlistget = WhishList::where(['user_id' => $user->id, 'activity_id' => $request->activity_id])->first();
                    if ($whishlistget != null) {
                        $whishlistget->delete();
                        $response['code'] = 200;
                        $response['message'] = "Activity remove from whishlist";
                    } else {
                        $whishlist = new WhishList;
                        $whishlist->user_id = $user->id;
                        $whishlist->activity_id = $request->activity_id;
                        $whishlist->created_at = date('Y-m-d H:i:s', time());
                        $whishlist->updated_at = date('Y-m-d H:i:s', time());
                        $whishlist->save();
                        $response['code'] = 200;
                        $response['message'] = "Activity added to whishlist";
                    }
                } else {
                    $response['code'] = 401;
                    $response['message'] = "Insert activity id";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "No register user found";
            }
        } else {
            return $token;
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    /*
     *
     * push notificaiton function 
     *
     *
     * */

    // Send Notification To For Order Cancellation
    public function sendmerchantNotification($orderId, $userId, $message) {
        // if ($type == "order") {
        //     $title = "New Order #" . $orderId . " Confirmed";
        // } else if ($type == "cancel_order") {
        //     $title = "Order #" . $orderId . " Cancelled";
        // } else if ($type == "product_return") {
        //     $title = "Product Return For Order #" . $orderId;
        // } else if ($type == "payout_request") {
        //     $title = "Payout Request";
        // } else {
        //     $title = "";
        // }
        $receiver = User::where('id', $userId)->first();
        if ($receiver) {
            if ($receiver->device_type == 'android') {
                if ($receiver->device_token != null) {
                    $url = "https://fcm.googleapis.com/fcm/send";
                    $token = $receiver->device_token;
                    $serverKey = 'AAAAs9jBH8Y:APA91bFyGo3Ax-Wjt5Tc0icd1vAPrhDGUpSiCPGs0Ay8FNBzOezWyg-9lsjjiWSgC6r9Is_LLzJue2qZt2Xhd8zGViHC35eJNSnsHP0_zIfS71TL8edj8JfY1-7EEBBh5uyQty_LdFaF';
                    $body = $message;
                    $notification = array('body' => $body, 'sound' => 'default', 'badge' => '1');
                    $arrayToSend = array('to' => $token, 'notification' => $notification, 'priority' => 'high', 'data' => ['order_id' => $orderId]);
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

    public function sendcustomerNotification($orderId, $userId, $message) {
        // if ($type == "order") {
        //     $title = "New Order #" . $orderId . " Confirmed";
        // } else if ($type == "cancel_order") {
        //     $title = "Order #" . $orderId . " Cancelled";
        // } else if ($type == "product_return") {
        //     $title = "Product Return For Order #" . $orderId;
        // } else if ($type == "payout_request") {
        //     $title = "Payout Request";
        // } else {
        //     $title = "";
        // }
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

    /**
     * 
     * Get  Category list
     */
    public function categoriesList(Request $request) {
        $categories = Category::where('is_delete', 0)->get();
        $response = [];
        $response['payload'] = [];
        if (count($categories)) {
            $response['code'] = 200;
            $response['message'] = "list of categories";
            foreach ($categories as $key => $value) {
                $response['payload'][$key]['category_id'] = $value->id;
                $response['payload'][$key]['category_name'] = $value->name;
            }
        } else {
            $response['code'] = 400;
            $response['message'] = "No category data founded!!";
        }

        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    /**
     * 
     * Customer Notifications
     * 
     * 
     */
    public function customerNotification(Request $request) {
        $token = $this->validateToken($this->token);
        $response = [];
        $response['payload'] = [];
        if ($token == "varified") {
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
            if ($user) {
                if ($user->role_id == 2) {
                    if (isset($request->page) && $request->page != "") {
                        $pageNumber = $request->page;
                    } else {
                        $pageNumber = 1;
                    }
                    $notification = Notification::with('sender', 'receiver')->where('sender_id', $user->id)->orderBy('created_at', 'desc')->paginate(10, ['*'], 'page', $pageNumber);
                    if (count($notification)) {

                        foreach ($notification as $key => $value) {
                            $response['payload'][$key]['time_ago'] = $this->timecalculate(strtotime($value->created_at));
                            $response['payload'][$key]['order_status'] = $value->type;
                            $response['payload'][$key]['Message'] = $value->message;
                            $response['payload'][$key]['description'] = ($value->description) ? $value->description : "";
                        }
                        $response['page'] = $pageNumber + 1;
                        $response['code'] = 200;
                        $response['message'] = "View your booking.";
                    } else {
                        $response['page'] = $pageNumber;
                        $response['code'] = 200;
                        $response['message'] = "No new notifications!!";
                    }
                } else {
                    $response['code'] = 401;
                    $response['message'] = "No new notifications!!";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "No notification found";
            }
        } else {
            return $token;
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    /*
     *
     *
     *
      Merchant sections **
     *
     *
     */

    /*
     * Merchant Login by Username/Email and Password
     * 
     */

    public function merchantLogin(Request $request) {
        $response = [];
        $user = DB::table('users')->where('email', $request->email)->where('is_delete', 0)->where('role_id', 3)->first();
        if ($user != null) {
            if ($user->registration_type == 1) {
                if (\Hash::check($request->password, $user->password)) { // Check for password is correct or not
                    if ($user->is_delete == 0) {
                        if ($user->status == "Active") {
                            $response = $this->jwtAuthentication($request, $user);
                            if ($response['code'] == 200) {
                                DB::table('users')->where('id', $user->id)->update(['device_token' => $request->device_token, 'device_type' => $request->device_type]);
                            }
                        } else { // Account has been deactivated
                            $response['code'] = 401;
                            $response['message'] = "Your account has been deactivated. Please contact san app management.";
                        }
                    } else {
                        $response['code'] = 401;
                        $response['message'] = "This user has been deleted";
                    }
                } else { // Password does not match
                    $response['code'] = 401;
                    $response['message'] = "Please enter valid password";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "You are registered with facebook. Please login with facebook";
            }
        } else { // No such registered user found
            $response['code'] = 401;
            $response['message'] = "No such registered user found.";
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    /*
     * Merchant Booking list
     * 
     */

    public function merchantBookinglist(Request $request) {
        $token = $this->validateToken($this->token);
        $response = [];
        if ($token == "varified") {
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
            if ($user) {
                $response['payload'] = [];
                if ($user->role_id == 3) {
                    $user_id = $user->id;
                    if (isset($request->page) && $request->page != "") {
                        $pageNumber = $request->page;
                    } else {
                        $pageNumber = 1;
                    }
                    $customer_name = $request->customer_name;
                    $category_id = $request->category_id;
                    $activity_name = $request->activity_name;
                    $query = Orders::with(['activity', 'oredr_ietms.activitypackageoptions', 'oredr_ietms.packagequantity', 'user'])->whereHas('activity', function ($query) use($user_id) {
                        $query->where('merchant_id', $user_id);
                    });
                    if (isset($activity_name) && $activity_name != '') {
                        $query = $query->whereHas('activity', function ($query) use($activity_name) {
                            if (isset($activity_name) && $activity_name != '') {
                                $query->where('title', 'LIKE', "%$activity_name%");
                            }
                        });
                    }
                    if (isset($customer_name) || !empty($customer_name)) {
                        $query = $query->whereHas('user', function ($query) use($customer_name) {
                            if (isset($customer_name) && $customer_name != '') {
                                $query->where('name', 'LIKE', "%$customer_name%");
                            }
                        });
                    }
                    if (isset($category_id) || !empty($category_id)) {
                        $query = $query->whereHas('activity', function ($query) use($category_id) {
                            if (isset($category_id) && $category_id != '') {
                                $query->where('category_id', $category_id);
                            }
                        });
                    }
                    if (isset($request->from_date) && $request->from_date != "" && isset($request->to_date) && $request->to_date != "") {
                        $query = $query->whereBetween('booking_date', [$request->from_date, $request->to_date]);
                    }
                    if (isset($request->to_date) || empty($request->to_date)) {
                        if (isset($request->from_date) && $request->from_date != "") {
                            $query = $query->where('booking_date', '>=', $request->from_date);
                        }
                    }
                    if (isset($request->order_number) || !empty($request->order_number)) {
                        $query = $query->where('order_number', 'LIKE', "%$request->order_number%");
                    }
                    if (isset($request->booking_status) || !empty($request->booking_status)) {
                        $query = $query->where('status', 'LIKE', "%$request->booking_status%");
                    }

                    $bookingactivity = $query->where('order_payment_status', 'Completed')->orderBy('updated_at', 'desc')->paginate(10, ['*'], 'page', $pageNumber);
                    if (count($bookingactivity)) {
                        foreach ($bookingactivity as $key => $value) {
                            $response['payload'][$key]['order_id'] = $value->id;
                            $response['payload'][$key]['order_number'] = $value->order_number;
                            $response['payload'][$key]['order_payment_status'] = $value->order_payment_status;
                            $response['payload'][$key]['customer_id'] = $value->user->id;
                            $response['payload'][$key]['customer_name'] = $value->user->name;
                            $response['payload'][$key]['customer_contact_number'] = ($value->user->mobile_number != "") ? $value->user->country_code . " " . $value->user->mobile_number : "--";
                            $response['payload'][$key]['customer_email'] = $value->user->email;
                            if ($value->user->registration_type == 1) {
                                $response['payload'][$key]['profile_pic'] = ($value->user->profile_photo != "") ? url("/public/img/profileimage/" . $value->user->profile_photo) : "";
                            } else {
                                $response['payload'][$key]['profile_pic'] = ($value->user->profile_photo != "") ? $value->user->profile_photo : "";
                            }
                            $response['payload'][$key]['order_number'] = $value->order_number;
                            $response['payload'][$key]['booking_date'] = date_format($value->created_at, 'Y-m-d');
                            $response['payload'][$key]['participation_date'] = $value->booking_date;
                            $response['payload'][$key]['total_price'] = $value->order_total;
                            $response['payload'][$key]['status'] = $value->status;
                            $response['payload'][$key]['activity_id'] = $value->activity->id;
                            $response['payload'][$key]['activity_name'] = $value->activity->title;
                            $response['payload'][$key]['category_id'] = $value->activity->category->id;
                            $response['payload'][$key]['category'] = $value->activity->category->name;
                            $response['payload'][$key]['activity_image'] = url("/public/img/activity/fullsized/" . $value->activity->image);
                            foreach ($value->oredr_ietms as $orderkey => $ordervalue) {
                                $response['payload'][$key]['package_title'] = $ordervalue->activitypackageoptions->package_title;
                                $response['payload'][$key]['package_id'] = $ordervalue->activitypackageoptions->id;
                                $response['payload'][$key]['packagequantity'][$orderkey]['quantity'] = $ordervalue->quantity;
                                $response['payload'][$key]['packagequantity'][$orderkey]['quantity_id'] = $ordervalue->packagequantity->id;
                                $response['payload'][$key]['packagequantity'][$orderkey]['quantity_name'] = $ordervalue->packagequantity->name;
                            }
                        }

                        $response['page'] = $pageNumber + 1;
                        $response['code'] = 200;
                        $response['message'] = "View your booking.";
                    } else {
                        $response['page'] = $pageNumber;
                        $response['code'] = 200;
                        $response['message'] = "No booking...yet";
                    }
                } else {
                    $response['code'] = 401;
                    $response['message'] = "No booking data found";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "No register user found";
            }
        } else {
            return $token;
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    /**
     * 
     * Merchant booking congfrmation and canellation
     */
    public function merchantBookingStatus(Request $request) {
        $token = $this->validateToken($this->token);
        $response = [];
        if ($token == "varified") {
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
            if ($user) {
                $response['payload'] = [];
                if ($user->role_id == 3) {
                    $merchant = new MerchantController();
                    $orderId = Orders::with('activity', 'user')->where('order_payment_status', 'Completed')->where('id', $request->order_id)->first();
                    $orderId->status = $request->status;
                    $orderId->status_date = date("Y-m-d");
                    $orderId->voucher_number = "SAN" . date('dm') . rand('6', '999999');
                    $orderId->save();
                    if ($request->status == 1) {
                        $orderstatus = "Canceled";
                        $merchant->sendBookingCancelEmail($orderId);
                        $merchant->saveRefundEntry($orderId->id, $orderId->customer_id);
                    } else {
                        $orderstatus = "Confirmed";
                        $merchant->sendBookingConfirmEmail($orderId);
                    }
                    $message = "Your " . $orderId->activity->title . " has been " . $orderstatus . " for " . $orderId->booking_date . ".";
                    $notification = new Notification;
                    $notification->sender_id = $orderId->customer_id;
                    $notification->receiver_id = $orderId->activity->merchant_id;
                    $notification->message = $message;
                    $notification->type = $request->status;
                    $notification->created_at = date('Y-m-d H:i:s', time());
                    $notification->updated_at = date('Y-m-d H:i:s', time());
                    $notification->save();
                    $this->sendcustomerNotification($request->order_id, $orderId->customer_id, $message);
                    $response['code'] = 200;
                    $response['message'] = "Order has been " . $orderstatus;
                } else {
                    $response['code'] = 401;
                    $response['message'] = "No register user found";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "No register user found";
            }
        } else {
            return $token;
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    /**
     * 
     * Booking Notifications
     * 
     */
    public function timecalculate($time) {

        $time = time() - $time; // to get the time since that moment
        $time = ($time < 1) ? 1 : $time;
        $tokens = array(
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );
        foreach ($tokens as $unit => $text) {
            if ($time < $unit)
                continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
        }
    }

    public function bookingNotification(Request $request) {

        $token = $this->validateToken($this->token);
        $response = [];
        if ($token == "varified") {
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
            if ($user) {
                $response['payload'] = [];
                if ($user->role_id == 3) {
                    if (isset($request->page) && $request->page != "") {
                        $pageNumber = $request->page;
                    } else {
                        $pageNumber = 1;
                    }
                    $notification = Notification::with('sender', 'receiver')->where('receiver_id', $user->id)->orderBy('created_at', 'desc')->paginate(10, ['*'], 'page', $pageNumber);
                    if (count($notification)) {
                        foreach ($notification as $key => $value) {
                            $time = date('Gi.s', strtotime($value->created_at));
                            $delta_time = time() - strtotime($value->created_at);
                            $hours = floor($delta_time / 3600);
                            $delta_time %= 3600;
                            $minutes = floor($delta_time / 60);



                            $response['payload'][$key]['customer_id'] = $value->sender->id;
                            $response['payload'][$key]['customre_name'] = $value->sender->name;
                            $response['payload'][$key]['customre_email'] = $value->sender->email;
                            $response['payload'][$key]['time_ago'] = $this->timecalculate(strtotime($value->created_at));
                            if ($value->sender->registration_type == 1) {
                                $response['payload'][$key]['profile_pic'] = ($value->sender->profile_photo != "") ? url("/public/img/profileimage/" . $value->sender->profile_photo) : "";
                            } else {
                                $response['payload'][$key]['profile_pic'] = ($value->sender->profile_photo != "") ? $value->sender->profile_photo : "";
                            }
                            $response['payload'][$key]['Message'] = $value->message;
                        }
                        $response['page'] = $pageNumber + 1;
                        $response['code'] = 200;
                        $response['message'] = "View your booking.";
                    } else {
                        $response['page'] = $pageNumber;
                        $response['code'] = 200;
                        $response['message'] = "No new notifications!!";
                    }
                } else {
                    $response['code'] = 401;
                    $response['message'] = "No new notifications!!";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "No register user found";
            }
        } else {
            return $token;
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    /*
     *
     * Booking sales report
     * *
     */

    public function bookingSalesReport(Request $request) {

        $token = $this->validateToken($this->token);
        $response = [];
        if ($token == "varified") {
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
            if ($user) {
                $response['payload'] = [];
                if ($user->role_id == 3) {
                    $user_id = $user->id;
                    $activity_name = $request->activity_name;
                    $customer_name = $request->customer_name;
                    $category_id = $request->category_id;
                    if (isset($request->page) && $request->page != "") {
                        $pageNumber = $request->page;
                    } else {
                        $pageNumber = 1;
                    }
                    $query = Orders::with(['activity.category', 'oredr_ietms.activitypackageoptions', 'oredr_ietms.packagequantity', 'user'])->whereHas('activity', function ($query) use($user_id, $activity_name) {
                        $query->where('merchant_id', $user_id);
                    });
                    if (isset($activity_name) && $activity_name != '') {
                        $query = $query->whereHas('activity', function ($query) use($activity_name) {
                            if (isset($activity_name) && $activity_name != '') {
                                $query->where('title', 'LIKE', "%$activity_name%");
                            }
                        });
                    }
                    if (isset($customer_name) || !empty($customer_name)) {
                        $query = $query->whereHas('user', function ($query) use($customer_name) {
                            if (isset($customer_name) && $customer_name != '') {
                                $query->where('name', 'LIKE', "%$customer_name%");
                            }
                        });
                    }
                    if (isset($category_id) || !empty($category_id)) {
                        $query = $query->whereHas('activity', function ($query) use($category_id) {
                            if (isset($category_id) && $category_id != '') {
                                $query->where('category_id', $category_id);
                            }
                        });
                    }
                    if (isset($request->from_date) && $request->from_date != "" && isset($request->to_date) && $request->to_date != "") {
                        $startdate = Carbon::parse($request->from_date)->startOfDay();
                        $enddate = Carbon::parse($request->to_date)->endOfDay();
                        $query = $query->whereBetween('booking_date', [$startdate, $enddate]);
                    }
                    if (isset($request->to_date) || empty($request->to_date)) {
                        if (isset($request->from_date) && $request->from_date != "") {
                            $query = $query->where('booking_date', '>=', $request->from_date);
                        }
                    }
                    if (isset($request->order_number) || !empty($request->order_number)) {
                        $query = $query->where('order_number', 'LIKE', "%$request->order_number%");
                    }
                    $bookingactivity = $query->where('status', 2)->orderBy('updated_at', 'desc')->paginate(10, ['*'], 'page', $pageNumber);
                    $response['total_earn'] = $bookingactivity->sum('order_total');
                    if (count($bookingactivity)) {
                        foreach ($bookingactivity as $key => $value) {
                            $response['payload'][$key]['order_id'] = $value->id;
                            $response['payload'][$key]['order_number'] = $value->order_number;
                            $response['payload'][$key]['order_payment_status'] = $value->order_payment_status;
                            $response['payload'][$key]['customer_id'] = $value->user->id;
                            $response['payload'][$key]['customer_name'] = $value->user->name;
                            if ($value->user->registration_type == 1) {
                                $response['payload'][$key]['profile_pic'] = ($value->user->profile_photo != "") ? url("/public/img/profileimage/" . $value->user->profile_photo) : "";
                            } else {
                                $response['payload'][$key]['profile_pic'] = ($value->user->profile_photo != "") ? $value->user->profile_photo : "";
                            }
                            $response['payload'][$key]['booking_date'] = date_format($value->created_at, 'Y-m-d');
                            $response['payload'][$key]['participation_date'] = $value->booking_date;
                            $response['payload'][$key]['total_price'] = $value->order_total;
                            $response['payload'][$key]['activity_id'] = $value->activity->id;
                            $response['payload'][$key]['activity_name'] = $value->activity->title;
                            $response['payload'][$key]['category_id'] = $value->activity->category->id;
                            $response['payload'][$key]['category_name'] = $value->activity->category->name;
                            $response['payload'][$key]['activity_image'] = url("/public/img/activity/fullsized/" . $value->activity->image);
                            foreach ($value->oredr_ietms as $orderkey => $ordervalue) {
                                $response['payload'][$key]['package_title'] = $ordervalue->activitypackageoptions->package_title;
                                $response['payload'][$key]['package_id'] = $ordervalue->activitypackageoptions->id;
                                $response['payload'][$key]['packagequantity'][$orderkey]['quantity'] = $ordervalue->quantity;
                                $response['payload'][$key]['packagequantity'][$orderkey]['quantity_id'] = $ordervalue->packagequantity->id;
                                $response['payload'][$key]['packagequantity'][$orderkey]['quantity_name'] = $ordervalue->packagequantity->name;
                            }
                        }

                        $response['page'] = $pageNumber + 1;
                        $response['code'] = 200;
                        $response['message'] = "View your booking.";
                    } else {
                        $response['page'] = $pageNumber;
                        $response['code'] = 200;
                        $response['message'] = "No booking data found ";
                    }
                } else {
                    $response['code'] = 401;
                    $response['message'] = "No booking data found";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "No register user found";
            }
        } else {
            return $token;
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    public function merchantActivityList(Request $request) {

        $token = $this->validateToken($this->token);
        $response = [];
        if ($token == "varified") {
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
            if ($user) {
                $response['payload'] = [];
                if ($user->role_id == 3) {
                    $user_id = $user->id;
                    $activity_id = $request->activity_id;
                    $searchterm = $request->searchterm;
                    $category_id = $request->category;
                    $location_id = $request->location;
                    $statusactivity = $request->statusactivity;
                    if (isset($request->page) && $request->page != "") {
                        $pageNumber = $request->page;
                    } else {
                        $pageNumber = 1;
                    }
                    // $query = Orders::with(['activity','oredr_ietms.activitypackageoptions','oredr_ietms.packagequantity','user'])->whereHas('activity', function ($query) use($user_id,$activity_id){
                    //     $query->where('merchant_id', $user_id);
                    //     if(isset($activity_id) && $activity_id != ''){
                    //         $query->where('activity_id', $activity_id);
                    //     }
                    // });
                    $query = Activity::with(['category', 'merchant', 'city', 'reviews'])->where('is_delete', 0)->where('merchant_id', $user_id);

                    if ($category_id != null) {
                        $query->where('category_id', $category_id);
                    }
                    if ($location_id != null) {
                        $query->where('city_id', $location_id);
                    }
                    if ($statusactivity != null) {
                        $query->where('admin_approve', $statusactivity);
                    }
                    $query->where('is_delete', 0)
                            ->where(function ($query) use($searchterm) {
                                $query->orWhere('title', 'LIKE', "%$searchterm%");
                                $query->orWhere('actual_price', 'LIKE', "%$searchterm%");
                                $query->orWhere('display_price', 'LIKE', "%$searchterm%");
                                $query->orWhere('status', 'LIKE', "%$searchterm%");
                                $query->orWhere('created_at', 'LIKE', "%$searchterm%");
                                $query->orWhereHas('category', function ($query) use($searchterm) {
                                    $query->where('name', 'LIKE', "%$searchterm%");
                                });
                                $query->orWhereHas('merchant', function ($query) use($searchterm) {
                                    $query->where('name', 'LIKE', "%$searchterm%");
                                });
                                $query->orWhereHas('subcategory', function ($query) use($searchterm) {
                                    $query->where('name', 'LIKE', "%$searchterm%");
                                });
                            });
                    $merchantactivity = $query->orderBy('updated_at', 'desc')->paginate(5, ['*'], 'page', $pageNumber);
                    // $query = Activity::with(['category', 'merchant','city'])->where('is_delete', 0)->where('merchant_id' , $user_id);
                    // $merchantactivity = $query->orderBy('updated_at', 'desc')->paginate(5, ['*'], 'page', $pageNumber);
                    // print_r($merchantactivity->toArray());
                    if (count($merchantactivity)) {
                        foreach ($merchantactivity as $key => $value) {
                            $booked_activity = Orders::where('activity_id', $value->id)->count();
                            $response['payload'][$key]['id'] = $value->id;
                            $response['payload'][$key]['total_booked'] = $booked_activity;
                            $response['payload'][$key]['merchant_id'] = $value->merchant_id;
                            $response['payload'][$key]['title'] = $value->title;
                            $response['payload'][$key]['subtitle'] = ($value->subtitle) ? $value->subtitle : "";
                            $response['payload'][$key]['image'] = url("/public/img/activity/fullsized/" . $value->image);
                            if (count($value->activitypackageoptions)) {
                                $response['payload'][$key]['actual_price'] = $value->activitypackageoptions[0]->actual_price;
                                $response['payload'][$key]['display_price'] = ($value->activitypackageoptions[0]->display_price) ? $value->activitypackageoptions[0]->display_price : $value->activitypackageoptions[0]->actual_price;
                            } else {
                                $response['payload'][$key]['actual_price'] = "";
                                $response['payload'][$key]['display_price'] = "";
                            }
                            $response['payload'][$key]['city_id'] = ($value->city->id) ? $value->city->id : "";
                            $response['payload'][$key]['city'] = ($value->city->city) ? $value->city->city : "";
                            $response['payload'][$key]['category_id'] = $value->category->id;
                            $response['payload'][$key]['category'] = $value->category->name;
                            $response['payload'][$key]['admin_approve'] = $value->admin_approve;
                            $response['payload'][$key]['created_date'] = date('Y-m-d', strtotime($value->created_at));
                            $counttotal = count($value->reviews);
                            $max = 0;
                            foreach ($value->reviews as $rate => $count) {
                                $max = $max + $count['rating'];
                            }
                            $avrage_ratting = '';
                            if (count($value->reviews)) {
                                $avrage_ratting = $max / $counttotal;
                            }
                            $response['payload'][$key]['total_review'] = ($counttotal != "") ? $counttotal : 0;
                            $response['payload'][$key]['average_review'] = ($avrage_ratting != "") ? $avrage_ratting : 0;
                        }
                        $response['page'] = $pageNumber + 1;
                        $response['code'] = 200;
                        $response['message'] = "View your booking.";
                    } else {
                        $response['page'] = $pageNumber;
                        $response['code'] = 200;
                        $response['message'] = "No activity booking found ";
                    }
                } else {
                    $response['code'] = 401;
                    $response['message'] = "No booking data found";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "No register user found";
            }
        } else {
            return $token;
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

    /* Send booking note to customer */

    public function addBookingNote(Request $request) {
        $token = $this->validateToken($this->token);
        $response = [];
        if ($token == "varified") {
            $user = User::whereNotNull('user_token')->where('user_token', '=', $this->token)->first();
            if ($user) {
                $order = Orders::with('user', 'activity')->where('order_number', $request->order_number)->first();
                if (count($order)) {
                    $template = EmailTemplate::where('name', 'booking-note')->first();
                    if ($template != null) {

                        $title = $request->title;
                        $description = $request->description;
                        $notification = new Notification;
                        $notification->sender_id = $order->customer_id;
                        $notification->receiver_id = $order->activity->merchant_id;
                        $notification->message = $title;
                        $notification->description = $description;
                        $notification->created_at = date('Y-m-d H:i:s', time());
                        $notification->updated_at = date('Y-m-d H:i:s', time());
                        $notification->save();
                        $this->sendcustomerNotification($order->id, $order->customer_id, $title);

                        $orderdata['order_number'] = $order->order_number;
                        $orderdata['customer'] = $order->user->name;
                        $orderdata['email'] = $order->user->email;
                        $orderdata['title'] = $request->title;
                        $orderdata['description'] = $request->description;
                        Mail::send([], [], function($message) use ($template, $orderdata) {
                            $data = [
                                'customer' => ucfirst($orderdata['customer']),
                                'title' => $orderdata['title'],
                                'description' => $orderdata['description']
                            ];
                            $message->to($orderdata['email'], ucfirst($orderdata['customer']))
                                    ->subject($template->subject . $orderdata['order_number'] . " Booking Note")
                                    ->setBody($template->parse($data), 'text/html');
                        });
                        $response['code'] = 200;
                        $response['message'] = "Booking note has been sent to cutomer";
                    }
                } else {
                    $response['code'] = 401;
                    $response['message'] = "No order found";
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "No register user found";
            }
        } else {
            return $token;
        }
        return response()->json($response)->setStatusCode($response['code'], $response['message']);
    }

}
