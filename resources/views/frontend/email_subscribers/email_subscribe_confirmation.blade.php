@extends('layouts.master')
@section('title') Email Subscription | Online Books Review @endsection
@section('meta_description') Email Subscription @endsection
@section('meta_keyword') Email Subscription @endsection

@section('content')
    @include('includes.header')
    <br>
    <div class="container">
        <div class="row-fluid">
            <div class="span9">

                <div class="row">

                    <div class="col-xs-6 col-xs-offset-3">
                        <h2>Email Subscription</h2>
                            @if($email_subscriber_message == 'success')
                                <div>Successfully subscribed</div>
                            @elseif($email_subscriber_message == 'exist')
                                <div>Already you had subscribed</div>
                            @endif

                        <br>
                        <br>
                        <br>
                        <br>
                        <h4>
                            <strong><a style="float:right" href="/">Back to homepage</a></strong>
                        </h4><table class="table table-striped">
                            <tbody>





                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div> <!--row -->
        @include('includes.footer')
    </div>
@endsection