<?php

namespace App\Modules\Locations\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Continent;
use App\Country;
use App\City;
use App\Citycategory;
use App\Category;
use App\Activity;
use Auth;
use Session;
use DateTimeZone;
use Intervention\Image\ImageManagerStatic as Image;

class LocationsController extends Controller {
    /*
     *  Country Lists
     * 
     */

    public function locations() {
        if (Auth::user()->can('locations', 'read') && Auth::user()->role_id == 1) {
            $locations = Continent::with(['contries' => function ($query) {
                                    $query->where('is_delete', 0);
                                }, 'contries.city' => function($query) {
                                    $query->where('is_delete', 0);
                                }])
                            ->where([
                                'status' => 'Active',
                                'is_delete' => 0
                            ])->orderBy('updated_at', 'desc')->get();
            $cityarray = Activity::with(['city' => function ($query) {
                            $query->where('is_delete', 0);
                        }])->where('is_delete', 0)->groupBy('city_id')->pluck('city_id')->toArray();

            $countryarray = City::whereIn('id', $cityarray)->where('is_delete', 0)->groupBy('country_id')->pluck('country_id')->toArray();
            $continentarray = Country::whereIn('id', $countryarray)->where('is_delete', 0)->groupBy('continent_id')->pluck('continent_id')->toArray();
            $locationarray = [];
            $locationarray = json_encode($locationarray);
            return view('locations::locations', compact('locations', 'locationarray', 'cityarray', 'countryarray', 'continentarray'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Search Location
     * 
     */

    public function searchLocation(Request $request) {
        if (Auth::user()->can('locations', 'read') && Auth::user()->role_id == 1) {
            $searchterm = $request->get('searchterm');
            $locations = Continent::with('contries', 'contries.city')
                            ->where(function ($query) {
                                $query->where('status', 'Active');
                                $query->where('is_delete', 0);
                            })
                            ->where(function ($query) use($searchterm) {
                                $query->orWhere('name', 'LIKE', "%$searchterm%");
                                $query->orWhereHas('contries', function ($q) use($searchterm) {
                                    $q->where('country', 'LIKE', "%$searchterm%");
                                    $q->where('is_delete', 0);
                                });
                                $query->orWhereHas('contries.city', function ($q1) use($searchterm) {
                                    $q1->where('city', 'LIKE', "%$searchterm%");
                                    $q1->where('is_delete', 0);
                                });
                            })
                            ->orderBy('updated_at', 'desc')->get();
            $locationarray = [];
            foreach ($locations as $locationskey => $locationsvalue) {
                $locationarray[$locationskey]['continetkey'] = $locationskey;
                $locationarray[$locationskey]['continetname'] = $locationsvalue->name;
                foreach ($locationsvalue->contries as $countrykey => $countryvalue) {
                    $locationarray[$locationskey]['countryname'][$countrykey]['countrykey'] = $countrykey;
                    $locationarray[$locationskey]['countryname'][$countrykey]['countryname'] = $countryvalue->country;
                    foreach ($countryvalue->city as $citykey => $cityvalue) {
                        $locationarray[$locationskey]['countryname'][$countrykey]['city'][$citykey]['citykey'] = $citykey;
                        $locationarray[$locationskey]['countryname'][$countrykey]['city'][$citykey]['cityname'] = $cityvalue->city;
                    }
                }
            }
            // echo '<pre>';
            $cityarray = Activity::with(['city' => function ($query) {
                            $query->where('is_delete', 0);
                        }])->where('is_delete', 0)->groupBy('city_id')->pluck('city_id')->toArray();

            $countryarray = City::whereIn('id', $cityarray)->where('is_delete', 0)->groupBy('country_id')->pluck('country_id')->toArray();
            $continentarray = Country::whereIn('id', $countryarray)->where('is_delete', 0)->groupBy('continent_id')->pluck('continent_id')->toArray();
            $locationarray = json_encode($locationarray);
            return view('locations::locations', compact('locations', 'searchterm', 'locationarray', 'continentarray', 'countryarray', 'cityarray'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     *  Add Continent
     * 
     */

    public function addContinent(Request $request) {
        if (Auth::user()->can('locations', 'write') && Auth::user()->role_id == 1) {
            if (count($request->continent)) {
                foreach ($request->continent as $key => $value) {
                    if ($value != "") {
                        $isExists = Continent::where('name', $value)->where('is_delete', 0)->exists();
                        if (!$isExists) {
                            $continent = new Continent();
                            $continent->name = $value;
                            $continent->created_at = date('Y-m-d H:i:s', time());
                            $continent->updated_at = date('Y-m-d H:i:s', time());
                            $continent->save();
                        }
                    }
                }
                Session::flash('success', 'Continent has been saved successfully');
                return redirect()->route('locations');
            } else {
                Session::flash('error', 'Please enter atleast one continent');
                return redirect()->route('locations');
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Update Contitnent
     * 
     */

    public function updateContinent(Request $request) {
        if (Auth::user()->can('locations', 'write') && Auth::user()->role_id == 1) {
            DB::table('continents')->where('id', $request->update_continent_id)->update([
                'name' => $request->continent,
                'updated_at' => date('Y-m-d H:i:s', time())
            ]);
            Session::flash('success', 'Continent has been updated successfully');
            return redirect()->route('locations');
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Delete Continent
     * 
     */

    public function deleteContinent(Request $request) {
        if (Auth::user()->can('locations', 'write') && Auth::user()->role_id == 1) {
            DB::table('continents')->where('id', $request->id)->update([
                'is_delete' => 1,
                'updated_at' => date('Y-m-d H:i:s', time())
            ]);
            DB::table('country')->where('continent_id', $request->id)->update([
                'is_delete' => 1,
                'updated_at' => date('Y-m-d H:i:s', time())
            ]);
            Session::flash('success', 'Continent has been deleted successfully');
            return redirect()->route('locations');
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Already Exists
     * 
     */

    public function continentExists(Request $request) {
        $continent = DB::table('continents')->where('name', $request->continent)->where('is_delete', 0)->first();
        if ($request->action == 'edit') { // Check For Edit User
            if ($continent == null) {
                $isValid = true;
            } else {
                if (count($continent) == 1) {
                    if ($continent->id == $request->continent_id) {
                        $isValid = true;
                    } else {
                        $isValid = false;
                    }
                } else {
                    $isValid = false;
                }
            }
        } else { // Check For New User
            if ($continent === null) {
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
     *  Check country exists or not
     * 
     */

    public function countryExists(Request $request) {
        $country = DB::table('country')->where('country', $request->country)->where('is_delete', 0)->first();
        if ($request->action == 'edit') { // Check For Edit User
            if ($country == null) {
                $isValid = true;
            } else {
                if (count($country) == 1) {
                    if ($country->id == $request->country_id) {
                        $isValid = true;
                    } else {
                        $isValid = false;
                    }
                } else {
                    $isValid = false;
                }
            }
        } else { // Check For New User
            if ($country === null) {
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
     *  Add Country
     * 
     */

    public function addCountry(Request $request) {
        if (Auth::user()->can('locations', 'write') && Auth::user()->role_id == 1) {
            if (count($request->country)) {
                foreach ($request->country as $key => $value) {
                    if ($value != "") {
                        $isExists = Country::where('country', $value)->where('is_delete', 0)->exists();
                        if (!$isExists) {
                            $country = new Country();
                            $country->continent_id = $request->country_continent_id;
                            $country->country = $value;
                            $country->created_at = date('Y-m-d H:i:s', time());
                            $country->updated_at = date('Y-m-d H:i:s', time());
                            $country->save();
                        }
                    }
                }
                Session::flash('success', 'Country has been saved successfully');
                return redirect()->route('locations');
            } else {
                Session::flash('error', 'Please enter atleast one country');
                return redirect()->route('locations');
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     *  Update Country
     * 
     */

    public function updateCountry(Request $request) {
        if (Auth::user()->can('locations', 'write') && Auth::user()->role_id == 1) {
            DB::table('country')->where('id', $request->country_id)->update([
                'country' => $request->country,
//            'status' => $request->status,
                'updated_at' => date('Y-m-d H:i:s', time())
            ]);
            Session::flash('success', 'Country has been updated successfully');
            return redirect()->route('locations');
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Delete Country
     * 
     */

    public function deleteCountry(Request $request) {
        if (Auth::user()->can('locations', 'write') && Auth::user()->role_id == 1) {
            DB::table('country')->where('id', $request->id)->update([
                'is_delete' => 1,
                'updated_at' => date('Y-m-d H:i:s', time())
            ]);
            Session::flash('success', 'Country has been deleted successfully');
            return redirect()->route('locations');
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Add City
     * 
     */

    public function addCity(Request $request) {
        if (Auth::user()->can('locations', 'write') && Auth::user()->role_id == 1) {
            if ($request->isMethod('POST')) {
                $city = new City();
                $city->country_id = $request->country;
                $city->city = $request->city;
                $city->description = $request->description;
                $city->popular_destination = $request->popular_destination;
                $timeZone = explode('--', $request->timezone);
                if (count($timeZone)) {
                    $city->timezone = $timeZone[0];
                    $city->zone_name = $timeZone[1];
                }

                $file = $request->file()['image'];
                $filename = "";
                $filename = time() . '-' . $file->getClientOriginalName();
                $path = public_path('img/cityimages/fullsize/' . $filename);
                $resizedPath = public_path('img/cityimages/resized/' . $filename);
                Image::make($file->getRealPath())->save($path);
                // Resize image with resolution 2040 X 1360
                Image::make($file->getRealPath())->resize(2040, 1360)->save($resizedPath);
                // Saving Product Images
                $city->image = $filename;
                $city->created_at = date('Y-m-d H:i:s', time());
                $city->updated_at = date('Y-m-d H:i:s', time());
                $city->save();

                if (count($request->category)) {
                    foreach ($request->category as $key => $value) {
                        $cityCategory = new Citycategory();
                        $cityCategory->city_id = $city->id;
                        $cityCategory->category_id = $value;
                        $cityCategory->created_at = date('Y-m-d H:i:s', time());
                        $cityCategory->updated_at = date('Y-m-d H:i:s', time());
                        $cityCategory->save();
                    }
                }
                Session::flash('success', 'City has been added successfully');
                return redirect()->route('locations');
            } else {
                $timezone = $this->tz_list();
                $country = Country::where('status', 'Active')->where('is_delete', 0)->pluck('country', 'id')->toArray();
                $categories = Category::where(['status' => 'Active', 'is_delete' => 0])->pluck('name', 'id')->toArray();
                return view('locations::addcity', compact('timezone', 'country', 'categories'));
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Edit City
     * 
     */

    public function editCity(Request $request) {
        if (Auth::user()->can('locations', 'write') && Auth::user()->role_id == 1) {
            $city = City::with('categories')->where('id', $request->id)->first();
            if ($request->isMethod('POST')) {
                $city->country_id = $request->country;
                $city->city = $request->city;
                $city->description = $request->description;
                $city->popular_destination = $request->popular_destination;
                $timeZone = explode('--', $request->timezone);
                if (count($timeZone)) {
                    $city->timezone = $timeZone[0];
                    $city->zone_name = $timeZone[1];
                }
                if ($_FILES['image']['error'] == 0) {
                    $file = $request->file()['image'];
                    $filename = "";
                    $filename = time() . '-' . $file->getClientOriginalName();
                    $path = public_path('img/cityimages/fullsize/' . $filename);
                    $resizedPath = public_path('img/cityimages/resized/' . $filename);
                    Image::make($file->getRealPath())->save($path);
                    // Resize image with resolution 2040 X 1360
                    Image::make($file->getRealPath())->resize(2040, 1360)->save($resizedPath);
                    // Saving Product Images
                    $city->image = $filename;
                }
                $city->created_at = date('Y-m-d H:i:s', time());
                $city->updated_at = date('Y-m-d H:i:s', time());
                $city->save();
                if (count($request->category)) {
                    Citycategory::where([
                        'city_id' => $city->id
                    ])->delete();
                    foreach ($request->category as $key => $value) {
                        $cityCategory = new Citycategory();
                        $cityCategory->city_id = $city->id;
                        $cityCategory->category_id = $value;
                        $cityCategory->created_at = date('Y-m-d H:i:s', time());
                        $cityCategory->updated_at = date('Y-m-d H:i:s', time());
                        $cityCategory->save();
                    }
                }
                Session::flash('success', 'City has been updated successfully');
                return redirect()->route('locations');
            } else {
                if (is_numeric($request->id)) {
                    if ($city != null) {
                        $timezone = $this->tz_list();
                        $country = Country::where('status', 'Active')->where('is_delete', 0)->pluck('country', 'id')->toArray();
                        $categories = Category::where(['status' => 'Active', 'is_delete' => 0])->pluck('name', 'id')->toArray();
                        return view('locations::editcity', compact('timezone', 'country', 'city', 'categories'));
                    } else {
                        return view('errors.404');
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
     * Delete City
     * 
     */

    public function deleteCity(Request $request) {
        if (Auth::user()->can('locations', 'write') && Auth::user()->role_id == 1) {
            DB::table('city')->where('id', $request->id)->update([
                'is_delete' => 1,
                'updated_at' => date('Y-m-d H:i:s', time())
            ]);
            Session::flash('success', 'City has been deleted successfully');
            return redirect()->route('locations');
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    public function tz_list() {
        $zones_array = array();
        $timestamp = time();
        $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        foreach ($tzlist as $key => $zone) {
            date_default_timezone_set($zone);
            $zones_array['(GMT ' . date('P', $timestamp) . ')--' . $zone] = '(GMT ' . date('P', $timestamp) . ') ' . $zone;
        }
        return $zones_array;
    }

}
