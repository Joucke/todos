@extends('layouts.app-tailwind-ui')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
  @include('auth.header', ['title' => __('auth.reset_password')])

  <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
    <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
      <form action="{{ route('password.email') }}" method="POST">
        @csrf

        <div>
          <label for="email" class="block text-sm font-medium leading-5 text-gray-700">
            {{ __('users.email') }}
          </label>
          <div class="mt-1 rounded-md shadow-sm">
            <input id="email" type="email" name="email" placeholder="you@example.com" value="{{ old('email') }}" required autofocus class="appearance-none block w-full px-3 py-2 border rounded-md focus:outline-none transition duration-150 ease-in-out sm:text-sm sm:leading-5{{ $errors->has('email') ? ' border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red' : 'border-gray-300 placeholder-gray-400 focus:shadow-outline-blue focus:border-blue-300' }}" />
          </div>
          @if ($errors->has('email'))
            <p class="mt-2 text-sm text-red-600">{{ $errors->first('email') }}</p>
          @endif
        </div>

        <div class="mt-6">
          <span class="block w-full rounded-md shadow-sm">
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
              {{ __('auth.send_link') }}
            </button>
          </span>
        </div>
      </form>

      <div class="mt-6">
        <div class="relative">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
          </div>
          <div class="relative flex justify-center text-sm leading-5">
            <span class="px-2 bg-white text-gray-500">
              {{ __('auth.options') }}
            </span>
          </div>
        </div>

        <div class="mt-6 grid grid-cols-2 gap-3">
          @if (Route::has('login'))
            <div>
              <span class="w-full inline-flex rounded-md shadow-sm">
                <a href="{{ route('login') }}" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md bg-white text-xs leading-5 font-medium text-gray-500 hover:text-gray-400 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition duration-150 ease-in-out">
                  {{ __('auth.login') }}
                </a>
              </span>
            </div>
          @endif

          @if (Route::has('register'))
            <div>
              <span class="w-full inline-flex rounded-md shadow-sm">
                <a href="{{ route('register') }}" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md bg-white text-xs leading-5 font-medium text-gray-500 hover:text-gray-400 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition duration-150 ease-in-out">
                  {{ __('auth.register') }}
                </a>
              </span>
            </div>
          @endif

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
