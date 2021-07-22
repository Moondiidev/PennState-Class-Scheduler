@extends('layouts.auth')

@section('mainContent')

    <div>

        @if($warnings)

            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Holy smokes!</strong>

                <ul>
                @foreach($warnings as $warning)

                    <li class="ml-4 mt-2 list-disc">{{$warning}}</li>

                @endforeach
                </ul>

            </div>

        @endif


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

