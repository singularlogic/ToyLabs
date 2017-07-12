<template>
    <form class="ui form" method="POST" action="/product/create">
        <input type="hidden" name="_token" :value="$parent.crsf" />

        <h4 class="ui dividing header">General</h4>
        <div class="field">
            <label>Title</label>
            <input type="text" name="title" v-model="product.title" placeholder="Enter a descriptive title for your product" required />
        </div>
        <div class="field">
            <label>Description</label>
            <textarea v-model="product.description" name="description" required></textarea>
        </div>
        <div class="fields">
            <div class="twelve wide field">
                <label>Category</label>
                <select class="ui search dropdown" name="category_id" v-model="product.category_id">
                    <option value="">Select Product Category</option>
                    <option v-for="c of _categories" :value="c.id">{{ c.title }}</option>
                </select>
            </div>
            <div class="four wide field" required>
                <label>Ages</label>
                <input type="text" v-model="product.ages" name="ages" placeholder="Target ages" required />
            </div>
        </div>

        <h4 class="ui dividing header">Legal</h4>
        <div class="inline fields">
            <label for="owner">Who will be the legal owner of this product?</label>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="owner" id="me" :value="_user" v-model="product.owner" />
                    <label for="me">Myself</label>
                </div>
            </div>
            <div class="field" :class="{ disabled: _organizations.length === 0 }">
                <div class="ui radio checkbox">
                    <input type="radio" name="owner" id="org" :value="organization" v-model="product.owner" />
                    <label for="org">
                        My Organization
                        <span v-if="_organizations.length > 0">(<em>{{ _organizations[0].name }}</em>)</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="inline fields">
            <label for="public">
                <i class="red warning icon"></i>
                Do you want to make your product public (can be seen by everyone)?
            </label>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="is_public" id="false" value="false" v-model="product.is_public" />
                    <label for="false">No</label>
                </div>
            </div>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="is_public" id="true" value="true" v-model="product.is_public" />
                    <label for="true">Yes</label>
                </div>
            </div>
        </div>

        <input type="hidden" name="owner_id" :value="owner_id" />
        <input type="hidden" name="owner_type" :value="owner_type" />

        <button type="submit" class="ui orange submit right floated labeled icon button" ref="submitButton">
            <i class="edit icon"></i> {{ submitText }}
        </button>
        <a href="/dashboard" class="ui default right floated button">Cancel</a>
    </form>
</template>

<script>
export default {
    props: ['_product', '_user', '_organizations', '_categories'],
    data() {
        return {
            submitText: this._product.id ? 'Update' : 'Create',
            product: this._product,
            organization: this._organizations.length > 0 ? this._organizations[0] : null,
        };
    },
    computed: {
        owner_id() {
            return this.product.owner.id;
        },
        owner_type() {
            return this.product.owner === this.organization ? 'App\\Organization' : 'App\\User';
        }
    }
}
</script>