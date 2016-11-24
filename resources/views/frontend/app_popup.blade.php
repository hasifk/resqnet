@extends('frontend.layouts.master')
@section('after-scripts-end')
<script>
    $(document).ready(function () {
        window.location = "resqnet://";
    });
</script>
@stop