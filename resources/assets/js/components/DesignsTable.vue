<template>
    <div>
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
                <tr v-if="designs.length == 0 && archived.length == 0">
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
                            <a class="ui button" :href="`/design/${d.id}/ar-models`" data-tooltip="AR Models" data-position="top center" data-inverted>
                                <i class="eye icon"></i>
                            </a>
                            <button class="ui button" data-tooltip="Create new version" data-position="top center" data-inverted @click="createRevision(d.id)">
                                <i class="history icon"></i>
                            </button>
                            <a class="ui button" :href="`/design/${d.id}/feedback`" data-tooltip="Feedback" data-position="top center" data-inverted>
                                <i class="comments outline icon"></i>
                            </a>
                            <a class="ui button" :href="`/design/${d.id}/collaborate`" data-tooltip="Collaborations" data-position="top center" data-inverted>
                                <i class="handshake icon"></i>
                            </a>
                            <a :href="`/product/${_product_id}/prototypes/create/${d.id}`" class="ui button" data-tooltip="Create Prototype" data-position="top center" data-inverted>
                                <i class="cubes icon"></i>
                            </a>
                            <button class="ui button" data-tooltip="Archive design" data-position="top center" data-inverted @click="archiveDesign(d.id)">
                                <i class="archive icon"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-for="d in archived">
                    <td>
                        <i class="archive icon" title="Archived"></i>
                        <a :href="`/design/${d.id}/`">{{ d.title }}</a>
                    </td>
                    <td class="center aligned">{{ d.version }}</td>
                    <td class="center aligned"><timeago :since="d.updated_at" :max-time="86400 * 7" :format="formatDate" :auto-update="30"></timeago></td>
                    <td>
                        <div class="ui tiny basic icon buttons">
                            <a class="ui button" :href="`/design/${d.id}/ar-models`" data-tooltip="AR Models" data-position="top center" data-inverted>
                                <i class="eye icon"></i>
                            </a>
                            <a class="ui button" :href="`/design/${d.id}/feedback`" data-tooltip="Feedback" data-position="top center" data-inverted>
                                <i class="comments outline icon"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <th colspan="4">
                    <a class="ui right floated small primary labeled icon button" :href="`/product/${_product_id}/designs/create`">
                        <i class="plus icon"></i> Add Design
                    </a>
                </th>
            </tfoot>
        </table>

        <table class="ui celled table">

        </table>

        <confirm-dialog
            id="createRevision"
            icon="warning"
            :title="messageTitle"
            :body="messageBody"
        ></confirm-dialog>
    </div>
</template>

<script>
import moment from 'moment';
import ConfirmDialog from './ConfirmDialog.vue';

export default {
    props: ['_designs', '_product_id'],
    components: { ConfirmDialog },
    data() {
        return {
            designs: this._designs.filter(d => !d.is_archived),
            archived: this._designs.filter(d => d.is_archived),
            messageTitle: '',
            messageBody: '',
        };
    },
    methods: {
        formatDate(time) {
            return moment(new Date(time)).format('DD.MM.YYYY');
        },
        createRevision(id) {
            this.messageTitle = 'Create new version?';
            this.messageBody = 'Are you sure you want to create a revision of this design? This version will be archived, and in case you need collaborators, you have to add them again to the new version.';
            $('#createRevision').modal({
                closable: false,
                onApprove: () => {
                    axios.put(`/design/${id}/revision`).then(res => {
                        if (res.status === 200) {
                            location.reload();
                        }
                    });
                },
            }).modal('show');
        },
        archiveDesign(id) {
            this.messageTitle = 'Archive Design?';
            this.messageBody = 'Are you sure you want to archive this design?';
            $('#createRevision').modal({
                closable: false,
                onApprove: () => {
                    axios.put(`/design/${id}/archive`).then(res => {
                        if (res.status === 200) {
                            location.reload();
                        }
                    });
                },
            }).modal('show');
        },
    }
}
</script>
