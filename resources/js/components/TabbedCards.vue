<template>
    <div class="tabs">
        <div class="card-padding-full">
            <div class="card">

                <div class="card-header bg-grey-lighter border-b">
                    <ul class="list-reset flex">
                        <li :class="{active: isSelected(tab)}"
                            v-for="tab in tabs"
                            :key="`tab_${tab.id}`">
                            <a class="nav blue-light"
                                :id="`tab_${tab.id}`"
                                href="#"
                                @click.prevent="selectTab(tab)"
                                v-text="tab.title"></a>
                        </li>
                    </ul>
                </div>

                <div class="card-body bg-white" v-for="tab in tabs" v-if="isSelected(tab)">
                    <div class="card-container" v-for="(dates, section) in cards[tab.id]">
                        <!-- TODO: use section to collapse dates -->
                        <collapse-section :section="section" class="w-full">
                            <task-card
                                v-for="task in dates"
                                :key="`task_${task.id}`"
                                :show-title="true"
                                :task-data="task"
                                :clickable="true"
                                >
                            </task-card>
                        </collapse-section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TaskCard from './TaskCard';

export default {
    components: {
        TaskCard,
    },
    props: ['tabs', 'cards'],
    data () {
        return {
            selectedTab: this.tabs[0].id,
        };
    },
    methods: {
        isSelected (tab) {
            return this.selectedTab == tab.id;
        },
        selectTab (tab) {
            this.selectedTab = tab.id;
        }
    },
}
</script>
