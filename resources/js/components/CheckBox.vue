<template>
    <label class="flex items-center" @click="toggle">
        <span
            class="w-6 h-6 border-2 border-blue rounded cursor-pointer items-center justify-center flex"
            >
            <svg-icon
                name="icon-check"
                class="w-8 h-8 -mx-2 -my-2 primary-transparent"
                :class="isChecked ? 'secondary-blue' : 'secondary-transparent'"
                >
            </svg-icon>
        </span>
        <span class="ml-2" v-text="label"></span>
        <input type="checkbox" class="hidden" :name="name" v-model="isChecked" @change="toggle">
    </label>
</template>

<script>
import SvgIcon from 'vue-svgicon';

export default {
    components: {
        SvgIcon,
    },
    props: ['value', 'label', 'name'],
    data () {
        return {
            isChecked: this.value,
        };
    },
    methods: {
        toggle () {
            this.$emit('toggle');
        },
    },
}
</script>
