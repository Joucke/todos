<template>
    <div class="">
        <p class="cursor-pointer" v-if="title" v-html="title" @click="toggle"></p>
        <svg-icon :name="`icon-${icon}`" v-if="icon" class="w-8 h-8 primary-white secondary-white cursor-pointer" @click="toggle"></svg-icon>
        <div class="absolute container mt-16 pin-t" v-if="show">
            <div :class="menu" class="absolute">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
import SvgIcon from 'vue-svgicon';
import '../icons';

export default {
    components: {
        SvgIcon,
    },
    props: ['title', 'menuClasses', 'icon'],
    data () {
        return {
            show: false,
        };
    },
    methods: {
        toggle () {
            this.show = !this.show;
        }
    },
    computed: {
        menu () {
            let classes = this.menuClasses.split(' ');
            if (! this.show) {
                classes.push('hidden');
            }
            return classes;
        }
    }
}
</script>
