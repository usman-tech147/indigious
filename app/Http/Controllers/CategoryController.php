<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class CategoryController extends Controller
{

    public function index()
    {
        $packages=Package::all();
        return view('admin.new-category',compact('packages'));
    }


    public function store(Request $request,$package_id)
    {
        $request->merge(['package'=>$package_id]);
        $request->validate([
            'package'=>['required','exists:packages,id'],
            'name'=>['required','max:'.env('INPUT_SMALL')],
//            'image'=>['required', "mimes:jpeg,jpg,png"],
//            'detail'=>['required', 'max:'.env('TEXT_AREA_LIMIT')]
        ]);

        if($request->hasFile('image')){
            $image=$request->file('image');
            $imageName=time().$image->getClientOriginalName();
            $image->move(public_path('/uploads/category/'),$imageName);

        }
        Category::create([
            'package_id'=>$request->input('package'),
            'name'=>$request->input('name'),
            'detail'=>$request->input('detail'),
//            'image'=>$imageName
        ]);
        return redirect()->back()->with('success','Category Successfully Added.');
    }


    public function categoryList($package_id)
    {
        $package=Package::findOrFail($package_id);
        return view('admin.category-list',compact('package'));
    }
    public function categoryListDataTable(Request $request){
        $columns = array(
            0   =>'id',
            1   =>'name',
            2   =>'image',
            3   =>'package',
            4   =>'video',
            5   =>'detail',
            6   =>'action'
        );
        $package_id=$request->input('package_id');
        $categories=Category::where('package_id',$package_id)->get();
        $totalData=$categories->count();
        $totalFiltered=$totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
//        $order = $columns[$request->input('order.0.column')];
//        $dir = $request->input('order.0.dir');

        $name = $request->input('name');


        $a=0;
            $categoriesQuery =  Category::where('package_id',$package_id)->where(function ($query) use ($name){
                if($name){
                    $query->where('name', 'LIKE',"%".$name."%");
               $a=1;
                }
            });
            if($a){
                $totalFiltered=$categoriesQuery->count();
            }
            $categories= $categoriesQuery->offset($start)
            ->limit($limit)
            ->get();
        $data = array();
        if(!empty($categories))
        {
            $i=1;
            foreach ($categories as $category)
            {
                $videoCount=0;
                foreach ($category->subCategories as $sub_category){
                    $videoCount+=$sub_category->videos->count();
                }
//                $edit="/admin/edit-category/".$category->id;
                $edit="javascript:void(0)";
                $delete="/admin/delete-category/".$category->id;
                $subCategory=route('admin.sub-category-list',[$category->id]);
                $imagePath=asset('uploads/category/'.$category->image);
                $nestedData['no'] = $i++;
                $nestedData['id'] = $category->id;
                $nestedData['name'] = $category->name;
                $nestedData['image'] ='<a class="elem imgzoom" href="'.$imagePath.'" data-lcl-thumb="'.$imagePath.'"><i class="fa fa-picture-o"></i></a>';
                $nestedData['package'] =    $category->package->name;
                $nestedData['video'] =  $videoCount;
//                $nestedData['detail'] =$category->detail;
                $nestedData['action']='<a href='.$edit.' class="tag" data-target="#categoryModal" data-toggle="modal"  data-id="'.$category->id.'" data-name="'.$category->name.'" data-detail="'.$category->detail.'">Edit</a> <a href='.$delete.' data-id='.$category->id.' class="tag btn-delete-package">Delete</a><a href="'.$subCategory.'" class="tag">Manage Subcategories</a>';
                $data[] = $nestedData;
            }
        }

        $json=array(
            "draw"=>intval(\request('draw')),
            "recordsTotal"=> intval($totalData),
            "recordsFiltered"=> intval($totalFiltered),
            "data"=>$data
        );
        return Response::json($json);
    }
    public function deleteCategory($id){
        $category=Category::findOrFail($id);

        if($category->delete()){
            try {
                unlink(public_path('/uploads/category/' . $category->image));
            }
            catch (\Exception $ex){

            }
        }
        else{
            return Response::json(['error'=>'Can\'t Delete'],500);
        }
    }
    public function editCategory($id){
        $category=Category::findOrFail($id);
        $packages=Package::all();
        return view('admin.edit-category',compact('category','packages'));
    }
    public function updateCategory(Request $request,$id){
        $request->validate([
//            'package'=>['required','exists:packages,id'],
            'e_name'=>['required','max:'.env('INPUT_SMALL')],
//            'image'=>[ "mimes:jpeg,jpg,png"],
//            'e_detail'=>['required', 'max:'.env('TEXT_AREA_LIMIT')]
        ]);
        $category=Category::findOrFail($id);
        if($request->hasFile('image')){
            $image=$request->file('image');
            $imageName=time().$image->getClientOriginalName();
            unlink(public_path('/uploads/category/'.$category->image));
            $image->move(public_path('/uploads/category/'),$imageName);
            $category->image=$imageName;
        }
        $category->name=$request->input('e_name');
//        $category->package_id=$request->input('package');
        $category->detail=$request->input('e_detail');
        $category->save();

        return redirect()->back()->with('success','Category details successfully updated');
    }
    public function getPackageCategory(Request $request){
        $package=Package::findOrFail($request->input('package_id'));
        $category=$package->categories;
        return Response::json($category,200);
    }
}
