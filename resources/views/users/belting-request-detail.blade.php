@extends('layouts.app')
@section('title','Belting Request Details')

@section('content')
    @include('partials.banner')

    <!--// Main Content \\-->
    <div class="lifestyle-main-content">

        <!--// Main Section \\-->
        <div class="lifestyle-main-section">
            <div class="container">
                <div class="row">
                    @include('users.partials.side-bar')
                    <div class="col-md-9">
                        <div class="lifestyle-wrapper">
                            <h2 class="form-title">Belting Request Details</h2>
                            <table class="table table-detail">
                                <tr>
                                    <th>Name</th>
                                    <td>{{$beltingRequest->name}}</td>

                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{$beltingRequest->email}}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{$beltingRequest->phone_no}}</td>

                                </tr>
                                <tr>
                                    <th>Date/Time</th>
                                    <td>{{$beltingRequest->created_at}}</td>

                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{{$beltingRequest->status}}</td>

                                </tr>
                                <tr>
                                    <th>Message</th>
                                    <td>{{$beltingRequest->message}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--// Main Section \\-->

    </div>
    <!--// Main Content \\-->
@endsection
