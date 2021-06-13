@extends('layouts.app')

@section('content')
    <main class="sm:container sm:mx-auto sm:mt-10">
        <div class="w-full sm:px-6">

            @if (session('status'))
                <div class="text-sm border border-t-8 rounded text-green-700 border-green-600 bg-green-100 px-3 py-4 mb-4" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <section class="flex flex-col break-words bg-white sm:border-1 sm:rounded-md sm:shadow-sm sm:shadow-lg">

                <header class="font-semibold bg-gray-200 text-gray-700 py-5 px-6 sm:py-6 sm:px-8 sm:rounded-t-md">
                    Courses
                </header>

                <div class="w-full p-6">
                    <div class="text-gray-700 grid grid-cols-3 gap-4">
                        @foreach($courses as $course)

                            <div class="leading-relaxed">
                            <h4><strong>Title</strong>: {{$course->title}} - {{$course->abbreviation}} </h4>
                            <p><strong>Description</strong>: <br> {{$course->description}}</p>
                            <p><strong>Credits</strong>: {{$course->credits}}</p>
                            <p><strong>Semester</strong>: {{$course->semester->name}}</p>

                            <h4><strong>Prerequisites </strong></h4>
                            @if(! $course->prerequisites)
                                None
                            @else
                                @foreach($course->prerequisites as $prereq)
                                        @if (! $loop->last)
                                            {{\App\Models\Course::find($prereq)->abbreviation . ","}}
                                        @else
                                            {{\App\Models\Course::find($prereq)->abbreviation}}
                                        @endif
                                @endforeach
                            @endif

                            </div>

                        @endforeach

                </div>
            </section>
        </div>
    </main>
@endsection
