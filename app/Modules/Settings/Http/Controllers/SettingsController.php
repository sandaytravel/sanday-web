<?php

namespace App\Modules\Settings\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Session;
use App\Faqs;
use App\Fees;
use App\Settings;
use App\EmailTemplate;
use App\Category;
use App\Generalpolicy;
use App\AboutUs;
use App\Explore;
use App\Exploreimages;
use App\ActivityPolicy;
use Illuminate\Support\Facades\Mail;
use Auth;
use Intervention\Image\ImageManagerStatic as Image;

class SettingsController extends Controller {
    /*
     * Email Templates
     * 
     */

    public function emailTemplates(Request $request) {
        if (Auth::user()->can('settings', 'read') || Auth::user()->role_id == 1) {
            $templates = EmailTemplate::paginate(10);
            return View('settings::emailtemplates', compact('templates'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Edit email template
     * 
     */

    public function editEmailTemplate(Request $request) {
        if (Auth::user()->can('settings', 'write') || Auth::user()->role_id == 1) {
            $template = EmailTemplate::where('id', $request->id)->first();
            return View('settings::editemailtemplate', compact('template'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Update email template
     * 
     */

    public function updateEmailTemplate(Request $request) {
        if (Auth::user()->can('settings', 'write') || Auth::user()->role_id == 1) {
            DB::table('email_templates')
                    ->where('id', $request->id)
                    ->update(['subject' => $request->subject, 'content' => $request->content, 'updated_at' => date('Y-m-d H:i:s', time())]);
            Session::flash('success', 'Email template updated successfully.');
            return redirect()->route('emailtemplate');
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Genetal Policies
     * 
     */

    public function generalPolicy() {
        if (Auth::user()->can('settings', 'read') || Auth::user()->role_id == 1) {
            $policy = Generalpolicy::where('is_activity_policy', 0)->where('is_delete', 0)->get();

            $policy_array = ActivityPolicy::with('activity')->whereHas('activity', function ($query) {
                        $query->where('is_delete', 0);
                    })->groupBy('policy_id')->pluck('policy_id')->toArray();
            return view('settings::general_policy', compact('policy', 'policy_array'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Add Policy
     * 
     */

    public function addPolicy(Request $request) {
        if (Auth::user()->can('settings', 'write') || Auth::user()->role_id == 1) {
            $policy = new Generalpolicy();
            $policy->name = $request->name;

            $file = $request->file()['image'];
            $filename = "";
            $filename = time() . '-' . $file->getClientOriginalName();
            $path = public_path('img/icons/fullsized/' . $filename);
            $resizedPath = public_path('img/icons/resized/' . $filename);
            Image::make($file->getRealPath())->save($path);
            // Resize image with resolution 2040 X 1360
            Image::make($file->getRealPath())->resize(256, 256)->save($resizedPath);
            // Saving Product Images
            $policy->icon = $filename;

            $policy->created_at = date('Y-m-d H:i:s', time());
            $policy->updated_at = date('Y-m-d H:i:s', time());
            $policy->save();

            Session::flash('success', 'Policy has been saved successfully');
            return redirect()->route('general_policy');
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Update Policy
     * 
     */

    public function updatePolicy(Request $request) {
        if (Auth::user()->can('settings', 'write') || Auth::user()->role_id == 1) {
            $policy = Generalpolicy::where('id', $request->policy_id)->first();
            if ($policy != null) {
                $policy->name = $request->name;
                if ($request->file() != null) {
                    $file = $request->file()['image'];
                    $filename = "";
                    $filename = time() . '-' . $file->getClientOriginalName();
                    $path = public_path('img/icons/fullsized/' . $filename);
                    $resizedPath = public_path('img/icons/resized/' . $filename);
                    Image::make($file->getRealPath())->save($path);
                    // Resize image with resolution 2040 X 1360
                    Image::make($file->getRealPath())->resize(256, 256)->save($resizedPath);
                    // Saving Product Images
                    $policy->icon = $filename;
                }
                $policy->save();
                Session::flash('success', 'Policy has been updated successfully');
                return redirect()->route('general_policy');
            } else {
                Session::flash('error', 'No policy found to update');
                return redirect()->route('general_policy');
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Delete Policy
     * 
     */

    public function deletePolicy(Request $request) {
        if (Auth::user()->can('settings', 'write') || Auth::user()->role_id == 1) {
            DB::table('general_policies')->where('id', $request->id)->update([
                'is_delete' => 1,
                'updated_at' => date('Y-m-d H:i:s', time())
            ]);
            Session::flash('success', 'Policy has been deleted successfully');
            return redirect()->route('general_policy');
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * About us
     * 
     */

    public function aboutUs(Request $request) {
        if (Auth::user()->can('settings', 'read') || Auth::user()->role_id == 1) {
            $aboutus = AboutUs::first();
            return view('settings::about_us', compact('aboutus'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    public function updateAboutus(Request $request) {
        if (Auth::user()->can('settings', 'write') || Auth::user()->role_id == 1) {
            $AboutUs = AboutUs::first();
            if ($AboutUs != null) {
                $AboutUs->content = $request->description;
            } else {
                $AboutUs = new AboutUs();
                $AboutUs->content = $request->description;
            }
            $AboutUs->save();
            Session::flash('success', 'About us content has been updated successfully');
            return redirect()->route('aboutUs');
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Explore 
     * 
     */

    public function explore(Request $request) {
        if (Auth::user()->can('settings', 'read') || Auth::user()->role_id == 1) {
            $explore = Explore::with('images')->first();
            return view('settings::explore', compact('explore'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    public function updateExplore(Request $request) {
        if (Auth::user()->can('settings', 'write') || Auth::user()->role_id == 1) {
            DB::table('explore')->where('id', $request->id)->update([
                'title' => $request->title,
                'description' => $request->description
            ]);
            Session::flash('success', 'Explore content has been updated successfully');
            return redirect()->route('explore');
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /* Upload Explore Images */

    public function uploadExploreImage(Request $request) {
        $explore = Explore::first();
        $ids = [];
        if ($request->file()['files'] != null) {
            foreach ($request->file()['files'] as $key => $value) {
                $filename = "";
                $filename = time() . '-' . $value->getClientOriginalName();
                $path = public_path('img/explore/fullsized/' . $filename);
                $resizedPath = public_path('img/explore/resized/' . $filename);
                Image::make($value->getRealPath())->save($path);
                // Resize image with resolution 2040 X 1360
                Image::make($value->getRealPath())->resize(2040, 1360)->save($resizedPath);
                // Saving Product Images
                $image = new Exploreimages();
                $image->explore_id = $explore->id;
                $image->image = $filename;
                $image->created_at = date('Y-m-d h:i:s', time());
                $image->updated_at = date('Y-m-d h:i:s', time());
                $image->save();
                $ids[] = $image->id;
            }
        }
        return $ids;
    }

    /* Remove Explore Image */

    public function removeExploreImage(Request $request) {
        $exploreImage = Exploreimages::where('id', $request->id)->first();
        if ($exploreImage != null) {
            unlink(public_path('img/explore/fullsized/' . $exploreImage->image));
            unlink(public_path('img/explore/resized/' . $exploreImage->image));
            Exploreimages::where('id', $request->id)->delete();
        }
        return;
    }

}
