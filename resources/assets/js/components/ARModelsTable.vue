<template>
    <div>
        <table class="ui celled table">
            <thead class="full-width">
                <tr>
                    <th class="ten wide">Title</th>
                    <th class="one center aligned">Downloads</th>
                    <th class="one center aligned">Comments</th>
                    <th class="one center aligned">Rating</th>
                    <th class="collapsing">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="models.length == 0">
                    <td colspan="4" class="center aligned">No AR models found!</td>
                </tr>
                <tr v-for="ar in models">
                    <td>
                        <a :href="`/ar-model/${ar.id}/`">{{ ar.title }}</a>
                    </td>
                    <td class="center aligned">{{ ar.downloads }}</td>
                    <td class="center aligned">{{ ar.totalComments }}</td>
                    <td class="center aligned">
                        {{ ar.averageRating | formatNumber }}
                    </td>
                    <td>
                        <div class="ui tiny basic icon buttons">
                            <a class="ui button" :href="`/ar-model/${ar.id}/edit`" data-tooltip="Edit AR Model" data-position="top center" data-inverted>
                                <i class="pencil icon"></i>
                            </a>
                            <a class="ui button" data-tooltip="Delete AR Model" data-position="top center" data-inverted @click="deleteModel(ar.id)">
                                <i class="trash icon"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <th colspan="5">
                    <a class="ui right floated small primary labeled icon button" :href="`/${_type}/${_id}/ar-models/create`">
                        <i class="plus icon"></i> Add AR Model
                    </a>
                </th>
            </tfoot>
        </table>
        <confirm-dialog
            id="modelDelete"
            icon="trash"
            title="Delete AR model?"
            body="Are you sure you want to delete this AR model? All questions, answers and comments will be deleted as well. This action cannot be undone!"
        ></confirm-dialog>
    </div>
</template>

<script type="text/javascript">
import ConfirmDialog from './ConfirmDialog';
import numeral from 'numeral';

export default {
    name: 'ARModelsTable',
    props: ['_type', '_id', '_models'],
    components: { ConfirmDialog },
    data() {
        return {
            models: [...this._models],
        };
    },
    methods: {
        deleteModel(id) {
            $('#modelDelete').modal({
                closable: false,
                onApprove: () => {
                    const model = this.models.find((o) => o.id == id);
                    const idx = this.models.indexOf(model);
                    if (~idx) {
                        axios.delete(`/ar-model/${id}`).then(res => {
                            if (res.status === 200) {
                                this.models.splice(idx, 1);
                            }
                        });
                    }
                },
            }).modal('show');
        }
    },
    filters: {
        formatNumber(value) {
            return numeral(value).format('0.00');
        }
    },
};
</script>