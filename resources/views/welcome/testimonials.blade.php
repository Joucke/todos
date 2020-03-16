<div class="py-12 bg-gray-50 overflow-hidden md:py-20 lg:py-24" id="testimonials">
  <div class="relative max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
    <svg class="absolute top-full right-full transform translate-x-1/3 -translate-y-1/4 lg:translate-x-1/2 xl:-translate-y-1/2" width="404" height="404" fill="none" viewBox="0 0 404 404">
      <defs>
        <pattern id="svg-pattern-squares-1" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
          <rect x="0" y="0" width="4" height="4" class="text-gray-200" fill="currentColor" />
        </pattern>
      </defs>
      <rect width="404" height="404" fill="url(#svg-pattern-squares-1)" />
    </svg>

    <div class="relative">
      @foreach ($testimonials as $testimonial)
        <blockquote class="mt-8">
          <div class="max-w-3xl mx-auto text-center text-2xl leading-9 font-medium text-gray-900">
            <p>
              &ldquo;{{ $testimonial->quote }}&rdquo;
            </p>
          </div>
          <footer class="mt-8">
            <div class="md:flex md:items-center md:justify-center">
              <div class="md:flex-shrink-0">
                @if ($testimonial->avatar)
                  <img class="mx-auto h-10 w-10 rounded-full" src="{{ $testimonial->avatar }}" alt="" />
                @else
                  <svg class="w-8 h-8 p-1 bg-indigo-200 rounded-full text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14C8.13401 14 5 17.134 5 21H19C19 17.134 15.866 14 12 14Z"/>
                  </svg>
                @endif
              </div>
              <div class="mt-3 text-center md:mt-0 md:ml-4 md:flex md:items-center">
                <div class="text-base leading-6 font-medium text-gray-900">{{ $testimonial->name }}</div>

                <svg class="hidden md:block mx-1 h-5 w-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M11 0h3L9 20H6l5-20z" />
                </svg>

                <div class="text-base leading-6 font-medium text-gray-500">{{ $testimonial->description }}</div>
              </div>
            </div>
          </footer>
        </blockquote>
      @endforeach
    </div>
  </div>
</div>
