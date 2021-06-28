@extends('layouts.auth')

@section('mainContent')

    <div>

        <form action="{{route('markAsCompleted')}}" method="post">
        @csrf

            <div class="flex flex-wrap -mx-3 mb-3">
                <div class="w-full md:w-1/5 px-3 mb-3 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-semester">
                        Semester
                    </label>
                    <div class="relative">
                        <select name="semester" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-semester">
                            <option value="1">Fall</option>
                            <option value="2">Spring</option>
                            <option value="3">Summer</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-1/5 px-3 mb-3 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-credits">
                        Desired Credits
                    </label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-credits" type="number" placeholder="12">
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
