<template>
    <div class="">
        <div :class="showTitle">
            <p class="cursor-pointer"
                v-if="title"
                v-html="title"
                @click="toggle"
                >
            </p>
            <svg-icon
                :name="`icon-${icon}`"
                v-if="icon"
                class="w-8 h-8 primary-white secondary-white cursor-pointer"
                @click="toggle"
                >
            </svg-icon>
        </div>
        <div :class="menuContainer">
            <div :class="menu">
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
    props: {
        title: String,
        menuClasses: String,
        menuContainer: String,
        icon: String,
        responsive: {
            type: Object,
            default: () => {
                return {
                    menu: {
                        sm: 'hidden',
                        md: 'hidden',
                        lg: 'flex',
                        xl: 'flex',
                    },
                    title: {
                        sm: 'flex',
                        md: 'flex',
                        lg: 'hidden',
                        xl: 'hidden',
                    }
                };
            },
        },
    },
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
                for (let screen in this.responsive.menu) {
                    classes.push(`${screen}:${this.responsive.menu[screen]}`);
                }
            }
            return classes;
        },
        showTitle () {
            let classes = ['flex items-center'];
            for (let screen in this.responsive.title) {
                classes.push(`${screen}:${this.responsive.title[screen]}`);
            }
            return classes;
        }
    },
}
</script>
