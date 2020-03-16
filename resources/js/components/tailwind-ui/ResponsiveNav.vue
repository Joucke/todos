<template>
  <div>
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6">
      <nav class="relative flex items-center justify-between sm:h-10 md:justify-center">
        <div class="flex items-center flex-1 md:absolute md:inset-y-0 md:left-0">
          <div class="flex items-center justify-between w-full md:w-auto">
            <a href="/">
              <svg class="h-10 w-auto" fill="none" viewBox="0 0 60 24">
                <path stroke="currentColor" class="text-gray-900" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 14l2 2 4-4M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H9zm0 0a2 2 0 002 2h2a2 2 0 002-2H9zm0 0a2 2 0 012-2h2a2 2 0 012 2H9z"/>
                <path stroke="currentColor" class="text-indigo-600" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" d="M28.913 11.662h-2.285V18h-.933v-6.338h-2.28v-.771h5.498v.771zm6.738 3.013c0 .696-.117 1.305-.351 1.826-.235.518-.567.913-.996 1.186-.43.274-.931.41-1.504.41a2.73 2.73 0 01-1.49-.41 2.761 2.761 0 01-1.01-1.176c-.238-.511-.36-1.102-.366-1.773v-.512c0-.684.118-1.288.356-1.812s.573-.924 1.006-1.201c.436-.28.934-.42 1.494-.42.57 0 1.071.138 1.504.415.436.273.771.672 1.006 1.196.234.521.351 1.128.351 1.822v.449zm-.932-.46c0-.842-.17-1.488-.508-1.938-.339-.452-.812-.678-1.421-.678-.592 0-1.06.226-1.401.678-.339.45-.513 1.075-.523 1.875v.523c0 .817.171 1.46.513 1.928.345.466.819.699 1.42.699.606 0 1.075-.22 1.407-.66.332-.442.503-1.075.513-1.899v-.527zM37.409 18v-7.11h2.007c.619 0 1.165.137 1.64.41.476.274.842.663 1.1 1.168.26.504.392 1.084.395 1.738v.454c0 .67-.13 1.258-.39 1.763a2.713 2.713 0 01-1.11 1.162c-.478.27-1.036.409-1.674.415h-1.968zm.938-6.338v5.571h.986c.723 0 1.284-.224 1.685-.673.403-.45.605-1.09.605-1.92v-.414c0-.808-.19-1.434-.571-1.88-.378-.45-.915-.677-1.612-.684h-1.093zm11.445 3.013c0 .696-.117 1.305-.352 1.826-.234.518-.566.913-.996 1.186-.43.274-.93.41-1.504.41a2.73 2.73 0 01-1.489-.41 2.76 2.76 0 01-1.01-1.176c-.238-.511-.36-1.102-.367-1.773v-.512c0-.684.119-1.288.357-1.812.237-.524.573-.924 1.006-1.201.436-.28.934-.42 1.494-.42.57 0 1.07.138 1.504.415.436.273.771.672 1.005 1.196.235.521.352 1.128.352 1.822v.449zm-.933-.46c0-.842-.169-1.488-.507-1.938-.339-.452-.813-.678-1.421-.678-.593 0-1.06.226-1.402.678-.338.45-.512 1.075-.522 1.875v.523c0 .817.17 1.46.513 1.928.345.466.818.699 1.42.699.606 0 1.075-.22 1.407-.66.332-.442.503-1.075.512-1.899v-.527z"/>
                <path stroke="currentColor" class="text-indigo-500" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" d="M53.92 14.831c-.804-.231-1.39-.514-1.758-.85a1.639 1.639 0 01-.547-1.25c0-.56.223-1.022.67-1.386.448-.368 1.031-.552 1.747-.552.489 0 .923.094 1.304.283.384.189.68.45.889.781.211.333.317.695.317 1.09H55.6c0-.43-.137-.767-.41-1.011-.274-.248-.66-.371-1.158-.371-.462 0-.823.102-1.084.307-.257.202-.386.483-.386.845 0 .29.123.535.367.737.247.199.665.381 1.255.547.592.166 1.054.35 1.386.552.336.198.583.431.742.698.163.267.245.581.245.942 0 .576-.225 1.039-.674 1.387-.45.345-1.05.518-1.802.518-.488 0-.944-.093-1.367-.279-.423-.188-.75-.446-.982-.771a1.887 1.887 0 01-.341-1.108h.942c0 .43.158.77.474 1.02.319.247.743.371 1.274.371.495 0 .874-.1 1.138-.303a.984.984 0 00.395-.825c0-.348-.122-.617-.366-.805-.244-.193-.687-.381-1.328-.567z"/>
              </svg>
            </a>
            <div class="-mr-2 flex items-center md:hidden">
              <button @click="open = true" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>
        <div class="hidden md:block">
          <a v-for="(link, index) in links" :href="link.url" :class="{'ml-10': index > 0}" class="font-medium text-gray-500 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition duration-150 ease-in-out">{{ link.title }}</a>
        </div>
        <div v-if="canLogin" class="hidden md:absolute md:flex md:items-center md:justify-end md:inset-y-0 md:right-0">
          <span v-if="isLoggedIn" class="inline-flex rounded-md shadow">
            <a :href="auth.url" class="inline-flex items-center px-4 py-2 border border-transparent text-base leading-6 font-medium rounded-md text-indigo-600 bg-white hover:text-indigo-500 focus:outline-none focus:shadow-outline-blue active:bg-gray-50 active:text-indigo-700 transition duration-150 ease-in-out">
              {{ auth.title }}
            </a>
          </span>
          <span v-if="! isLoggedIn" class="inline-flex rounded-md shadow">
            <a :href="login.url" class="inline-flex items-center px-4 py-2 border border-transparent text-base leading-6 font-medium rounded-md text-indigo-600 bg-white hover:text-indigo-500 focus:outline-none focus:shadow-outline-blue active:bg-gray-50 active:text-indigo-700 transition duration-150 ease-in-out">
              {{ login.title }}
            </a>
          </span>
        </div>
      </nav>
    </div>

    <div v-show="open" style="display: none;" class="absolute top-0 inset-x-0 p-2 md:hidden">
      <div class="rounded-lg shadow-md transition transform origin-top-right" v-show="open" x-transition:enter="duration-150 ease-out" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="duration-100 ease-in" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="rounded-lg bg-white shadow-xs overflow-hidden">
          <div class="px-5 pt-4 flex items-center justify-between">
            <div>
              <svg class="h-10 w-auto" fill="none" viewBox="0 0 60 24">
                <path stroke="currentColor" class="text-gray-900" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 14l2 2 4-4M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H9zm0 0a2 2 0 002 2h2a2 2 0 002-2H9zm0 0a2 2 0 012-2h2a2 2 0 012 2H9z"/>
                <path stroke="currentColor" class="text-indigo-600" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" d="M28.913 11.662h-2.285V18h-.933v-6.338h-2.28v-.771h5.498v.771zm6.738 3.013c0 .696-.117 1.305-.351 1.826-.235.518-.567.913-.996 1.186-.43.274-.931.41-1.504.41a2.73 2.73 0 01-1.49-.41 2.761 2.761 0 01-1.01-1.176c-.238-.511-.36-1.102-.366-1.773v-.512c0-.684.118-1.288.356-1.812s.573-.924 1.006-1.201c.436-.28.934-.42 1.494-.42.57 0 1.071.138 1.504.415.436.273.771.672 1.006 1.196.234.521.351 1.128.351 1.822v.449zm-.932-.46c0-.842-.17-1.488-.508-1.938-.339-.452-.812-.678-1.421-.678-.592 0-1.06.226-1.401.678-.339.45-.513 1.075-.523 1.875v.523c0 .817.171 1.46.513 1.928.345.466.819.699 1.42.699.606 0 1.075-.22 1.407-.66.332-.442.503-1.075.513-1.899v-.527zM37.409 18v-7.11h2.007c.619 0 1.165.137 1.64.41.476.274.842.663 1.1 1.168.26.504.392 1.084.395 1.738v.454c0 .67-.13 1.258-.39 1.763a2.713 2.713 0 01-1.11 1.162c-.478.27-1.036.409-1.674.415h-1.968zm.938-6.338v5.571h.986c.723 0 1.284-.224 1.685-.673.403-.45.605-1.09.605-1.92v-.414c0-.808-.19-1.434-.571-1.88-.378-.45-.915-.677-1.612-.684h-1.093zm11.445 3.013c0 .696-.117 1.305-.352 1.826-.234.518-.566.913-.996 1.186-.43.274-.93.41-1.504.41a2.73 2.73 0 01-1.489-.41 2.76 2.76 0 01-1.01-1.176c-.238-.511-.36-1.102-.367-1.773v-.512c0-.684.119-1.288.357-1.812.237-.524.573-.924 1.006-1.201.436-.28.934-.42 1.494-.42.57 0 1.07.138 1.504.415.436.273.771.672 1.005 1.196.235.521.352 1.128.352 1.822v.449zm-.933-.46c0-.842-.169-1.488-.507-1.938-.339-.452-.813-.678-1.421-.678-.593 0-1.06.226-1.402.678-.338.45-.512 1.075-.522 1.875v.523c0 .817.17 1.46.513 1.928.345.466.818.699 1.42.699.606 0 1.075-.22 1.407-.66.332-.442.503-1.075.512-1.899v-.527z"/>
                <path stroke="currentColor" class="text-indigo-500" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" d="M53.92 14.831c-.804-.231-1.39-.514-1.758-.85a1.639 1.639 0 01-.547-1.25c0-.56.223-1.022.67-1.386.448-.368 1.031-.552 1.747-.552.489 0 .923.094 1.304.283.384.189.68.45.889.781.211.333.317.695.317 1.09H55.6c0-.43-.137-.767-.41-1.011-.274-.248-.66-.371-1.158-.371-.462 0-.823.102-1.084.307-.257.202-.386.483-.386.845 0 .29.123.535.367.737.247.199.665.381 1.255.547.592.166 1.054.35 1.386.552.336.198.583.431.742.698.163.267.245.581.245.942 0 .576-.225 1.039-.674 1.387-.45.345-1.05.518-1.802.518-.488 0-.944-.093-1.367-.279-.423-.188-.75-.446-.982-.771a1.887 1.887 0 01-.341-1.108h.942c0 .43.158.77.474 1.02.319.247.743.371 1.274.371.495 0 .874-.1 1.138-.303a.984.984 0 00.395-.825c0-.348-.122-.617-.366-.805-.244-.193-.687-.381-1.328-.567z"/>
              </svg>
            </div>
            <div class="-mr-2">
              <button @click="open = false" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>
          <div class="px-2 pt-2 pb-3">
            <a v-for="(link, index) in links" :href="link.url" :class="{'mt-1': index > 0}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-50 transition duration-150 ease-in-out">{{ link.title }}</a>
          </div>
          <div v-if="canLogin">
            <a v-if="isLoggedIn" :href="auth.url" class="block w-full px-5 py-3 text-center font-medium text-indigo-600 bg-gray-50 hover:bg-gray-100 hover:text-indigo-700 focus:outline-none focus:bg-gray-100 focus:text-indigo-700 transition duration-150 ease-in-out">
              {{ auth.title }}
            </a>
            <a v-if="! isLoggedIn" :href="login.url" class="block w-full px-5 py-3 text-center font-medium text-indigo-600 bg-gray-50 hover:bg-gray-100 hover:text-indigo-700 focus:outline-none focus:bg-gray-100 focus:text-indigo-700 transition duration-150 ease-in-out">
              {{ login.title }}
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['links', 'login', 'auth'],
  data () {
    return {
      open: false,
    };
  },
  computed: {
    canLogin() {
      return !! this.login;
    },
    isLoggedIn() {
      return !! this.auth;
    },
  },
  methods: {
    //
  },
}
</script>
