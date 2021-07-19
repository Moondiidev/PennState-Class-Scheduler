@extends('layouts.auth')

@section('mainContent')

    <div>

        @if($suggestedCourses->isEmpty())

            <p>Sorry, we couldn't find any courses that fit your criteria...</p>

        @else

            <div class="text-gray-700 grid grid-cols-2 gap-6">
                @foreach($suggestedCourses as $course)

                    <div class="leading-relaxed p-4 rounded bg-gray-50">
                        <h4><strong>Title</strong>:
                            <a class="underline font-medium hover:no-underline" href="{{route('courses.show', $course->id)}}">
                                {{$course->title}} - {{$course->abbreviation}}
                            </a>
                        </h4>
                        <p><strong>Credits</strong>: {{$course->credits}}</p>
                    </div>

                @endforeach
            </div>

        @endif


    </div>

@endsection

