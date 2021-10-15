{{--Modal--}}
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data"  id="edit-category-form">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Category Name</label>
                                <input type="text" placeholder="" name="e_name" id="name" class="form-control"  value="">
                                @error('e_name')
                                <label class="error" for="name">{{$message}}</label>
                                @enderror
                            </div>
                        </div>
{{--                        <div class="col-md-12">--}}
{{--                            <div class="form-group">--}}
{{--                                <label>Category Details</label>--}}
{{--                                <textarea name="e_detail" id="detail" class="form-control"></textarea>--}}
{{--                                @error('e_detail')--}}
{{--                                <label class="error" for="detail">{{$message}}</label>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="subCategoryModal" tabindex="-1" role="dialog" aria-labelledby="subCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data"  id="edit-sub-category-form">
                @csrf
                @method('put')
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Sub Category Name</label>
                                    <input type="text" placeholder="" name="e_name" id="name" class="form-control"  value="">
                                    @error('e_name')
                                    <label class="error" for="name">{{$message}}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Sub Category Details</label>
                                    <textarea name="e_detail" id="detail" class="form-control"></textarea>
                                    @error('e_detail')
                                    <label class="error" for="detail">{{$message}}</label>
                                    @enderror
                                </div>
                            </div>
                        </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    {{--let path = '{{env('APP_URL')}}';--}}
    let path='';

</script>
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
        crossorigin="anonymous"></script>
<script type="text/javascript" src="{{asset('assets\scripts\main.js')}}"></script>
<script type="text/javascript" src="{{asset('assets\scripts\functions.js')}}"></script>
<script src="{{asset('script\jquery-modal.js')}}"></script>
<script type="text/javascript" src="{{asset('assets\scripts\lc_lightbox.lite.js')}}"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
        integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script src="{{asset('assets\scripts\select1.min.js')}}"></script>
<script src="{{asset('assets\scripts\fileinput.js')}}"> </script>
{{--Block ui--}}
<script src="{{asset('script/jquery.blockUI.js')}}"></script>
<script>
    // $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
    $(document).ajaxStop($.unblockUI);

</script>
{{----}}
{{-- <script src="{{asset('/assets/scripts/theme.js')}}"> </script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.9/themes/fas/theme.min.js"></script>
<script src="{{asset('assets\scripts\pagination.min.js')}}"></script>
@if(Route::currentRouteName()=='admin.new-video-upload')
    <script src="{{asset('assets\scripts\upload_new_video.js')}}"></script>
@endif
@if(Route::currentRouteName()=='admin.videos-list')
    <script src="{{asset('assets\scripts\video_list.js')}}"></script>
@endif
@if(Route::currentRouteName()=='admin.package-list')
    <script src="{{asset('assets\scripts\package_list.js')}}"></script>
@endif
@if(Route::currentRouteName()=='admin.category-list')
    <script src="{{asset('assets\scripts\category_list.js')}}"></script>
@endif
@if(Route::currentRouteName()=='admin.users')
    <script src="{{asset('assets\scripts\user_list.js')}}"></script>
@endif
@if(Route::currentRouteName()=='admin.belting-evolution-requests')
    <script src="{{asset('assets\scripts\belting_request_datatable.js')}}"></script>
@endif
@if(Route::currentRouteName()=='admin.edit-user')
    <script src="{{asset('assets\scripts\user_edit.js')}}"></script>
@endif
@include('partials.additional-validation')

@include('admin.partials.validation-script')
<script>
    $(document).ready(function() {
        // image popup lightbox
        lc_lightbox('.elem', {
            wrap_class: 'lcl_fade_oc',
            gallery: false,
            thumb_attr: 'data-lcl-thumb',

            skin: 'minimal',
            radius: 0,
            padding: 0,
            border_w: 0,
        });
    });
</script>
@yield('script')

</body>

</html>
