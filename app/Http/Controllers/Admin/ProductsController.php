<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ImageOptimize;
use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Admin\ProductImage;
use App\Models\Admin\Vendor;
use App\Models\Posts\PostFile\PostFile;
use App\Models\Posts\Post\Post;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    /**
     * listing the Products
    */
    public function index(Request $request)
    {
        if (!have_right('View-Products')) {
            access_denied();
        }

        $data = [];
        $data['categories'] = Category::all();
        $data['vendors'] = Vendor::all();
        if ($request->ajax()) {
            $category = (isset($_GET['category']) && $_GET['category']) ? $_GET['category'] : '';
            $status = (isset($_GET['status']) && $_GET['status']) ? $_GET['status'] : '';
            $vendor = (isset($_GET['vendor']) && $_GET['vendor']) ? $_GET['vendor'] : '';
            $from_date = (isset($_GET['from_date']) && $_GET['from_date']) ? $_GET['from_date'] : '';
            $to_date = (isset($_GET['to_date']) && $_GET['to_date']) ? $_GET['to_date'] : '';
            $db_record = Product::all();
            $db_record = $db_record->when($category, function ($q, $category) {
                return $q->where('category_id', $category);
            });
            $db_record = $db_record->when($status, function ($q, $status) {
                $status = $status == 'active' ? 1 : 0;
                return $q->where('status', $status);
            });
            $db_record = $db_record->when($vendor, function ($q, $vendor) {
                return $q->where('vendor_id', $vendor);
            });
            $db_record = $db_record->when($from_date, function ($q, $from_date) {
                $startDate = date('Y-m-d', strtotime($from_date)) . ' 00:00:00';
                return $q->where('created_at', '>=', $startDate);
            });
            $db_record = $db_record->when($to_date, function ($q, $to_date) {
                $endDate = date('Y-m-d', strtotime($to_date)) . ' 23:59:00';
                return $q->where("created_at", '<=', $endDate);
                // return $q->whereRaw("(date(created_at) <='" . $endDate . "')");
            });

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<span class="badge badge-danger">Disable</span>';
                if ($row->status == 1) {
                    $status = '<span class="badge badge-success">Active</span>';
                }
                return $status;
            });
            $datatable = $datatable->addColumn('featured', function ($row) {
                if ($row->featured == 1) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                if (have_right('Featured-Products')) {

                    $featured = '<label class="switch"> <input type="checkbox" class="is_featured" id="chk_' . $row->id . '" name="is_featured" onclick="is_featured($(this),' . $row->id . ')" ' . $checked . ' > <span class="slider round"></span></label>';
                    return $featured;
                } else {
                    return '<span class=" badge badge-danger">No Permission</span>';
                }
            });

            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Edit-Products')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/products/" . $row->id) . '/edit' . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('Delete-Products')) {
                    $actions .= '<form method="POST" action="' . url("admin/products/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
                    $actions .= '<input type="hidden" name="_method" value="DELETE">';
                    $actions .= '<input name="_token" type="hidden" value="' . csrf_token() . '">';
                    $actions .= '<button class="btn btn-danger" style="margin-left:02px;" type="button" onclick="showConfirmAlert(this)" title="Delete">';
                    $actions .= '<i class="far fa-trash-alt"></i>';
                    $actions .= '</button>';
                    $actions .= '</form>';
                }

                if (have_right('Create-Post-Products')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="javascript:void(0)" title="Create Post" onclick="getProductDetails(' . $row->id . ')"><i class="far fa-paper-plane"></i></a>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['status', 'action', 'featured']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.products.listing', $data);
    }

    /**
     * creating the Products
    */
    public function create()
    {
        if (!have_right('Delete-Products')) {
            access_denied();
        }

        $data = [];
        $data['row'] = new Product();
        $data['categories'] = Category::where('status', 1)->get();
        $data['vendors'] = Vendor::where('status', 1)->get();
        $data['action'] = 'add';
        return View('admin.products.form', $data);
    }

    /**
     * storing the Products
    */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'name_english' => 'required|string|max:200',
            'description_english' => 'required|string',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        if (isset($input['image'])) {
            $imagePathsarray = $this->uploadImageMultiple($request);
        }
        unset($input['image']);
        if (isset($input['file_name'])) {
            $imagePath = $this->uploadImage($request, 'file');
            $input['file_name'] = $imagePath;
        }

        if ($input['action'] == 'add') {
            unset($input['action']);
            if (!have_right('Create-Products')) {
                access_denied();
            }

            $model = new Product();
            $model->fill($input);
            $model->save();
            if (isset($imagePathsarray)) {
                foreach ($imagePathsarray as $key => $val) {
                    $model_image = new ProductImage();
                    $model_image->file_name = $val;
                    //                    $model_image->file_type = $val['image_extention'];
                    //                    $model->productImages()->save($model_image);
                    $model->productImages()->save($model_image);
                }
            }
            return redirect('admin/products')->with('message', 'Data added Successfully');
        } else {

            if (!have_right('Edit-Products')) {
                access_denied();
            }

            if (isset($request->old_image_id)) {

                $del_rows = $request->old_image_id;
                $delete_image_row = DB::table('product_images')->whereNotIn('id', $del_rows)->get();
                foreach ($delete_image_row as $keyy => $vall) {
                    $image_name = $vall->file_name;
                    deleteS3File($image_name);
                    // $this->deleteEditoImage($image_name);
                }
                DB::table('product_images')->whereNotIn('id', $del_rows)->delete();
            } else {

                $delete_image_row = DB::table('product_images')->where('id', $request->id)->get();
                foreach ($delete_image_row as $keyy => $vall) {
                    $image_name = $vall->file_name;
                    deleteS3File($image_name);
                    // $this->deleteEditoImage($image_name);
                }
                // DB::enableQueryLog();
                DB::table('product_images')->where('product_id', $request->id)->delete();
            }

            unset($input['action']);
            if (isset($input['image'])) {
                $imagePathsarray = $this->uploadImageMultiple($request);
            } else {
                unset($input['image']);
            }

            unset($input['old_image_id']);
            $id = $input['id'];
            $model = Product::find($id);
            $model->fill($input);
            $model->update();
            if (!empty($imagePathsarray)) {
                foreach ($imagePathsarray as $key => $val) {
                    $model_image = new ProductImage();
                    $model_image->file_name = $val;
                    $fileNameParts = explode('.', $val);
                    $ext = end($fileNameParts);
                    $model_image->file_type = $ext;
                    $model->productImages()->save($model_image);
                }
            }
            return redirect('admin/products')->with('message', 'Data updated Successfully');
        }
    }

    /**
     * edit the Products
    */
    public function edit($id)
    {
        if (!have_right('Edit-Products')) {
            access_denied();
        }

        $data = [];
        $data['id'] = $id;
        $data['row'] = Product::find($id);
        $data['product_images'] = $data['row']->productImages;
        $data['categories'] = Category::where('status', 1)->get();
        $data['vendors'] = Vendor::where('status', 1)->get();
        $data['action'] = 'edit';
        return View('admin.products.form', $data);
    }

    /**
     * removing the Products
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Products')) {
            access_denied();
        }

        $data = [];
        $data['row'] = Product::destroy($id);
        return redirect('admin/products')->with('message', 'Data deleted Successfully');
    }

    /**
     * uploading image for the Products
    */
    public function uploadImage(Request $request, $file = null)
    {
        if (empty($file)) {
            $path = 'images/products-images';
        } else {
            $path = 'files/product-files';
        }
        if ($request->image && empty($file)) {
            $imageName = 'product' . time() . '.' . $request->image->extension();
            if ($request->image->move(public_path($path), $imageName)) {
                $path = $path . "/" . $imageName;
            }
        }
        if ($request->file_name && !empty($file)) {
            $imageName = 'product' . time() . '.' . $request->file_name->extension();
            if ($request->file_name->move(public_path($path), $imageName)) {
                $path = $path . "/" . $imageName;
            }
        }
        return $path;
    }

    /**
     * uploading multiple images for the Products
    */
    public function uploadImageMultiple(Request $request)
    {
        $path = 'images/products-images';
        $image_path_array = [];
        $counter = 0;
        foreach ($request->image as $key => $value) {
            $path = ImageOptimize::improve($value, 'images/products-images');
            /*$imageName = 'product' . time() . uniqid() . '.' . $value->extension();
            $imageExtention = $value->extension();
            $value->move(public_path($path), $imageName);*/
            /* $image_path_array[$counter]['image_url'] =  $path . "/" . $imageName;
            $image_path_array[$counter]['image_extention'] = $imageExtention;*/
            $image_path_array[] = $path;
            $counter++;
        }
        return $image_path_array;
        // dd($image_path_array);
    }

    /**
     * getting the Product details
    */
    public function getProductDetails(Request $request)
    {
        $product = Product::with('productImages')->find($request->postID)->toArray();
        $response = [];

        $response['name']['english'] = $product['name_english'];
        $response['name']['urdu'] = $product['name_urdu'];
        $response['name']['arabic'] = $product['name_arabic'];
        $response['description']['english'] = $product['description_english'];
        $response['description']['urdu'] = $product['description_urdu'];
        $response['description']['arabic'] = $product['description_arabic'];
        $response['price'] = $product['price'];
        $response['id'] = $product['id'];
        $images = [];
        foreach ($product['product_images'] as $image) {
            $images[] = $image['file_name'];
        }
        $response['images'] = $images;

        $data = [];
        $data['names'] = $response['name'];
        $data['descriptions'] = $response['description'];
        $data['images'] = $response['images'];
        $data['price'] = $response['price'];
        $data['id'] = $response['id'];

        $html = View('admin.products.post-details-partial', $data);
        echo $html;
        exit();
    }

    /**
     * deleting editor image of the Products
    */
    public function deleteEditoImage($image)
    {
        if (file_exists(public_path($image))) {
            unlink(public_path($image));
        }
    }

    /**
     * setting featured Products
    */
    public function setFeaturedProduct($id = null)
    {
        if (!have_right('Featured-Products')) {
            access_denied();
        }

        Product::where('featured', 1)->update(['featured' => 0]);
        $update_product = Product::where('id', $id)->update(['featured' => $_GET['status']]);

        if ($update_product) {

            echo true;
            exit();
        }
    }

    /**
     * creating posts of the Products
    */
    public function productPost(Request $request)
    {
        if (!have_right('Create-Post-Products')) {
            access_denied();
        }

        $data = $request->except('images');
        $data['post_type'] = 4;
        $data['status'] = 1;
        $data['product_id'] = $data['product_id'];
        $post = Post::create(Arr::add($data, 'admin_id', auth()->user()->id));
        if(isset($request->images)){
            foreach ($request->images as $image) {
                $image2 = Str::replace('/', '', $image);
                $path='post-images/' . $image2;
                // Copy the file within S3
                Storage::disk('s3')->copy($image, $path);
                // File::copy(public_path($image), public_path('post-images/' . $image2));
                PostFile::create(['file' => 'post-images/' . $image2, 'post_id' => $post->id]);
            }
        }
        return redirect()->back()->with('message', 'Post Created Successfully');
    }
}
