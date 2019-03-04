<template>
    <table>
        <tbody>
            <tr v-for="item, index in items" :key="item.id">
                <td>
                    <svg-icon
                        @click="up(index)"
                        name="icon-sort-ascending"
                        class="up cursor-pointer w-6 h-6 primary-black secondary-black"
                        v-if="index > 0"
                        >
                    </svg-icon>
                </td>
                <td v-for="column in columns" v-text="item[column]"></td>
                <td>
                    <svg-icon
                        @click="down(index)"
                        name="icon-sort-decending"
                        class="down cursor-pointer w-6 h-6 primary-black secondary-black"
                        v-if="index < items.length - 1"
                        >
                    </svg-icon>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
import SvgIcon from 'vue-svgicon';
import '../icons';

export default {
    components: {
        SvgIcon,
    },
    props: ['listData', 'columns', 'sortUrl', 'sortKey'],
    data () {
        return {
            items: this.listData,
        };
    },
    methods: {
        down (index) {
            let items = this.items;
            [items[index], items[index + 1]] = [items[index + 1], items[index]];
            let data = {};
            data[this.sortKey] = {};
            items.forEach((item, index) => {
                data[this.sortKey][item.id] = {sort_order: index + 1};
            });
            axios.patch(this.sortUrl, data)
                .then(({data}) => {
                    this.items = data.items;
                });
        },
        up (index) {
            let items = this.items;
            [items[index], items[index - 1]] = [items[index - 1], items[index]];
            let data = {};
            data[this.sortKey] = {};
            items.forEach((item, index) => {
                data[this.sortKey][item.id] = {sort_order: index + 1};
            });
            axios.patch(this.sortUrl, data)
                .then(({data}) => {
                    this.items = data.items;
                });
        },
    },
}
</script>
