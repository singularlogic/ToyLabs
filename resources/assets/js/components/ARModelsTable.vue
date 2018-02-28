<template>
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
                <td class="center aligned">{{ ar.views }}</td>
                <td class="center aligned">{{ ar.comments }}</td>
                <td class="center aligned">
                    {{ ar.rating }} / 5.0
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
</template>

<script type="text/javascript">
export default {
    name: 'ARModelsTable',
    props: ['_type', '_id', 'models'],
    methods: {
        deleteModel(id) {
            console.log('NYI - deleteARModel ' + id);
        }
    }
};
</script>