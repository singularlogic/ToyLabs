<template>
        <table class="ui celled table">
            <thead class="full-width">
                <tr>
                    <th class="ten wide">Title</th>
                    <th class="one center aligned">Version</th>
                    <th class="two center aligned">Last Modified</th>
                    <th class="collapsing">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="designs.length == 0">
                    <td colspan="4" class="center aligned">No product designs found!</td>
                </tr>
                <tr v-for="d in designs">
                    <td>
                        <a :href="`/design/${d.id}/`">{{ d.title }}</a>
                    </td>
                    <td class="center aligned">{{ d.version }}</td>
                    <td class="center aligned"><timeago :since="d.updated_at" :max-time="86400 * 7" :format="formatDate" :auto-update="30"></timeago></td>
                    <td>
                        <div class="ui tiny basic icon buttons">
                            <a class="ui button" :href="`/design/${d.id}/edit`" data-tooltip="Edit Design" data-position="top center" data-inverted>
                                <i class="pencil icon"></i>
                            </a>
                            <button class="ui button" data-tooltip="Create new version" data-position="top center" data-inverted>
                                <i class="history icon"></i>
                            </button>
                            <button class="ui button" data-tooltip="Discussions" data-position="top center" data-inverted>
                                <i class="comments outline icon"></i>
                            </button>
                            <button class="ui button" data-tooltip="Partner Matching" data-position="top center" data-inverted>
                                <i class="handshake icon"></i>
                            </button>
                            <a :href="`/product/${_product_id}/prototypes/create/${d.id}`" class="ui button" data-tooltip="Create Prototype" data-position="top center" data-inverted>
                                <i class="cubes icon"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <th colspan="4">
                    <a class="ui right floated small primary labeled icon button" :href="`/product/${_product_id}/designs/create`">
                        <i class="marker icon"></i> Add Design
                    </a>
                </th>
            </tfoot>
        </table>
</template>

<script>
import moment from 'moment';

export default {
    props: ['_designs', '_product_id'],
    data() {
        return {
            designs: this._designs,
        };
    },
    methods: {
        formatDate(time) {
            return moment(new Date(time)).format('DD.MM.YYYY');
        },
    }
}
</script>