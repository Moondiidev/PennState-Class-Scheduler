@extends('layouts.auth')

@section('mainContent')

    <div>

        <form action="{{route('markAsCompleted')}}" method="post">
        @csrf

            <div class="flex flex-wrap -mx-3 mb-6">

                <div class="w-full md:w-1/5 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-semester">
                        Semester
                    </label>
                    <select name="semester[]" multiple class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-semester">
                        <option @if($course->semesters->contains(1)) selected @endif value="1">Fall</option>
                        <option @if($course->semesters->contains(2)) selected @endif value="2">Spring</option>
                        <option @if($course->semesters->contains(3)) selected @endif value="3">Summer</option>
                    </select>
                </div>

                <div class="w-full md:w-1/5 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-credits">
                        Number of Credits Desired
                    </label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-credits" type="number" placeholder="12">
                </div>

                <button type="submit" class="w-full sm:w-1/4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded mt-10">
                    Get Recommended Courses
                </button>
            </div>



        </form>


    </div>

@endsection
