<template>
    <div>
        <table class="ui celled table">
            <thead class="full-width">
                <tr>
                    <th class="twelve wide">Title</th>
                    <th class="two center aligned">Last Modified</th>
                    <th class="collapsing">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="prototypes.length == 0 && archived.length == 0">
                    <td colspan="3" class="center aligned">No product prototypes found!</td>
                </tr>
                <tr v-for="p in prototypes">
                    <td>
                        <a :href="`/prototype/${p.id}/`">{{ p.title }}</a>
                    </td>
                    <td class="center aligned"><timeago :since="p.updated_at" :max-time="86400 * 7" :format="formatDate" :auto-update="30"></timeago></td>
                    <td>
                        <div class="ui tiny basic icon buttons">
                            <a class="ui button" :href="`/prototype/${p.id}/edit`" data-tooltip="Edit Prototype" data-position="top center" data-inverted>
                                <i class="pencil icon"></i>
                            </a>
                            <a class="ui button" :href="`/prototype/${p.id}/feedback`" data-tooltip="Feedback" data-position="top center" data-inverted>
                                <i class="comments outline icon"></i>
                            </a>
                            <a class="ui button" data-tooltip="Collaborations" :href="`/prototype/${p.id}/collaborate`" data-position="top center" data-inverted>
                                <i class="handshake icon"></i>
                            </a>
                            <button class="ui button" data-tooltip="Archive prototype" data-position="top center" data-inverted @click="archivePrototype(p.id)">
                                <i class="archive icon"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-for="p in archived">
                    <td>
                        <i class="archive icon" title="Archived"></i>
                        <a :href="`/prototype/${p.id}/`">{{ p.title }}</a>
                    </td>
                    <td class="center aligned">
                        <timeago :since="p.updated_at" :max-time="86400 * 7" :format="formatDate" :auto-update="30"></timeago>
                    </td>
                    <td>
                        <div class="ui tiny basic icon buttons">
                            <a class="ui button" :href="`/prototype/${p.id}/feedback`" data-tooltip="Feedback" data-position="top center" data-inverted>
                                <i class="comments outline icon"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <th colspan="3">
                    <a class="ui right floated small primary labeled icon button" :href="`/product/${_product_id}/prototypes/create`">
                        <i class="marker icon"></i> Add Prototype
                    </a>
                </th>
            </tfoot>
        </table>
        <confirm-dialog
            id="archive"
            icon="warning"
            title="Archive Prototype?"
            body="Are you sure you want to archive this prototype?"
        ></confirm-dialog>
    </div>
</template>

<script>
import moment from 'moment';
import ConfirmDialog from './ConfirmDialog.vue';

export default {
    props: ['_prototypes', '_product_id'],
    components: { ConfirmDialog },
    data() {
        return {
            prototypes: this._prototypes.filter(p => !p.is_archived),
            archived: this._prototypes.filter(p => p.is_archived),
        };
    },
    methods: {
        formatDate(time) {
            return moment(new Date(time)).format('DD.MM.YYYY');
        },
        archivePrototype(id) {
            $('#archive').modal({
                closable: false,
                onApprove: () => {
                    axios.put(`/prototype/${id}/archive`).then(res => {
                        if (res.status === 200) {
                            location.reload();
                        }
                    });
                },
            }).modal('show');
        },    }
}
</script>