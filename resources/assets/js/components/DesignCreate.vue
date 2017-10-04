<template>
    <form class="ui form" method="POST">
        <input type="hidden" name="_token" :value="$parent.crsf" />

        <h4 class="ui dividing header">General</h4>
        <div class="field">
            <label>Title</label>
            <input type="text" name="title" v-model="design.title" placeholder="Enter a descriptive title for your design" required />
        </div>
        <div class="field">
            <label>Description</label>
            <textarea v-model="design.description" name="description" required></textarea>
        </div>

        <div class="inline fields">
            <label for="public">
                <i class="red warning icon"></i>
                Do you want to make your design public (can be seen by everyone)?
            </label>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="is_public" id="false" value="0" v-model="design.is_public" />
                    <label for="false">No</label>
                </div>
            </div>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="is_public" id="true" value="1" v-model="design.is_public" />
                    <label for="true">Yes</label>
                </div>
            </div>
        </div>

        <div class="ui divider"></div>

        <button type="submit" class="ui orange submit right floated labeled icon button" ref="submitButton">
            <i class="edit icon"></i> {{ submitText }}
        </button>
    </form>
</template>

<script>
export default {
    props: ['_design', '_product_id'],
    data() {
        return {
            submitText: this._design.id ? 'Update' : 'Create',
            design: this._design,
        };
    }
}
</script>