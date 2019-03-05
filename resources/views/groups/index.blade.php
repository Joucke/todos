@extends('layouts.app')

@section('content')
<header class="flex justify-between items-center">
    <h1 class="page-header">{{ __('groups.sort') }}</h1>
</header>

<div class="py-4">
    <sort-table
        :list-data="{{ $groups }}"
        sort-url="{{ route('groups.sort', ['user' => auth()->user()]) }}"
        sort-key="user_group_order"
        >
        <template slot="row" slot-scope="{index, item, items, up, down}">
            <td>
                <svg-icon
                    @click="up(index)"
                    name="icon-sort-ascending"
                    class="up cursor-pointer w-6 h-6 primary-black secondary-black"
                    v-if="index > 0"
                    >
                </svg-icon>
            </td>
            <td v-text="item.title"></td>
            <td>
                <svg-icon
                    @click="down(index)"
                    name="icon-sort-decending"
                    class="down cursor-pointer w-6 h-6 primary-black secondary-black"
                    v-if="index < items.length - 1"
                    >
                </svg-icon>
            </td>
        </template>
    </sort-table>
</div>

@endsection
