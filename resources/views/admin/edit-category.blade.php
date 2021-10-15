@extends('admin.layouts.app')
@section('title','Edit Category')

@section('content')
    <div class="app-main__inner">
        <div class="card-header-tab card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-server mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Edit Category Details</div>
        </div>
        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="tabs-animation">
                    <div class="row">
                        <div class="col-md-12">
                            @include('partials.flash-message')
                            <form action="{{route('admin.edit-category',[$category->id])}}" method="POST" enctype="multipart/form-data"  id="edit-category-form">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Package</label>
                                            <select name="package" id="package" class="form-control">
                                                <option value="" disabled selected>Select Package</option>
                                                @foreach($packages as $package)
                                                    <option value="{{$package->id}}" @if($category->package->id==$package->id) selected @endif>{{$package->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('package')
                                            <label class="error" for="package">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Category Name</label>
                                            <input type="text" placeholder="" name="name" id="name" class="form-control"  value="{{$category->name}}">
                                            @error('name')
                                            <label class="error" for="name">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Image</label>
                                            <input type="file" accept="image/*" placeholder="" name="image" id="image" class="form-control">
                                            @error('image')
                                            <label class="error" for="image">{{$message}}</label>
                                            @enderror
                                        </div>
                                        <label id="image-error" class="error" for="image" style="display: none"></label>

                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Category Details</label>
                                            <textarea name="detail" id="detail" class="form-control">{{$category->detail}}</textarea>
                                            @error('detail')
                                            <label class="error" for="detail">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary pull-right" >Submit</button>
                                <div class="clearfix"></div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>

        $('#image').fileinput({
            theme: 'fas',
            allowedFileExtensions: ["jpg", "png", "jpeg"],
            initialPreview: [
                "<img src='{{asset('uploads/category/'.$category->image)}}' class='file-preview-image' alt=''>",
            ],
            initialPreviewShowDelete: false

        })
        $('#package').select2({
            ajax: {
                url: path+'/admin/get-selected-packages-list',
                data: function (params) {
                    var query = {
                        search: params.term,
                    }
                    console.log(query)
                    return query;
                },
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'

                    console.log(data)
                    return {
                        results: data
                    };
                }
            }
        });

    </script>
@endsection
