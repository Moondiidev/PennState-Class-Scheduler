@extends('layouts.auth')

@section('mainContent')

    <div>

        <form action="{{route('recommendationResults')}}" method="post">
        @csrf

            @if ($errors->any())

                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Holy smokes... we found errors!</strong>

                    <ul>
                        @foreach ($errors->all() as $error)

                            <li class="ml-4 mt-2 list-disc">{{ $error }}</li>

                        @endforeach
                    </ul>

                </div>
            @endif

            <div class="flex flex-wrap -mx-3 mb-3">
                <div class="w-full md:w-1/5 px-3 mb-3 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-semester">
                        Semester
                    </label>
                    <div class="relative">
                        <select name="semester" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-semester">
                            <option @if ( old('semester') == 1 ) selected @endif value="1">Fall</option>
                            <option @if ( old('semester') == 2 ) selected @endif value="2">Spring</option>
                            <option @if ( old('semester') == 3 ) selected @endif value="3">Summer</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-1/5 px-3 mb-3 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-credits">
                        # of Courses
                    </label>
                    <input name="number_of_courses" value="{{old('number_of_courses')}}" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-credits" type="number" placeholder="5">
                </div>
            </div>

            <div class="flex flex-wrap mx-2 mb-2">

                <button type="submit" class="w-full sm:w-1/4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded mt-10">
                    Get Recommended Courses
                </button>

            </div>

        </form>


    </div>

@endsection
