
<div class="col-md-3">
    <div class="lifestyle-typo-wrap">
        <div class="lifestyle-dashboard-nav">
            <ul class="nav">
                @forelse($package->categories as $key=>$category)
                <li class="nav-item">
                    <a class="nav-link collapsed colap" data-toggle="collapse" href="#open{{$key}}" aria-expanded="true">
                        <i class="fa fa-align-center"></i>
                        <p>{{$category->name}}</p>
                    </a>
                    <div class="collapse lifestyle-sidebar-drop" id="open{{$key}}" style="">
                        <ul class="nav">
                            @forelse($category->subCategories as $key2=>$sub_category)
                            <li class="nav-item">
                                <a class="nav-link collapsed colap" data-toggle="collapse" href="#open{{$key}}{{$key2}}" aria-expanded="true">
                                    <i class="fa fa-align-center"></i>
                                    <p>{{$sub_category->name}}</p>
                                </a>
                            </li>
                                <div class="collapse lifestyle-sidebar-drop" id="open{{$key}}{{$key2}}" style="">
                                    <ul class="nav">
                                        @forelse($sub_category->videos as $video)
                                        <li class="nav-item">
                                            <a class="nav-link " href="{{route('user.video-information',$video->id)}}">
                                                <span class="sidebar-normal">{{$video->title}}</span>
                                            </a>
                                        </li>
                                        @empty
                                        <li class="nav-item">
                                            No Videos Found!
                                        </li>
                                        @endforelse
                                    </ul>
                                </div>
                            @empty
                                <li class="nav-item">
                                    No Sub Category Found!
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </li>
                @empty
                    <li class="nav-item">
                        <p>No Category Found!</p>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
