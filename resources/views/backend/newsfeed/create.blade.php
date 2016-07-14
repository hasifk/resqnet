@extends ('backend.layouts.master')

@section ('title', 'Admin Create Company Details')

@section('page-header')
<h1>
    Create News Feed
</h1>
@endsection

@section('content')

@include('backend.admin.includes.partials.newsfeeds.header-buttons')

<section class="content">
    <div class="row" id='notification_add'>
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box box-primary">
                    <!--                <div class="box-header with-border">
                                        <h3 class="box-title">Add Push Notifications</h3>
                                    </div> /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="{{ route('backend.admin.newsfeedsave') }}" accept-charset="UTF-8" role="form" >
                         {{ csrf_field() }}
                        <div class="box-body">
                            <div class="form-group">
                                <label for="exampleInputPassword1">News Feeds</label>
                                <textarea class="form-control textarea" name="newsfeed" cols="30" rows="5"></textarea>
                            </div>
                        </div><!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div><!-- /.box -->
            </div>
        </div>
    </div>
</section>
@endsection
