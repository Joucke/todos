<script>
import { Sortable } from '@shopify/draggable';
import move from '../functions/move';

export default {
    props: {
        data: {
            required: true,
        },
        sortUrl: {
            required: true,
        },
        sortKey: {
            required: true,
        },
        itemClass: {
            default: 'sortable-list-item',
        },
        handleClass: {
            default: 'sortable-list-handle',
        },
    },
    data() {
        return {
            items: this.data,
        };
    },
    provide() {
        return {
            sortableListItemClass: this.itemClass,
            sortableListHandleClass: this.handleClass,
        }
    },
    methods: {
        patch() {
            let data = {};
            data[this.sortKey] = {};

            this.items.forEach((item, index) => {
                data[this.sortKey][item.id] = {sort_order: index + 1};
            });

            axios.patch(this.sortUrl, data)
                .then(({data}) => {
                    this.items = data.items;
                });
        },
    },
    mounted() {
        new Sortable(this.$el, {
            draggable: `.${this.itemClass}`,
            handle: `.${this.handleClass}`,
            mirror: {
                constrainDimensions: true,
            }
        }).on('sortable:stop', ({ oldIndex, newIndex }) => {
            this.items = move(this.items, oldIndex, newIndex);
            this.patch();
        });
    },
    render() {
        return this.$scopedSlots.default({
            items: this.items
        });
    }
}
</script>
