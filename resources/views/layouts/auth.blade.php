@extends('layouts.app')

@section('content')
    <main class="sm:max-w-4xl sm:mx-auto sm:mt-10">
        <div class="w-full sm:px-6">

            <div id="navigation" data='{{$navItems}}'>
            </div>

            @if (session('status'))
                <div class="text-sm border border-t-8 rounded text-green-700 border-green-600 bg-green-100 px-3 py-4 mb-4" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <section class="flex flex-col break-words bg-white sm:border-1 sm:rounded-md sm:shadow-sm sm:shadow-lg">

                <header class="font-semibold bg-gray-200 text-gray-700 py-5 px-6 sm:py-6 sm:px-8 sm:rounded-t-md">
                    <div class="flex justify-between">
                        <span>
                            {{$pageName}}
                        </span>

                        @if ( isset($headerButtonAction) )
                            <span>
                                <a href="{{$headerButtonAction}}"
                                   class="bg-{{$headerButtonColor ?? 'gray'}}-500 hover:bg-{{$headerButtonColor ?? 'gray'}}-700 text-white font-bold py-2 px-4 rounded mt-10">
                                    {{$headerButtonText}}
                                </a>
                            </span>
                        @endif

                    </div>
                </header>
                <div class="w-full p-6">

                    @yield('mainContent')
                </div>

            </section>
        </div>
    </main>
@endsection
