@extends('layouts.auth')

@section('mainContent')

<div class="text-gray-700">
    <ul class="list-disc px-12">
        <li class="mb-6">
            <a class="underline hover:no-underline hover:text-blue-700" href="{{route('courses')}}">View Courses</a>
        </li>

        <li class="mb-6">
            <a class="underline hover:no-underline hover:text-blue-700" href="{{route('completedForm')}}">Mark Completed Courses</a>
        </li>
    </ul>
</div>

@endsection
