<template>
    <div>
        <button @click="isOpen = !isOpen" class="flex items-center ml-4">
            <span v-text="t(`tasks.sections.${title}`)"></span>
            <span>
                <svg class="w-6 h-6" :style="styles" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" class="fill-current text-transparent"/>
                    <path class="fill-current text-black" d="M10.3 8.7a1 1 0 0 1 1.4-1.4l4 4a1 1 0 0 1 0 1.4l-4 4a1 1 0 0 1-1.4-1.4l3.29-3.3-3.3-3.3z"/>
                </svg>
            </span>
        </button>
        <div class="flex flex-wrap" v-show="isOpen">
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    props: ['section'],
    data () {
        return {
            isOpen: true,
            title: this.section.split('-')[1]
        };
    },
    computed: {
        styles () {
            return this.isOpen ? 'transform: rotate(90deg)' : '';
        }
    },
    methods: {
        t (key) {
            return this.$options.filters.trans(key);
        },
        //
    },
}
</script>
