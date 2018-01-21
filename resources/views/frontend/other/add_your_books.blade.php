@extends('layouts.master')
@section('title') Add Your Books | Online Books Review @endsection
@section('meta_description') Add Your Books @endsection
@section('meta_keyword') Add Your Books @endsection

@section('content')
    @include('includes.header')
    <br>
    <br>
    <div class="container bottom30">
        <div class="row-fluid">
            <div class="span9">

                <div class="row">

                    <div class="col-xs-6 col-xs-offset-3">
                        <h2>Add Your Books</h2>
                        <p>If you want to add your book, please contact us at the e-mail address below:</p><p>Email: <a style="font-size:16px" href="mailto:info@onlinebooksreview.com">info@onlinebooksreview.com</a></p>
                        <br>
                        <br>
                        <br>
                        <div class="top30">
                            <h4>
                                <strong><a class="pull-right" style="float:right" href="/">Back to homepage</a></strong>
                            </h4>
                        </div>


                        <table class="table table-striped">
                            <tbody>





                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div> <!--row -->
        @include('includes.footer')
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
@endsection