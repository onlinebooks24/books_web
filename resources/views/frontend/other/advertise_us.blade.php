@extends('layouts.master')
@section('title') Advertise us| Online Books Review @endsection
@section('meta_description') Advertise us @endsection
@section('meta_keyword') Advertise us @endsection

@section('content')
    @include('includes.header')
    <br>
    <br>
    <br>

    <div class="container bottom30">
        <div class="row-fluid">
            <div class="span9">

                <div class="row">

                    <div class="col-xs-6 col-xs-offset-3">
                        <h2>Contact Us</h2>
                        <p>If you want to advertise on our site, please contact us at the e-mail address below:</p><p>Email: <a style="font-size:16px" href="mailto:info@onlinebooksreview.com">info@onlinebooksreview.com</a></p>

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