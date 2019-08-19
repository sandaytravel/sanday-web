<?php

namespace App\Modules\Activity\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Generalpolicy;
use App\Category;
use App\Subcategory;
use App\Activity;
use App\ActivityPackageOptions;
use App\ActivityPolicy;
use App\Activityfaqs;
use App\City;
use App\Citycategory;
use App\EmailTemplate;
use App\User;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\ActivitypackageQuantity;
use Intervention\Image\ImageManagerStatic as Image;
use Session;

class ActivityController extends Controller {
    /*
     * List of Activities
     * 
     */

    public function index() {
        if (Auth::user()->can('activities', 'read') && Auth::user()->role_id == 1) {
            $activities = Activity::with(['category', 'subcategory', 'merchant'])->where('is_delete', 0)->orderBy('created_at', 'DESC')->paginate(10);
            $categories = Category::where(['status' => 'Active', 'is_delete' => 0])->pluck('name', 'id')->toArray();
            $location = City::where(['status' => 'Active', 'is_delete' => 0])->pluck('city', 'id')->toArray();
            $statusactivity = array('0' => 'Draft',
                '1' => 'Published',);
            $packageoptionarray = ActivityPackageOptions::where('is_delete', 0)->groupBy('activity_id')->pluck('activity_id')->toArray();
            return view('activity::index', compact('activities', 'categories', 'location', 'statusactivity', 'packageoptionarray'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Search Activity
     * 
     */

    public function catLocationserach(Request $request) {
        if (Auth::user()->can('activities', 'read') && Auth::user()->role_id == 1) {
            $query = Activity::with(['category', 'subcategory']);
            if ($request->get('category') != null) {
                $query->where('category_id', $request->get('category'));
            }
            if ($request->get('location') != null) {
                $query->where('city_id', $request->get('location'));
            }
            $query->where('is_delete', 0);
            $activities = $query->orderBy('created_at', 'desc')->paginate(10);

            $activities->appends(['location' => $request->get('location'), 'category' => $request->get('category')])->render(); // Append query string to URL
            $categories = Category::where(['status' => 'Active', 'is_delete' => 0])->pluck('name', 'id')->toArray();
            $location = City::where(['status' => 'Active', 'is_delete' => 0])->pluck('city', 'id')->toArray();
            // $statusactivity = array('0' => 'Pending',
            // '1' => 'Approve',
            // '2' => 'Decline');
            $statusactivity = array('0' => 'Draft',
                '1' => 'Published',);
            return view('activity::index', compact('activities', 'categoryname', 'categories', 'location', 'statusactivity'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /* ---Delete activity--------- */

    // Delete multiple activites
    public function multipleDelete(Request $request) {
        $ids = $request->get('ids');
        if ($ids) {
            $activity = explode(',', $ids);
            for ($i = 0; $i < count($activity); $i++) {
                DB::table('activity')->where('id', $activity[$i])->update([
                    'is_delete' => 1
                ]);
            }
            Session::flash('success', 'Activty has been deleted successfully.');
            $data['success'] = true;
            $data['message'] = 'Activty has been deleted successfully.';
        } else {
            $data['success'] = false;
            $data['message'] = 'some error during deleting process.';
        }
        return response()->json([$data]);
    }

    // Multiple activites status change
    public function multipleStatus(Request $request) {
        $status = $request->get('status');
        $ids = $request->get('ids');
        if ($ids) {
            $activity = explode(',', $ids);
            for ($i = 0; $i < count($activity); $i++) {
                DB::table('activity')->where('id', $activity[$i])->update([
                    'status' => $status
                ]);
            }
            Session::flash('success', 'Activty has been ' . $status . ' successfully.');
            $data['success'] = true;
            $data['message'] = 'Activty has been ' . $status . ' successfully.';
        } else {
            $data['success'] = false;
            $data['message'] = 'some error during ' . $status . ' process.';
        }
        return response()->json([$data]);
    }

    /*
     * Search Activity
     * 
     */

    public function searchActivity(Request $request) {
        if (Auth::user()->can('activities', 'read') && Auth::user()->role_id == 1) {
            $searchterm = $request->get('searchterm');
            $category_id = $request->get('category');
            $location_id = $request->get('location');
            $statusactivity = $request->get('statusactivity');
            $query = Activity::with(['category', 'subcategory', 'merchant']);
            if (Auth::user()->role_id == 3) {
                $query = $query->where('merchant_id', Auth::user()->id);
            }
            if ($request->get('category') != null) {
                $query->where('category_id', $request->get('category'));
            }
            if ($request->get('location') != null) {
                $query->where('city_id', $request->get('location'));
            }
            if ($request->get('statusactivity') != null) {
                $query->where('admin_approve', $request->get('statusactivity'));
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
            $activities = $query->orderBy('created_at', 'desc')->paginate(10);

            $activities->appends(['searchterm' => $searchterm])->render(); // Append query string to URL
            $categories = Category::where(['status' => 'Active', 'is_delete' => 0])->pluck('name', 'id')->toArray();
            $location = City::where(['status' => 'Active', 'is_delete' => 0])->pluck('city', 'id')->toArray();
            // $statusactivity = array('0' => 'Pending',
            // '1' => 'Approve',
            // '2' => 'Decline');
            $statusactivity = array('0' => 'Draft',
                '1' => 'Published',);
            $packageoptionarray = ActivityPackageOptions::where('is_delete', 0)->groupBy('activity_id')->pluck('activity_id')->toArray();
            return view('activity::index', compact('activities', 'searchterm', 'categories', 'location', 'category_id', 'location_id', 'statusactivity', 'packageoptionarray'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Add Activity
     * 
     */

    public function addActivity(Request $request) {
        if (Auth::user()->can('activities', 'write') && Auth::user()->role_id == 1) {
            if ($request->isMethod('POST')) {
                $activity = $this->saveActivity($request);

                if ($activity != null) {
                    /* Save Package Details */
                    $this->savePackages($request, $activity->id);
                    /* End Save Package Details */
                    if (count($request->general_policy)) {
                        $this->savePolicy($request, $activity->id);
                    }
                    $this->saveFAQ($request->question, $request->answer, $activity->id);

                    Session::flash('success', 'Activity has been saved successfully');
                    return redirect()->route('activity');
                } else {
                    Session::flash('error', 'Activity could not save. Please try again');
                    return redirect()->route('activity');
                }
            } else {
                $categories = Category::where(['status' => 'Active', 'is_delete' => 0])->pluck('name', 'id')->toArray();
                $generalpolicies = Generalpolicy::where(['is_activity_policy' => 0])->where(['is_delete' => 0])->pluck('name', 'id')->toArray();
                $cities = City::where(['is_delete' => 0])->pluck('city', 'id')->toArray();
                $merchant = User::where(['is_delete' => 0, 'status' => 'Active', 'role_id' => 3])->pluck('name', 'id')->toArray();
                // echo '<pre>';
                // print_r($merchant);
                // print_r(array_merge($merchant,array("a"=>"Admin")));
                // // print_r($merchant['0'] ='Admin');
                // exit;
                return view('activity::add_activity', compact('categories', 'generalpolicies', 'cities', 'merchant'));
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    public function saveFAQ($questions, $answers, $activityId) {
        Activityfaqs::where('activity_id', $activityId)->delete();
        if (count($questions)) {
            foreach ($questions as $key => $value) {
                if ($value != "" && $answers[$key] != "") {
                    $faqs = new Activityfaqs();
                    $faqs->activity_id = $activityId;
                    $faqs->question = $value;
                    $faqs->answer = $answers[$key];
                    $faqs->created_at = date('Y-m-d H:i:s', time());
                    $faqs->updated_at = date('Y-m-d H:i:s', time());
                    $faqs->save();
                }
            }
        }
        return;
    }

    public function savePolicy($request, $activityId) {
        ActivityPolicy::where('activity_id', $activityId)->delete();
        /* Add General Policy Into Activity */
        Generalpolicy::where('activity_id', $activityId)->where('is_activity_policy', 1)->whereNotIn('id', $request->general_policy)->delete();
        foreach ($request->general_policy as $key => $value) {
            $policy = new ActivityPolicy();
            $policy->activity_id = $activityId;
            $policy->policy_id = $value;
            $policy->created_at = date('Y-m-d H:i:s', time());
            $policy->updated_at = date('Y-m-d H:i:s', time());
            $policy->save();
        }
        /* Add Activity Policy Into General Policy */
        if (count($request->activity_policy)) {
            foreach ($request->activity_policy as $key1 => $value1) {
                $generalPolicy = new Generalpolicy();
                $generalPolicy->activity_id = $activityId;
                $generalPolicy->name = $request->activity_policy_names[$key1];
                $generalPolicy->icon = $request->activity_policy_filenames[$key1];
                $generalPolicy->is_activity_policy = 1;
                $generalPolicy->created_at = date('Y-m-d H:i:s', time());
                $generalPolicy->updated_at = date('Y-m-d H:i:s', time());
                $generalPolicy->save();

                $activityPolicy = new ActivityPolicy();
                $activityPolicy->activity_id = $activityId;
                $activityPolicy->policy_id = $generalPolicy->id;
                $activityPolicy->created_at = date('Y-m-d H:i:s', time());
                $activityPolicy->updated_at = date('Y-m-d H:i:s', time());
                $activityPolicy->save();
            }
        }
        return;
    }

    public function savePackages($request, $activityId) {
        if (count($request->package_title)) {
            foreach ($request->package_title as $key => $value) {
                if ($value != "") {
                    if ($request->package_ids[$key] != "empty") {
                        $package = ActivityPackageOptions::where('id', $request->package_ids[$key])->first();
                        if ($package != null) { // Update
                            $package->package_title = $value;
                            $package->actual_price = $request->package_actual_price[$key];
                            $package->display_price = $request->package_display_price[$key];
                            $package->description = '<div style="font-family:Airbnb Cereal">' . $request->package_description[$key] . '</div>';
                            $package->validity = $request->package_validity[$key];
                            $package->updated_at = date('Y-m-d H:i:s', time());
                            $package->save();
                        }
                    } else {
                        $package = new ActivityPackageOptions();
                        $package->activity_id = $activityId;
                        $package->package_title = $request->package_title[$key];
                        $package->actual_price = $request->package_actual_price[$key];
                        $package->display_price = $request->package_display_price[$key];
                        $package->description = $request->package_description[$key];
                        $package->validity = $request->package_validity[$key];
                        $package->created_at = date('Y-m-d H:i:s', time());
                        $package->updated_at = date('Y-m-d H:i:s', time());
                        $package->save();
                    }
                }
            }
        }
        return;
    }

    public function saveActivity($request) {
        if ($request->id == "empty") {
            $activity = new Activity();
            if ($request->merchant_id != "") {
                $activity->merchant_id = $request->merchant_id;
                $activity->admin_id = Auth::user()->id;
            } else {
                $activity->merchant_id = Auth::user()->id;
                $activity->admin_id = Auth::user()->id;
            }
        } else {
            $activity = Activity::where('id', $request->id)->first();
            if ($request->merchant_id != "") {
                $activity->merchant_id = $request->merchant_id;
                ;
            } else {
                $activity->merchant_id = Auth::user()->id;
            }
        }
        $activity->city_id = $request->city;
        $activity->category_id = $request->category;
        $activity->subcategory_id = $request->subcategory;
        $activity->title = $request->title;
        $activity->subtitle = $request->subtitle;
        $activity->popular_activity = $request->popular_activity;
        $activity->popular_destination = $request->popular_destination;
        if ($request->hasFile('image')) {
            $file = $request->file()['image'];
            $filename = "";
            $filename = time() . '-' . str_replace(" ", "-", $file->getClientOriginalName());
            $path = public_path('img/activity/fullsized/' . $filename);
            $resizedPath = public_path('img/activity/resized/' . $filename);
            Image::make($file->getRealPath())->save($path);
            Image::make($file->getRealPath())->resize(256, 256)->save($resizedPath);
            $activity->image = $filename;
        }
        $activity->actual_price = $request->actual_price;
        $activity->display_price = $request->display_price;
        if ($request->id == "empty") {
            $activity->description = '<div style="font-family:Airbnb Cereal">' . $request->description . '</div>';
            $activity->is_package_options = ($request->is_package_options == "on") ? 1 : 0;
            $activity->is_what_to_expect = ($request->is_what_to_expect == "on") ? 1 : 0;
            $activity->what_to_expect_description = ($request->is_what_to_expect == "on") ? '<div style="font-family:Airbnb Cereal">' . $request->what_to_expect_description . '</div>' : "";
            $activity->is_activity_information = ($request->is_activity_information == "on") ? 1 : 0;
            $activity->activity_information_description = ($request->is_activity_information == "on") ? '<div style="font-family:Airbnb Cereal">' . $request->activity_information_description . '</div>' : "";
            $activity->is_how_to_use = ($request->is_how_to_use == "on") ? 1 : 0;
            $activity->how_to_use_description = ($request->is_how_to_use == "on") ? '<div style="font-family:Airbnb Cereal">' . $request->how_to_use_description . '</div>' : "";
            $activity->is_cancellation_policy = ($request->is_cancellation_policy == "on") ? 1 : 0;
            $activity->cancellation_policy_description = ($request->is_cancellation_policy == "on") ? '<div style="font-family:Airbnb Cereal">' . $request->cancellation_policy_description . '</div>' : "";
        } else {
            $activity->description = $request->description;
            $activity->is_package_options = ($request->is_package_options == "on") ? 1 : 0;
            $activity->is_what_to_expect = ($request->is_what_to_expect == "on") ? 1 : 0;
            $activity->what_to_expect_description = ($request->is_what_to_expect == "on") ? $request->what_to_expect_description : "";
            $activity->is_activity_information = ($request->is_activity_information == "on") ? 1 : 0;
            $activity->activity_information_description = ($request->is_activity_information == "on") ? $request->activity_information_description : "";
            $activity->is_how_to_use = ($request->is_how_to_use == "on") ? 1 : 0;
            $activity->how_to_use_description = ($request->is_how_to_use == "on") ? $request->how_to_use_description : "";
            $activity->is_cancellation_policy = ($request->is_cancellation_policy == "on") ? 1 : 0;
            $activity->cancellation_policy_description = ($request->is_cancellation_policy == "on") ? $request->cancellation_policy_description : "";
        }
        if (Auth::user()->role_id == 3) {
            if (isset($request->save_close_btn)) {
                
            } else {
                $activity->admin_approve = "0";
            }
        } else {
            // if(isset($request->save_close_btn)){
            // }else{
            //     if($request->merchant_id != ""){
            //         $activity->admin_approve = "0";
            //     }else{
            //         $activity->admin_approve = "1";
            //     }
            // }
            if (isset($request->save_close_btn)) {
                if ($request->save_close_btn == 2) {
                    $activity->admin_approve = "1";
                    if ($request->merchant_id != "") {

                        if ($activity->merchant_id == $activity->admin_id) {
                            $template = "activity-publish";
                            $this->approveActivity($request, $activity->admin_id, $template);
                        } else {
                            $template = "approve-activity";
                            $template1 = "activity-publish";
                            $this->approveActivity($request, $activity->merchant_id, $template);
                            $this->approveActivity($request, $activity->admin_id, $template1);
                        }
                    }
                }
            } else {
                if ($request->merchant_id != "") {
                    $activity->admin_approve = "0";
                    $template = "approve-activity";
                    $this->approveActivity($request, $request->merchant_id, $template);
                } else {
                    $activity->admin_approve = "0";
                }
            }
        }
        if ($request->id == "empty") {
            $activity->created_at = date('Y-m-d H:i:s', time());
        }
        $activity->updated_at = date('Y-m-d H:i:s', time());
        $activity->save();
        return $activity;
    }

    /*
     *
     * Activity approved by admin
     *
     */

    public function approveActivity($request, $merchant_id, $template) {
        $merchant = User::where('id', $merchant_id)->first();
        $dataArray = ["name" => $merchant->name, "email" => $merchant->email, "activityname" => $request->title];
        $templateResult = EmailTemplate::where('name', $template)->first();

        Mail::send([], [], function($message) use ($templateResult, $dataArray) {
            $data = [
                'toMerchantName' => $dataArray['name'],
                'activityName' => $dataArray['activityname'],
            ];
            $message->to($dataArray['email'], $dataArray['name'])
                    ->subject($templateResult->subject)
                    ->setBody($templateResult->parse($data), 'text/html');
        });
        return;
    }

    /*
     * Add Activity Policy
     * 
     */

    public function addActivityPolicy(Request $request) {
        $name = $request->name;
        $file = $request->file()['imagefile'];
        $filename = "";
        $filename = time() . '-' . str_replace(" ", "-", $file->getClientOriginalName());
        $path = public_path('img/icons/fullsized/' . $filename);
        $resizedPath = public_path('img/icons/resized/' . $filename);
        Image::make($file->getRealPath())->save($path);
        Image::make($file->getRealPath())->resize(256, 256)->save($resizedPath);
        return view('activity::addpolicy', compact('name', 'filename'))->render();
    }

    public function editActivityPolicy(Request $request) {
        if (Auth::user()->can('activities', 'write') && Auth::user()->role_id == 1) {
            if ($request->isMethod('POST')) {
                $activity = $this->saveActivity($request);
                if ($activity != null) {
                    /* Save Package Details */
                    $this->savePackages($request, $activity->id);
                    /* End Save Package Details */
                    if (count($request->general_policy)) {
                        $this->savePolicy($request, $activity->id);
                    }
                    $this->saveFAQ($request->question, $request->answer, $activity->id);

                    if ($request->save_close_btn == "0" || $request->save_close_btn == "2") {
                        Session::flash('success', 'Activity has been saved successfully');
                        return redirect()->route('activity');
                    } else {
                        Session::flash('success', 'Activity has been updated successfully');
                        return redirect()->route('updateactivity', [$request->id]);
                    }
                } else {
                    Session::flash('error', 'Activity could not save. Please try again');
                    return redirect()->route('activity');
                }
            } else {
                if (is_numeric($request->id)) {
                    if (Auth::user()->role_id == 3) {
                        $activity = Activity::with(['packageoptions' => function($query) {
                                        $query->where('is_delete', 0);
                                    }, 'policy', 'faqs'])->where('id', $request->id)->where('merchant_id', Auth::user()->id)->first();
                    } else {
                        $activity = Activity::with(['packageoptions' => function($query) {
                                        $query->where('is_delete', 0);
                                    }, 'policy', 'faqs'])->where('id', $request->id)->first();
                    }
                    if ($activity) {
                        /* For Category list */
                        $categories = Category::where(['status' => 'Active', 'is_delete' => 0])->pluck('name', 'id')->toArray();
                        $activityId = $activity->id;
                        /* For Terms and Policy */
                        $generalpolicies = Generalpolicy::where('is_delete', 0)->where(function ($query) use($activityId) {
                                    $query->where('is_activity_policy', 0);
                                    $query->orWhere('activity_id', $activityId);
                                })->get()->toArray();
                        /* For City List */
                        $cities = City::where(['is_delete' => 0])->pluck('city', 'id')->toArray();
                        /* For Subcategory list */
                        $subcategory = Subcategory::where(['category_id' => $activity->category_id, 'status' => 'Active', 'is_delete' => 0])->pluck('name', 'id')->toArray();
                        $publish_check = Activity::where('id', $activityId)->first();
                        $publish_check_array = ActivityPackageOptions::groupBy('activity_id')->pluck('activity_id')->toArray();
                        $merchant = User::where(['is_delete' => 0, 'status' => 'Active', 'role_id' => 3])->pluck('name', 'id')->toArray();
                        return view('activity::edit_activity', compact('activity', 'categories', 'subcategory', 'generalpolicies', 'cities', 'publish_check', 'publish_check_array', 'merchant'));
                    } else {
                        Session::flash('error', 'No such activity found');
                        return redirect()->route('activity');
                    }
                }
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * 
     * Set package configuration
     */

    public function setPackageConfiguration(Request $request) {
        if (Auth::user()->can('activities', 'write') && Auth::user()->role_id == 1) {
            if ($request->isMethod('POST')) {
                $setpackagedetails = $request->data;
                if ($setpackagedetails != null) {
                    foreach ($setpackagedetails as $setpackagedetailskey => $setpackagedetailsvalue) {
                        if (isset($setpackagedetailsvalue['package_id'])) {
                            $package_check = $setpackagedetailsvalue['package_id'];
                        } else {
                            $package_check = '';
                        }
                        if ($package_check != null) {
                            $ActivityPackageOptions = ActivityPackageOptions::where('id', $package_check)->where('is_delete', 0)->first();
                            $ActivityPackageOptions->activity_id = $request->id;
                            $ActivityPackageOptions->package_title = $setpackagedetailsvalue['package_title'];
                            $ActivityPackageOptions->actual_price = $setpackagedetailsvalue['package_actual_price'];
                            $ActivityPackageOptions->display_price = $setpackagedetailsvalue['package_display_price'];
                            $ActivityPackageOptions->description = $setpackagedetailsvalue['package_description'];
                            $ActivityPackageOptions->validity = $setpackagedetailsvalue['package_validity'];
                            $ActivityPackageOptions->updated_at = date('Y-m-d H:i:s', time());
                            $ActivityPackageOptions->save();
                            if (count($setpackagedetailsvalue)) {
                                foreach ($setpackagedetailsvalue['child']['booking_title'] as $setquntitykey => $setquntityvalue) {
                                    if ($setquntityvalue != "") {
                                        if (isset($setpackagedetailsvalue['child']['booking_id'][$setquntitykey])) {
                                            $packageQuantity = ActivitypackageQuantity::where('activity_package_id', $package_check)->where('id', $setpackagedetailsvalue['child']['booking_id'][$setquntitykey])->where('is_delete', 0)->first();
                                        } else {
                                            $packageQuantity = new ActivitypackageQuantity;
                                        }
                                        $packageQuantity->activity_package_id = $ActivityPackageOptions->id;
                                        $packageQuantity->name = $setquntityvalue;
                                        $packageQuantity->minimum_quantity = $setpackagedetailsvalue['child']['minimum_quantity'][$setquntitykey];
                                        $packageQuantity->maximum_quantity = $setpackagedetailsvalue['child']['maximum_quantity'][$setquntitykey];
                                        $packageQuantity->actual_price = $setpackagedetailsvalue['child']['booking_actual_price'][$setquntitykey];
                                        $packageQuantity->display_price = $setpackagedetailsvalue['child']['booking_display_price'][$setquntitykey];
                                        $packageQuantity->created_at = date('Y-m-d H:i:s', time());
                                        $packageQuantity->updated_at = date('Y-m-d H:i:s', time());
                                        $packageQuantity->save();
                                    }
                                }
                            }
                        } else {
                            $ActivityPackageOptions = new ActivityPackageOptions();
                            $ActivityPackageOptions->activity_id = $request->id;
                            $ActivityPackageOptions->package_title = $setpackagedetailsvalue['package_title'];
                            $ActivityPackageOptions->actual_price = $setpackagedetailsvalue['package_actual_price'];
                            $ActivityPackageOptions->display_price = $setpackagedetailsvalue['package_display_price'];
                            $ActivityPackageOptions->description = (isset($setpackagedetailsvalue['package_description'])) ? $setpackagedetailsvalue['package_description'] : "";
                            $ActivityPackageOptions->validity = $setpackagedetailsvalue['package_validity'];
                            $ActivityPackageOptions->created_at = date('Y-m-d H:i:s', time());
                            $ActivityPackageOptions->updated_at = date('Y-m-d H:i:s', time());
                            $ActivityPackageOptions->save();

                            if (count($setpackagedetailsvalue)) {
                                foreach ($setpackagedetailsvalue['child']['booking_title'] as $setquntitykey => $setquntityvalue) {
                                    if ($setquntityvalue != "") {
                                        $packageQuantity = new ActivitypackageQuantity();
                                        $packageQuantity->activity_package_id = $ActivityPackageOptions->id;
                                        $packageQuantity->name = $setquntityvalue;
                                        $packageQuantity->minimum_quantity = $setpackagedetailsvalue['child']['minimum_quantity'][$setquntitykey];
                                        $packageQuantity->maximum_quantity = $setpackagedetailsvalue['child']['maximum_quantity'][$setquntitykey];
                                        $packageQuantity->actual_price = $setpackagedetailsvalue['child']['booking_actual_price'][$setquntitykey];
                                        $packageQuantity->display_price = $setpackagedetailsvalue['child']['booking_display_price'][$setquntitykey];
                                        $packageQuantity->created_at = date('Y-m-d H:i:s', time());
                                        $packageQuantity->updated_at = date('Y-m-d H:i:s', time());
                                        $packageQuantity->save();
                                    }
                                }
                            }
                        }
                    }
                    Session::flash('success', 'Package configuration has been set successfully');
                    return redirect()->route('activity');
                }
            } else {
                if (is_numeric($request->id)) {
                    if (Auth::user()->role_id == 3) {
                        $activity = Activity::where('id', $request->id)->where('merchant_id', Auth::user()->id)->where('is_delete', 0)->first();
                    } else {
                        $activity = Activity::where('id', $request->id)->where('is_delete', 0)->first();
                    }

                    if (count($activity)) {
                        $packageList = ActivityPackageOptions::with(['packagequantity' => function($query) {
                                        $query->where('is_delete', 0);
                                    }])->where('activity_id', $request->id)->where('is_delete', 0)->get();
                        return view('activity::package_configuration', compact('activity', 'packageList'));
                    } else {
                        Session::flash('success', 'No such activity found ');
                        return redirect()->route('activity');
                    }
                } else {
                    return view('errors.404');
                }
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Delete Activity Package
     * 
     */

    public function removePackage(Request $request) {
        DB::table('activity_package_options')->where('id', $request->id)->update(['is_delete' => 1]);
        return response()->json([
                    'code' => 200,
                    'message' => "Pacakge has been deleted successfully"
        ]);
    }

    /*
     * Remove package quantity
     * 
     */

    public function removePackageQuantity(Request $request) {
        DB::table('activity_package_quantity')->where('id', $request->id)->update(['is_delete' => 1]);
        return response()->json([
                    'code' => 200,
                    'message' => "Package option removed successfully"
        ]);
    }

    public function changeStatus(Request $request) {
        DB::table('activity')->where('id', $request->id)->update(['status' => $request->status]);
        return response()->json([
                    'status' => "Activity has been " . $request->status . " successfully"
        ]);
    }

    /* Delete Activity */

    public function deleteActivity(Request $request) {
        if (Auth::user()->can('activities', 'write') && Auth::user()->role_id == 1) {
            DB::table('activity')->where('id', $request->id)->update([
                'is_delete' => 1
            ]);
            Session::flash('success', 'Activity has been deleted successfully');
            return redirect()->route('activity');
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Check policy exists
     * 
     */

    public function checkPolicyExists(Request $request) {
        $policy = DB::table('general_policies')->where('name', $request->name)->where('is_activity_policy', 0)->where('is_delete', 0)->first();
        if ($request->action == 'edit') { // Check For Edit User
            if ($policy == null) {
                $isValid = true;
            } else {
                if (count($policy) == 1) {
                    if ($policy->id == $request->policy_id) {
                        $isValid = true;
                    } else {
                        $isValid = false;
                    }
                } else {
                    $isValid = false;
                }
            }
        } else { // Check For New User
            if ($policy === null) {
                $isValid = true;
            } else {
                $isValid = false;
            }
        }
        echo json_encode(array(
            'valid' => $isValid,
        ));
    }

    /*
     * Get list of Categories based on City
     * 
     */

    public function getCategories(Request $request) {


        $categoryids = Citycategory::where('city_id', $request->id)->pluck('category_id')->toArray();
        $category = Category::whereIn('id', $categoryids)->pluck('name', 'id')->toArray();



        return response()->json([
                    'categories' => $category
        ]);
    }

    /*
     * Get list of subcategories based on category
     * 
     */

    public function getSubcategories(Request $request) {
        $subCategories = Subcategory::where('category_id', $request->id)->where('status', 'Active')->where('is_delete', 0)->pluck('name', 'id')->toArray();
        return response()->json([
                    'subcategories' => $subCategories
        ]);
    }

    /*
     * Admin Approve or decline merchant activity
     * 
     */

    public function approveDeclineActivity(Request $request) {
        if (Auth::user()->can('activities', 'write') && Auth::user()->role_id == 1) {
            $activity = Activity::with('merchant')->where('is_delete', '0')->where('id', $request->id)->first();
            $reasonDecline = "";
            if ($request->reasondecline != "") {
                $reasonDecline = $request->reasondecline;
            }
            if ($activity != null) {
                $dataArray = ["name" => $activity->merchant->name, "email" => $activity->merchant->email, "activityname" => $activity->title, 'resondecline' => $reasonDecline];
                if ($request->approvedecline == "approve") {
                    $activity->admin_approve = '1';
                    $template = EmailTemplate::where('name', 'approve-activity')->first();
                } else {
                    $activity->admin_approve = '2';
                    $template = EmailTemplate::where('name', 'decline-activity')->first();
                }
                Mail::send([], [], function($message) use ($template, $dataArray) {
                    $data = [
                        'toMerchantName' => $dataArray['name'],
                        'activityName' => $dataArray['activityname'],
                        'resonDecline' => $dataArray['resondecline'],
                    ];
                    $message->to($dataArray['email'], $dataArray['name'])
                            ->subject($template->subject)
                            ->setBody($template->parse($data), 'text/html');
                });
                $activity->save();

                Session::flash('success', 'activity has been ' . $request->approvedecline . '.');
                return redirect()->route('activity');
            } else {
                Session::flash('error', 'Something went wrong please try again later.');
                return redirect()->route('activity');
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

}
