@extends('layouts.auth')

@section('mainContent')

    <div>

        @if ($errors->any())
            <div role="alert" class="w-full max-w-3xl mb-8">
                <div class="bg-red-400 text-white font-bold rounded-t px-4 py-2">
                    Errors Found!
                </div>
                <div class="border border-t-0 border-red-300 rounded-b bg-red-200 px-4 py-3 text-red-700">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li class="m-2 list-disc">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif


        <form action="{{route('courses.update', $course)}}" method="post" class="w-full max-w-3xl" autocomplete="off">
            @csrf
            @method('PUT')

            @include('courses.partials.course-form')

            <div class="flex justify-between">
                <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-10">
                    Update
                </button>


            </div>
        </form>

    </div>

@endsection

