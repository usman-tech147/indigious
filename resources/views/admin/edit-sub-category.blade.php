@extends('admin.layouts.app')
@section('title','Add New Sub Category')

@section('content')
    <div class="app-main__inner">
        <div class="card-header-tab card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-folder mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Add new sub category.</div>
        </div>
        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="tabs-animation">
                    <div class="row">
                        <div class="col-md-12">
                            @include('partials.flash-message')
                            <form action="{{route('admin.edit-sub-category',[$sub_category->id])}}" method="POST" enctype="multipart/form-data"  id="new-sub-category-form">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Category</label>
                                            <select name="category_id" id="category" class="form-control">
                                                <option value="" disabled selected>Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{$category->id}}" @if($category->id==$sub_category->category_id) selected @endif >{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                            <label class="error" for="package">{{$message}}</label>
                                            @enderror
                                            <label id="category-error" class="error" for="category" style="display: none"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Sub Category Name</label>
                                            <input type="text" placeholder="" name="name" id="name" class="form-control"  value="{{$sub_category->name}}">
                                            @error('name')
                                            <label class="error" for="name">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Sub Category Details</label>
                                            <textarea name="detail" id="detail" class="form-control">{{$sub_category->detail}}</textarea>
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

        // $('#image').fileinput({
        //     theme: 'fas',
        //     allowedFileExtensions: ["jpg", "png", "jpeg"],
        // })
        $('#category').select2({
            ajax: {
                url: '{{route('admin.getPackageCategoriesId')}}',
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
