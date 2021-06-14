@extends('layouts.auth')

@section('mainContent')

<div class="text-gray-700 grid grid-cols-3 gap-4">
    @foreach($courses as $course)

        <div class="leading-relaxed p-4">
            <h4><strong>Title</strong>: {{$course->title}} - {{$course->abbreviation}} </h4>
            <p><strong>Description</strong>: <br> {{$course->description}}</p>
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

@endsection
