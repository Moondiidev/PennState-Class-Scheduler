@extends('layouts.auth')

@section('mainContent')

    <div>

        <form action="{{route('markAsCompleted')}}" method="post">
            @csrf

            <div class="grid grid-cols-4 gap-2">

            @foreach($courses as $course)

                <div class="flex flex-col items-left justify-left">
                    <div class="flex flex-col">
                        <label class="inline-flex items-center mt-3">
                            <input type="checkbox" name="completed[]" class="form-checkbox h-5 w-5 text-gray-600" value="{{$course->id}}"
                            @if( in_array($course->id, $currentCompletedCourses) ) checked @endif>
                            <span class="ml-2 text-gray-700">{{$course->abbreviation}}</span>
                        </label>
                    </div>
                </div>

            @endforeach

            </div>

            <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-10">
                Update
            </button>

        </form>


    </div>

@endsection
