<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use App\Models\ProductCategory;
use App\Models\Products;
use App\Models\ProductBrand;
use App\Models\ProductUnit;
use Carbon\Carbon;
use DB;
use Auth;

class ProductController extends Controller
{
    /**
     * Product All
     * Product All
     * Product All
     * 
     */
    public function productIndex()
    {
        $loggedUser = Auth::user();
        $productview = Products::leftJoin('product_categories', 'products.product_category', '=', 'product_categories.id')
                                ->leftJoin('product_brands', 'products.product_brand', '=', 'product_brands.id')
                                ->leftJoin('product_units', 'products.product_unit', '=', 'product_units.id')
                                ->select('products.*', 
                                        'product_categories.category_name', 
                                        'product_brands.brand_name', 
                                        'product_units.unit_name')
                                ->get();
        $category = ProductCategory::all();
        $brand = ProductBrand::all();
        $unit = ProductUnit::all();
    
        return view('products.product', compact('productview', 'loggedUser', 'category', 'brand', 'unit'));
    }

    /** Save Record */
    public function saveProduct(Request $request)
    {
        $request->validate([
            'product_code'     => 'required|string|max:255',
            // 'product_serial'   => 'required|string|max:255',	
            'product_name'	   => 'required|string|max:255',
            'product_category' => 'required|integer',
            // 'product_brand'    => 'required|integer',
            'product_unit'     => 'required|integer',
        ]);
        
        DB::beginTransaction();
        try {
            Products::create([
                'product_code'      => $request->product_code,
                'product_serial'	=> $request->product_serial,
                'product_name'      => $request->product_name,
                'product_category'  => $request->product_category,
                'product_brand'     => $request->product_brand,
                'product_unit'      => $request->product_unit,
                'product_parts_details' => $request->product_details,
                'product_status'    => 'Active',
                'product_creator'   => $request->user()->name,
            ]);
            
            DB::commit();
            flash()->success('Created new product successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Update request failed: ' . $e->getMessage());
            flash()->error('Failed to add product :)');
            return redirect()->back();
        }
    }
    
    // Auto generate of product code
    public function getProductCode(Request $request) 
    {
        $categoryId = $request->input('category_id');
    
        // Get the category and its code
        $category = ProductCategory::find($categoryId);
        if (!$category) {
            return response()->json(['product_code' => '']);
        }
    
        $categoryCode = $category->category_code;
    
        // Get the last product with a code starting with the category code
        $lastProduct = Products::where('product_category', $categoryId)
            ->where('product_code', 'LIKE', $categoryCode . '-%')
            ->orderByDesc('product_code')  // Ensure correct code-based ordering
            ->first();
    
        $nextNumber = 1;
    
        if ($lastProduct) {
            $matches = [];
            if (preg_match('/-(\d+)$/', $lastProduct->product_code, $matches)) {
                $nextNumber = intval($matches[1]) + 1;
            }
        }
        $newCode = $categoryCode . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    
        // Double-check uniqueness (optional)
        while (Products::where('product_code', $newCode)->exists()) {
            $nextNumber++;
            $newCode = $categoryCode . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }
    
        return response()->json(['product_code' => $newCode]);
    }
    

    /** Update Record */
    public function updateProduct(Request $request)
    {
        $request->validate([
            'id'                => 'required|integer|exists:products,id',
            'product_status'   => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            Products::where('id', $request->id)->update([
                'product_status'   => $request->product_status,
            ]);
            
            DB::commit();
            flash()->success('Product updated successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Failed to update product :(');
            return redirect()->back();
        }
    }
        /** Delete Record */
        public function deleteProduct(Request $request)
        {
            $request->validate([
                'id' => 'required|integer',
            ]);
            
            try {
                $product = Products::find($request->id);
                
                if ($product) {
                    $product->delete();
                    flash()->success('Product deleted successfully!');
                } else {
                    flash()->error('Product not found!');
                }
                return redirect()->back();
            } catch (\Exception $e) {
                flash()->error('Failed to delete product.');
                return redirect()->back();
            }
        }

    /** 
     * Display All Category
     * Display All Category
     * Display All Category
     * 
     * */
    public function categoryIndex()
    {
        $loggedUser = Auth::user();
        $categoryview = ProductCategory::orderByRaw("FIELD(category_status, 'Active') DESC")->get();

        return view('products.category', compact('categoryview', 'loggedUser'));
    }

    /** Save Category Record */
    public function saveCategory(Request $request)
    {
        $request->validate([
            'category_name'     => 'required|string|max:255',
            'category_code'     => 'required|string|max:255',
        ]);
        
        DB::beginTransaction();
        try {
            ProductCategory::create([
                'category_name'                  => $request->category_name,
                'category_code'                  => $request->category_code,
                'category_status'                => 'Active',
                'category_creator'               => $request->user()->name,
            ]);
            
            DB::commit();
            flash()->success('Created new category successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Failed to Add category :)');
            return redirect()->back();
        }
    }

    /** Update Record */
    public function updateCategory(Request $request)
    {
        $request->validate([
            'id'                => 'required|integer|exists:product_categories,id',
            'category_name'     => 'required|string|max:255',
            'category_status'   => 'required|string|max:255',
            'category_code'     => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            ProductCategory::where('id', $request->id)->update([
                'category_name'     => $request->category_name,
                'category_code'     => $request->category_code,
                'category_status'   => $request->category_status,
            ]);
            
            DB::commit();
            flash()->success('Category updated successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Failed to update category :)');
            return redirect()->back();
        }
    }

    /** Delete Record */
    public function deleteCategory(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);
        
        try {
            $category = ProductCategory::find($request->id);
            
            if ($category) {
                $category->delete();
                flash()->success('Category deleted successfully!');
            } else {
                flash()->error('Category not found!');
            }
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Error deleting category: ' . $e->getMessage());
            flash()->error('Failed to delete category.');
            return redirect()->back();
        }
    }


    /**
     * 
     * Funtions for Brands 
     * Funtions for Brands
     * Funtions for Brands 
     * 
     */

     /** Display All  */
    public function brandIndex()
    {
        $loggedUser = Auth::user();
        $brandview = ProductBrand::orderByRaw("FIELD(brand_status, 'Active') DESC")->get();

        return view('products.brand', compact('brandview', 'loggedUser'));
    }

    /** Save Record */
    public function saveBrand(Request $request)
    {
        $request->validate([
            'brand_name'             => 'required|string|max:255',
        ]);
        
        DB::beginTransaction();
        try {
            ProductBrand::create([
                'brand_name'                  => $request->brand_name,
                'brand_status'                => 'Active',
                'brand_creator'               => $request->user()->name,
            ]);
            
            DB::commit();
            flash()->success('Created new brand successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Failed to Add brand :)');
            return redirect()->back();
        }
    }

    /** Update Record */
    public function updateBrand(Request $request)
    {
        $request->validate([
            'id'                => 'required|integer|exists:product_brands,id',
            'brand_name'     => 'required|string|max:255',
            'brand_status'   => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            ProductBrand::where('id', $request->id)->update([
                'brand_name' => $request->brand_name,
                'brand_status' => $request->brand_status,
            ]);
            
            DB::commit();
            flash()->success('Brand updated successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Failed to update Brand :)');
            return redirect()->back();
        }
    }

    /** Delete Record */
    public function deleteBrand(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);
        
        try {
            $brand = ProductBrand::find($request->id);
            
            if ($brand) {
                $brand->delete();
                flash()->success('Brand deleted successfully!');
            } else {
                flash()->error('Brand not found!');
            }
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Error deleting brand: ' . $e->getMessage());
            flash()->error('Failed to delete brand.');
            return redirect()->back();
        }
    }

    /**
     * 
     * Units Function
     * Units Function
     * Units Function
     * 
     */

          /** Display All  */
    public function unitIndex()
    {
        $loggedUser = Auth::user();
        $unitview = ProductUnit::orderByRaw("FIELD(unit_status, 'Active') DESC")->get();

        return view('products.unit', compact('unitview', 'loggedUser'));
    }

    /** Save Record */
    public function saveUnit(Request $request)
    {
        $request->validate([
            'unit_name'             => 'required|string|max:255',
        ]);
        
        DB::beginTransaction();
        try {
            ProductUnit::create([
                'unit_name'                  => $request->unit_name,
                'unit_status'                => 'Active',
                'unit_creator'               => $request->user()->name,
            ]);
            
            DB::commit();
            flash()->success('Created new unit successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Failed to Add unit :)');
            return redirect()->back();
        }
    }

    /** Update Record */
    public function updateUnit(Request $request)
    {
        $request->validate([
            'id'                => 'required|integer|exists:product_brands,id',
            'unit_name'         => 'required|string|max:255',
            'unit_status'       => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            ProductUnit::where('id', $request->id)->update([
                'unit_name' => $request->unit_name,
                'unit_status' => $request->unit_status,
            ]);
            
            DB::commit();
            flash()->success('Unit updated successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Failed to update unit :)');
            return redirect()->back();
        }
    }

    /** Delete Record */
    public function deleteUnit(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);
        
        try {
            $brand = Productunit::find($request->id);
            
            if ($brand) {
                $brand->delete();
                flash()->success('Unit deleted successfully!');
            } else {
                flash()->error('Unit not found!');
            }
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Error deleting unit: ' . $e->getMessage());
            flash()->error('Failed to delete unit.');
            return redirect()->back();
        }
    }
}
