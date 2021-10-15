<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Common\PayPalModel;
use PayPal\Exception\PayPalConnectionException;

class PackageController extends Controller
{
    public function addNewPackage(Request $request)
    {
        $request->validate([
            'package_name' => ['required', 'unique:packages,name', 'max:' . env('INPUT_SMALL')],
            'thumbnail' => ['required', 'mimes:jpg,jpeg,png'],
            'price_year' => ['required', 'digits_between:0,' . env('INPUT_SMALL')],
            'detail' => ['required', 'max:' . env('TEXT_AREA_LIMIT')],
            'free_video_1' => 'mimetypes:video/avi,video/mp4,video/quicktime,video/x-matroska',
            'free_video_2' => 'mimetypes:video/avi,video/mp4,video/quicktime,video/x-matroska',

        ]);
        if ($request->hasFile('thumbnail')) {
            $video1Name = null;
            $video2Name = null;
            $admin = Auth::guard('admin')->user();
            $image = $request->file('thumbnail');
            $imageName = time() . $image->getClientOriginalName();
            $image->move(public_path('uploads/package/'), $imageName);

            if ($request->hasFile('free_video_1')) {
                $video1 = $request->file('free_video_1');
                $video1Name = time() . uniqid() . $video1->getClientOriginalName();
                $video1->move(public_path('/uploads/package'), $video1Name);

            }
            if ($request->hasFile('free_video_2')) {
                $video2 = $request->file('free_video_2');
                $video2Name = time() . uniqid() . $video2->getClientOriginalName();
                $video2->move(public_path('/uploads/package'), $video2Name);

            }
            $package = new Package([
                'name' => $request->input('package_name'),
                'thumbnail' => $imageName,
                'price_year' => $request->input('price_year'),
                'detail' => $request->input('detail'),
                'free_video_1' => $video1Name,
                'free_video_2' => $video2Name,
                'admin_id' => $admin->id,
            ]);
            if ($package->save()) {
                try {
                    \Stripe\Stripe::setApiKey(env('STRIPE_SK'));
                    $product = \Stripe\Product::create([
                        'name' => $request->input('package_name'),
                        'description' => $request->input('detail'),
                        'images' => [asset('uploads/package/' . $package->thumbnail)]
                    ]);

//                 yearly
                    $price_year_stripe = \Stripe\Price::create([
                        'product' => $product->id,
                        'unit_amount' => $request->input('price_year') * 100,
                        'currency' => 'usd',
                        'recurring' => [
                            'interval' => 'year',
                        ],
                    ]);
                    $package->stripe_product_id = $product->id;
                    $package->stripe_price_year_id = $price_year_stripe->id;
                    $package->save();
                } catch (\Exception $er) {
                    if ($package->delete()) {
                        try {
                            unlink(public_path('/uploads/package/' . $package->thumbnail));
                        } catch (\Exception $ex) {

                        }
                    }
                    return redirect()->back()->with('danger', 'Some Error Has Occurred!');
                }

                //                PAYPAL
                try {
                    $productPaypal = PayPalController::createProduct($package->name, $package->detail, env('APP_URL').'/uploads/package/' . $package->thumbnail);
                    $paypalPlanYear = PayPalController::createPlan($productPaypal->id, $package->name, 'year', 1, $package->price_year);
                    $package->paypal_product_id = $productPaypal->id;
                    $package->paypal_plan_year_id = $paypalPlanYear->id;
                    $package->save();
                } catch (\Exception $ex) {
                    if ($package->delete()) {
                        try {
                            unlink(public_path('/uploads/package/' . $package->thumbnail));
                        } catch (\Exception $ex) {

                        }
                    }
//                    dd($ex->getCode(), $ex->getLine(), $ex->getMessage());
                    return redirect()->back()->with('danger', 'Some Error Has Occurred Paypal!');
                }

            } else {
//                dd("outside: ");
                return redirect()->back()->with('danger', 'Some Error Has Occurred if else!');

            }
            return redirect()->back()->with('success', 'Package added');
        }
    }

    public function packageListDataTable(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'thumbnail',
            3 => 'price',
            4 => 'price_year',
            5 => 'detail',
            6 => 'action'
        );
//        $packages=DB::table('packages')->get();
        $totalData = Package::all()->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
//        $order = $columns[$request->input('order.0.column')];
//        $dir = $request->input('order.0.dir');


        $packages = Package
            ::limit($limit)
            ->offset($start)
            ->get();


        $name = $request->input('package_name');

        $packages = Package::where(function ($query) use ($name) {
            if (!empty($name)) {
                $query->where('name', 'LIKE', "%" . $name . "%");
            }
        })
            ->offset($start)
            ->limit($limit)
            ->get();

        $totalFiltered = $packages->count();

        $data = array();
        if (!empty($packages)) {
            $i = $start + 1;
            foreach ($packages as $package) {
                $edit = "/admin/edit-package/" . $package->id;
                $delete = "/admin/delete-package/" . $package->id;
                $view = "/admin/package-details/" . $package->id;
                $category = route('admin.category-list', [$package->id]);
                $imagePath = asset('uploads/package/' . $package->thumbnail);
                $nestedData['no'] = $i++;
                $nestedData['id'] = $package->id;
                $nestedData['package_name'] = $package->name;
                $nestedData['thumbnail'] = '<a class="elem imgzoom" href="' . $imagePath . '" data-lcl-thumb="' . $imagePath . '"><i class="fa fa-picture-o"></i></a>';
                $nestedData['price'] = '$' . $package->price;
                $nestedData['price_year'] = '$' . $package->price_year;
                $nestedData['price_months'] = '$' . $package->price_six;
                $nestedData['detail'] = $package->detail;
                $nestedData['action'] = '<a href=' . $view . ' class="tag">View</a><a href=' . $edit . ' class="tag">Edit</a> <a href=' . $delete . ' data-id=' . $package->id . ' class="tag btn-delete-package">Delete</a><a href=' . $category . '  class="tag">Manage Categories</a>';
                $data[] = $nestedData;
            }
        }

        $json = array(
            "draw" => intval(\request('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        return Response::json($json);
    }

    public function editPackage($id)
    {
        $package = Package::findOrFail($id);
        return view('admin.edit-package', compact('package'));
    }

    public function updatePackage(Request $request, $id)
    {
        $request->validate([
            'package_name' => 'required|max:' . env('INPUT_SMALL'),
            'detail' => 'required|max:' . env('TEXT_AREA_LIMIT'),
            'thumbnail' => 'mimes:jpg,png,jpeg',
//            'price'=>['required','digits_between:0,'.env('INPUT_SMALL')],
//            'price_six'=>['required','digits_between:0,'.env('INPUT_SMALL')],
            'price_year' => ['required', 'digits_between:0,' . env('INPUT_SMALL')],
            'free_video_1' => 'mimetypes:video/avi,video/mp4,video/quicktime,video/x-matroska',
            'free_video_2' => 'mimetypes:video/avi,video/mp4,video/quicktime,video/x-matroska',


        ]);

        \Stripe\Stripe::setApiKey(env('STRIPE_SK'));
        $package = Package::findOrFail($id);

        if (Package::where('name', $request->input('package_name'))->first() != null) {
            if ($package->name != Package::where('name', $request->input('package_name'))->first()->name) {
                return redirect()->back()->with('danger', 'Package Name Already Taken.');
            }
//            $package->name=$request->input('package_name');

        }
//            $package->price=$request->input('price');
        $package->name = $request->input('package_name');
        $package->detail = $request->input('detail');

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailName = time() . $thumbnail->getClientOriginalName();
            $thumbnail->move(public_path('/uploads/package'), $thumbnailName);
            try {
                unlink(public_path('/uploads/package/' . $package->thumbnail));

            } catch (\Exception $ex) {

            }
            $package->thumbnail = $thumbnailName;
        }
        if ($request->hasFile('free_video_1')) {
            $thumbnail = $request->file('free_video_1');
            $thumbnailName = time() . uniqid() . $thumbnail->getClientOriginalName();
            $thumbnail->move(public_path('/uploads/package'), $thumbnailName);
            try {
                unlink(public_path('/uploads/package/' . $package->free_video_1));

            } catch (\Exception $ex) {

            }
            $package->free_video_1 = $thumbnailName;
        }
        if ($request->hasFile('free_video_2')) {
            $thumbnail = $request->file('free_video_2');
            $thumbnailName = time() . uniqid() . $thumbnail->getClientOriginalName();
            $thumbnail->move(public_path('/uploads/package'), $thumbnailName);
            try {
                unlink(public_path('/uploads/package/' . $package->free_video_2));

            } catch (\Exception $ex) {

            }
            $package->free_video_2 = $thumbnailName;
        }
        if ($package->save()) {
//                    ============================Stripe product=======================
            try {
                $product = \Stripe\Product::update(
                    $package->stripe_product_id,
                    [
                        'name' => $request->input('package_name'),
                        'description' => $request->input('detail'),
                        'images' => [asset('uploads/package/' . $package->thumbnail)]
                    ]);
            } catch (\Exception $ex) {

            }


            //            ============================PayPal product=======================
            try {
                PayPalController::updateProduct($package->paypal_product_id, $request->input('detail'), '', asset('uploads/package/' . $package->thumbnail), '');

            } catch (\Exception $ex) {

            }

//
//                    ====================================PRICE UPDATE==================================
//                monthly
//            if($package->price!=$request->input('price')){
//                $price = \Stripe\Price::create([
//                    'product' => $product->id,
//                    'unit_amount' => $request->input('price')*100,
//                    'currency' => 'usd',
//                    'recurring' => [
//                        'interval' => 'month',
//                    ],
//                ]);
//                $package->stripe_price_id=$price->id;
//                PayPalController::updatePrice($package->paypal_plan_id,$request->input('price'));
//            }
//
//            //                6 monthly
//            if($package->price_six!=$request->input('price_six')){
//                $price_six_stripe = \Stripe\Price::create([
//                    'product' => $product->id,
//                    'unit_amount' => $request->input('price_six')*100,
//                    'currency' => 'usd',
//                    'recurring' => [
//                        'interval' => 'month',
//                        'interval_count' => 6,
//                    ],
//                ]);
//                $package->stripe_price_six_id=$price_six_stripe->id;
//                PayPalController::updatePrice($package->paypal_plan_six_id,$request->input('price_six'));
//
//            }

            //                 yearly
            if ($package->price_year != $request->input('price_year')) {

                $price_year_stripe = \Stripe\Price::create([
                    'product' => $product->id,
                    'unit_amount' => $request->input('price_year') * 100,
                    'currency' => 'usd',
                    'recurring' => [
                        'interval' => 'year',
                    ],
                ]);
                $package->stripe_price_year_id = $price_year_stripe->id;
                PayPalController::updatePrice($package->paypal_plan_year_id, $request->input('price_year'));


            }
            $users = User::all();
            $price_update = null;
            foreach ($users as $user) {
                $pkg = $user->packages->find($package->id);
                if ($pkg != null) {
//                    if($package->price!=$request->input('price')){
//                        if($pkg->pivot->frequency=='MONTH' && $pkg->pivot->interval_count==1){
//                            $price_update=$price->id;
//                        }
//                    }
//                    if($package->price_six!=$request->input('price_six')) {
//                        if($pkg->pivot->frequency=='MONTH' && $pkg->pivot->interval_count==6){
//                            $price_update=$price_six_stripe->id;
//                        }
//                    }
                    if ($package->price_year != $request->input('price_year')) {
                        if ($pkg->pivot->frequency == 'YEAR' && $pkg->pivot->interval_count == 1) {
                            $price_update = $price_year_stripe->id;
                        }
                    }
                    if ($price_update != null) {
                        try {
                            $subscription = \Stripe\Subscription::retrieve($pkg->pivot->subscription_id);
                            \Stripe\Subscription::update($pkg->pivot->subscription_id, [
                                'cancel_at_period_end' => false,
                                'proration_behavior' => 'none',
                                'items' => [
                                    [
                                        'id' => $subscription->items->data[0]->id,
                                        'price' => $price_update,
                                    ],
                                ],
                            ]);
                        } catch (\Exception $ex) {

                        }
                    }


                }
                $price_update = null;
            }

            $package->stripe_product_id = $product->id;
//            $package->price=$request->input('price');
//            $package->price_six=$request->input('price_six');
            $package->price_year = $request->input('price_year');
            $package->save();

//                   ====================================== PAYPAL========================================


//                    ======================================
//            $package->save();
        }
        return redirect()->back()->with('success', 'Package details successfully updated');
    }

    public function deletePackage($id)
    {
        $package = Package::findOrFail($id);
        if ($package->name == 'ReevesMMA') {
            return Response::json(['error' => 'Can\'t Delete'], 500);
        }

        if ($package->delete()) {
            try {
                unlink(public_path('/uploads/package/' . $package->thumbnail));
            } catch (\Exception $ex) {

            }
        } else {
            return Response::json(['error' => 'Can\'t Delete'], 500);
        }
    }

    public function getPackageDetails($id)
    {
        $package = Package::findOrFail($id);

        return view('admin.package-detail', compact('package'));
    }

    public function getVideo(Request $request, $id)
    {

        $video = Video::findOrFail($id);

        $responsesCollection = collect([json_decode(VdoCipherController::getOtp($video->video_id), true)]);
        return Response::json(array(
            'response' => $responsesCollection,
        ));
    }

    public function deleteFreeVideo(Request $request, $id)
    {
        $key = $request->input('key');

        $package = Package::find($id);
        if ($package) {
            try {
                if ($key == 1) {
                    $video = $package->free_video_1;
                    $package->free_video_1 = null;
                } else if ($key == 2) {
                    $video = $package->free_video_2;
                    $package->free_video_2 = null;
                }
//                dd(asset('uploads\package\\'.$video));
                unlink(public_path('uploads/package/' . $video));
                if ($package->save()) {
//                    dd($request->all());
                    return '{}';
                }


            } catch (\Exception $exception) {
                dd($exception);
            }
        }
    }
}
