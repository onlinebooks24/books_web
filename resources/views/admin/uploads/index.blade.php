@extends('layouts.admin_master')

@section('content')

    <div class="clearfix"></div>
    <div class="row">
        @if(Session::has('success'))
            <div class="alert alert-warning">
                {{Session::get('success')}}
            </div>
        @endif
        @if(count($errors) > 0)
            <ul class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{$error}}
                @endforeach
            </ul>
        @endif
    </div>

    <div class="row">
        <div class="alert alert-info">
            <strong>View All Articles</strong>
        </div>
    </div>
    <div class="row alert alert-success">
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="mytable" class="table table-bordred table-striped">
                    <thead>

                    <th>Name</th>
                    <th>Article</th>
                    <th>View</th>
                    <th>Delete</th>
                    </thead>
                    <tbody>

                    @foreach($uploads as $key => $upload)
                        <tr>
                            <td>{{ $upload->name }}</td>
                            <td><a target="_blank" href="{{ route('articles.show' , [ 'slug' => $upload->article->slug ])}}">{{ $upload->article->title }}</a></td>
                            <td>
                                <a target="_blank" href="{{ $upload->folder_path. $upload->name }}">
                                    <img style="width:300px" src="{{ $upload->folder_path. $upload->name }}">
                                </a>
                            </td>
                            <td><p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete{{++$key}}" ><span class="glyphicon glyphicon-trash"></span></button></p></td>
                        </tr>

                        <div class="modal fade" id="delete{{$key}}" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin_uploads.destroy' , $upload->id)}}" method="POST">
                                        {{ csrf_field() }}
                                        <input name="_method" type="hidden" value="DELETE">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                                            <h4 class="modal-title custom_align" id="Heading">Delete this entry</h4>
                                        </div>
                                        <div class="modal-body">

                                            <div class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Record? </div>

                                        </div>
                                        <div class="modal-footer ">
                                            <button type="submit" class="btn btn-success" ><span class="glyphicon glyphicon-ok-sign"></span> Yes</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> No</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>

                    @endforeach



                    </tbody>

                </table>

            </div>

        </div>

        <section>
            <nav>
                <ul class="pager">
                    @if($uploads->currentPage() !== 1)
                        <li class="previous"><a href="{{ $uploads->previousPageUrl() }}"><span aria-hidden="true">&larr;</span>Newer</a></li>
                    @endif
                    @if($uploads->currentPage() !== $uploads->lastPage() && $uploads->hasPages())
                        <li class="next"><a href="{{ $uploads->nextPageUrl() }}">Older <span aria-hidden="true">&rarr;</span></a></li>
                    @endif
                </ul>
            </nav>
        </section>
    </div>
@endsection
