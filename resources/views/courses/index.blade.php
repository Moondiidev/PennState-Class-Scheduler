@extends('layouts.auth')

@section('mainContent')

<div class="text-gray-700 grid grid-cols-2 gap-6">
    @foreach($courses as $course)

        <div class="leading-relaxed p-4 rounded bg-gray-50">
            <h4><strong>Title</strong>:
                <a class="underline font-medium hover:no-underline" href="{{route('courses.show', $course->id)}}">
                    {{$course->title}} - {{$course->abbreviation}}
                </a>
            </h4>
            <p><strong>Credits</strong>: {{$course->credits}}</p>
            <p><strong>Semester(s)</strong>:
                @foreach($course->semesters as $semester)
                    @if (! $loop->last)
                        {{$semester->name}},
                    @else
                        {{$semester->name}}
                    @endif
                @endforeach
            </p>
        </div>

    @endforeach
</div>

@endsection
