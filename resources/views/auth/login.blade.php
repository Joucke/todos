@extends('layouts.app-tailwind-ui')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-md">
    <svg class="h-12 mx-auto w-auto" fill="none" viewBox="0 0 60 24">
      <path stroke="currentColor" class="text-gray-900" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 14l2 2 4-4M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H9zm0 0a2 2 0 002 2h2a2 2 0 002-2H9zm0 0a2 2 0 012-2h2a2 2 0 012 2H9z"/>
      <path stroke="currentColor" class="text-indigo-600" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" d="M28.913 11.662h-2.285V18h-.933v-6.338h-2.28v-.771h5.498v.771zm6.738 3.013c0 .696-.117 1.305-.351 1.826-.235.518-.567.913-.996 1.186-.43.274-.931.41-1.504.41a2.73 2.73 0 01-1.49-.41 2.761 2.761 0 01-1.01-1.176c-.238-.511-.36-1.102-.366-1.773v-.512c0-.684.118-1.288.356-1.812s.573-.924 1.006-1.201c.436-.28.934-.42 1.494-.42.57 0 1.071.138 1.504.415.436.273.771.672 1.006 1.196.234.521.351 1.128.351 1.822v.449zm-.932-.46c0-.842-.17-1.488-.508-1.938-.339-.452-.812-.678-1.421-.678-.592 0-1.06.226-1.401.678-.339.45-.513 1.075-.523 1.875v.523c0 .817.171 1.46.513 1.928.345.466.819.699 1.42.699.606 0 1.075-.22 1.407-.66.332-.442.503-1.075.513-1.899v-.527zM37.409 18v-7.11h2.007c.619 0 1.165.137 1.64.41.476.274.842.663 1.1 1.168.26.504.392 1.084.395 1.738v.454c0 .67-.13 1.258-.39 1.763a2.713 2.713 0 01-1.11 1.162c-.478.27-1.036.409-1.674.415h-1.968zm.938-6.338v5.571h.986c.723 0 1.284-.224 1.685-.673.403-.45.605-1.09.605-1.92v-.414c0-.808-.19-1.434-.571-1.88-.378-.45-.915-.677-1.612-.684h-1.093zm11.445 3.013c0 .696-.117 1.305-.352 1.826-.234.518-.566.913-.996 1.186-.43.274-.93.41-1.504.41a2.73 2.73 0 01-1.489-.41 2.76 2.76 0 01-1.01-1.176c-.238-.511-.36-1.102-.367-1.773v-.512c0-.684.119-1.288.357-1.812.237-.524.573-.924 1.006-1.201.436-.28.934-.42 1.494-.42.57 0 1.07.138 1.504.415.436.273.771.672 1.005 1.196.235.521.352 1.128.352 1.822v.449zm-.933-.46c0-.842-.169-1.488-.507-1.938-.339-.452-.813-.678-1.421-.678-.593 0-1.06.226-1.402.678-.338.45-.512 1.075-.522 1.875v.523c0 .817.17 1.46.513 1.928.345.466.818.699 1.42.699.606 0 1.075-.22 1.407-.66.332-.442.503-1.075.512-1.899v-.527z"/>
      <path stroke="currentColor" class="text-indigo-500" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" d="M53.92 14.831c-.804-.231-1.39-.514-1.758-.85a1.639 1.639 0 01-.547-1.25c0-.56.223-1.022.67-1.386.448-.368 1.031-.552 1.747-.552.489 0 .923.094 1.304.283.384.189.68.45.889.781.211.333.317.695.317 1.09H55.6c0-.43-.137-.767-.41-1.011-.274-.248-.66-.371-1.158-.371-.462 0-.823.102-1.084.307-.257.202-.386.483-.386.845 0 .29.123.535.367.737.247.199.665.381 1.255.547.592.166 1.054.35 1.386.552.336.198.583.431.742.698.163.267.245.581.245.942 0 .576-.225 1.039-.674 1.387-.45.345-1.05.518-1.802.518-.488 0-.944-.093-1.367-.279-.423-.188-.75-.446-.982-.771a1.887 1.887 0 01-.341-1.108h.942c0 .43.158.77.474 1.02.319.247.743.371 1.274.371.495 0 .874-.1 1.138-.303a.984.984 0 00.395-.825c0-.348-.122-.617-.366-.805-.244-.193-.687-.381-1.328-.567z"/>
    </svg>
    <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">
      {{ __('auth.login') }}
    </h2>
  </div>

  <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
    <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
      <form action="{{ route('login') }}" method="POST">
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
          <label for="password" class="block text-sm font-medium leading-5 text-gray-700">
            {{ __('users.password') }}
          </label>
          <div class="mt-1 rounded-md shadow-sm">
            <input id="password" type="password" name="password" required class="appearance-none block w-full px-3 py-2 border rounded-md focus:outline-none transition duration-150 ease-in-out sm:text-sm sm:leading-5{{ $errors->has('password') ? ' border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red' : 'border-gray-300 placeholder-gray-400 focus:shadow-outline-blue focus:border-blue-300' }}" />
          </div>
          @if ($errors->has('password'))
            <p class="mt-2 text-sm text-red-600">{{ $errors->first('password') }}</p>
          @endif
        </div>

        <div class="mt-6 flex items-center justify-between">
          <div class="flex items-center">
            <input id="remember_me" name="remember" type="checkbox" {{ old('remember', false) ? ' checked' : '' }} class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" />
            <label for="remember_me" class="ml-2 block text-sm leading-5 text-gray-900">
              {{ __('auth.remember') }}
            </label>
          </div>

          {{-- <div class="text-sm leading-5">
            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">
              Forgot your password?
            </a>
          </div> --}}
        </div>

        <div class="mt-6">
          <span class="block w-full rounded-md shadow-sm">
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
              {{ __('auth.login') }}
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
          @if (Route::has('password.request'))
            <div>
              <span class="w-full inline-flex rounded-md shadow-sm">
                <a href="{{ route('password.request') }}" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md bg-white text-xs leading-5 font-medium text-gray-500 hover:text-gray-400 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition duration-150 ease-in-out">
                  {{ __('auth.password') }}
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
