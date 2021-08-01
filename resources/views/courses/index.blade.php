@extends('layouts.auth')

@section('mainContent')

<div id="course-view" class="container mx-auto px-4">
    <!-- todo: add main page -->
    <div id="myTest" data="{{$courses}}" data-courses="{{$completedCourses}}">

    </div>


</div>

@endsection
