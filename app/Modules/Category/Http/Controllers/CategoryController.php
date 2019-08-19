<?php

namespace App\Modules\Category\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Category;
use App\Subcategory;
use Session;
use Auth;
use Intervention\Image\ImageManagerStatic as Image;

class CategoryController extends Controller {
    /*
     * List of all categories
     * 
     */

    public function index() {
        if (Auth::user()->can('categories', 'read') && Auth::user()->role_id == 1) {
            $categories = Category::with(['activities' => function ($query) {
                            $query->where('is_delete', 0);
                        }, 'subcategories.activities' => function ($query) {
                            $query->where('is_delete', 0);
                        }, 'subcategories' => function($query) {
                            $query->where('is_delete', 0);
                        }])
                    ->where(['status' => 'Active', 'is_delete' => 0])
                    ->get();
            $categoriesArray = Category::where(['status' => 'Active', 'is_delete' => 0])->pluck('name', 'id')->toArray();
            return view('category::categories', compact('categories', 'categoriesArray'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Search category and it's sub category
     * 
     */

    public function searchCategory(Request $request) {
        if (Auth::user()->can('categories', 'read') && Auth::user()->role_id == 1) {
            $searchterm = $request->get('searchterm');
            $categories = Category::with(['subcategories' => function ($query) {
                            $query->where('is_delete', 0);
                        }])
                    ->where(function ($query) {
                        $query->where('status', 'Active');
                        $query->where('is_delete', 0);
                    })
                    ->where('name', 'LIKE', "%$searchterm%")
                    ->orWhereHas('subcategories', function ($query) use($searchterm) {
                        $query->where('name', 'LIKE', "%$searchterm%");
                    })
                    ->get();
            $categoriesArray = Category::where(['status' => 'Active', 'is_delete' => 0])->pluck('name', 'id')->toArray();
            return view('category::categories', compact('categories', 'categoriesArray', 'searchterm'));
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Add Category
     * 
     */

    public function addCategory(Request $request) {
        if (Auth::user()->can('categories', 'write') && Auth::user()->role_id == 1) {
            $category = new Category();
            $category->name = $request->category;

            $file = $request->file()['image'];
            $filename = "";
            $filename = time() . '-' . $file->getClientOriginalName();
            $path = public_path('img/icons/fullsized/' . $filename);
            $resizedPath = public_path('img/icons/resized/' . $filename);
            Image::make($file->getRealPath())->save($path);
            // Resize image with resolution 2040 X 1360
            Image::make($file->getRealPath())->resize(256, 256)->save($resizedPath);
            // Saving Product Images
            $category->icon = $filename;

            $category->created_at = date('Y-m-d H:i:s', time());
            $category->updated_at = date('Y-m-d H:i:s', time());
            $category->save();
            if (count($request->subcategory)) {
                foreach ($request->subcategory as $key => $value) {
                    if ($value != "") {
                        $isExists = Subcategory::where('name', $value)->where('is_delete', 0)->exists();
                        if (!$isExists) {
                            $subCategory = new Subcategory();
                            $subCategory->category_id = $category->id;
                            $subCategory->name = $value;
                            $subCategory->created_at = date('Y-m-d H:i:s', time());
                            $subCategory->updated_at = date('Y-m-d H:i:s', time());
                            $subCategory->save();
                        }
                    }
                }
            }
            Session::flash('success', 'Category has been saved successfully');
            return redirect()->route('categories');
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Edit Category
     * 
     */

    public function updateCategory(Request $request) {
        if (Auth::user()->can('categories', 'write') && Auth::user()->role_id == 1) {
            $category = Category::where('id', $request->category_id)->first();
            if ($category != null) {
                $category->name = $request->category;

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
                    $category->icon = $filename;
                }

                $category->updated_at = date('Y-m-d H:i:s', time());
                $category->save();
                Session::flash('success', 'Category has been updated successfully');
                return redirect()->route('categories');
            } else {
                Session::flash('error', 'No category found to update');
                return redirect()->route('categories');
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Delete Category and it's subcategory
     * 
     */

    public function deleteCategory(Request $request) {
        if (Auth::user()->can('categories', 'write') && Auth::user()->role_id == 1) {
            DB::table('categories')->where('id', $request->id)->update([
                'is_delete' => 1,
                'updated_at' => date('Y-m-d H:i:s', time())
            ]);
            DB::table('subcategory')->where('category_id', $request->id)->update([
                'is_delete' => 1,
                'updated_at' => date('Y-m-d H:i:s', time())
            ]);
            Session::flash('success', 'Category has been deleted successfully');
            return redirect()->route('categories');
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Check category exists or not
     * 
     */

    public function isCategoryExists(Request $request) {
        $category = DB::table('categories')->where('name', $request->category)->where('is_delete', 0)->first();
        if ($request->action == 'edit') { // Check For Edit User
            if ($category == null) {
                $isValid = true;
            } else {
                if (count($category) == 1) {
                    if ($category->id == $request->category_id) {
                        $isValid = true;
                    } else {
                        $isValid = false;
                    }
                } else {
                    $isValid = false;
                }
            }
        } else { // Check For New User
            if ($category === null) {
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
     * Add Sub Category
     * 
     */

    public function addSubcategory(Request $request) {
        if (Auth::user()->can('categories', 'write') && Auth::user()->role_id == 1) {
            if (count($request->subcategory)) {
                foreach ($request->subcategory as $key => $value) {
                    if ($value != "") {
                        $isExists = Subcategory::where('name', $value)->where('is_delete', 0)->exists();
                        if (!$isExists) {
                            $subCategory = new Subcategory();
                            $subCategory->category_id = $request->category;
                            $subCategory->name = $value;
                            $subCategory->created_at = date('Y-m-d H:i:s', time());
                            $subCategory->updated_at = date('Y-m-d H:i:s', time());
                            $subCategory->save();
                        }
                    }
                }
                Session::flash('success', 'Subcategory has been saved successfully');
                return redirect()->route('categories');
            } else {
                Session::flash('error', 'Please enter atleast one subcategory');
                return redirect()->route('categories');
            }
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Update Sub Category
     * 
     */

    public function updateSubcategory(Request $request) {
        if (Auth::user()->can('categories', 'write') && Auth::user()->role_id == 1) {
            DB::table('subcategory')->where('id', $request->subcategory_id)->update([
                'name' => $request->subcategory,
                'updated_at' => date('Y-m-d H:i:s', time())
            ]);
            Session::flash('success', 'Subcategory has been updated successfully');
            return redirect()->route('categories');
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Delete Subcategory
     * 
     */

    public function deleteSubCategory(Request $request) {
        if (Auth::user()->can('categories', 'write') && Auth::user()->role_id == 1) {
            DB::table('subcategory')->where('id', $request->id)->update([
                'is_delete' => 1,
                'updated_at' => date('Y-m-d H:i:s', time())
            ]);
            Session::flash('success', 'Subcategory has been deleted successfully');
            return redirect()->route('categories');
        } else {
            Session::flash('error', 'You are not authorized to access this feature');
            return redirect()->route('merchantdashboard');
        }
    }

    /*
     * Check subcategory exists or not
     * 
     */

    public function isSubcategoryExists(Request $request) {
        if (is_array($request->subcategory)) {
            $subcategory = $request->subcategory[0];
        } else {
            $subcategory = $request->subcategory;
        }
        $subcategory = DB::table('subcategory')->where('name', $subcategory)->where('is_delete', 0)->first();
        if ($request->action == 'edit') { // Check For Edit User
            if ($subcategory == null) {
                $isValid = true;
            } else {
                if (count($subcategory) == 1) {
                    if ($subcategory->id == $request->subcategory_id) {
                        $isValid = true;
                    } else {
                        $isValid = false;
                    }
                } else {
                    $isValid = false;
                }
            }
        } else { // Check For New User
            if ($subcategory === null) {
                $isValid = true;
            } else {
                $isValid = false;
            }
        }
        echo json_encode(array(
            'valid' => $isValid,
        ));
    }

}
