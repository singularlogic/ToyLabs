<template>
    <form class="ui form" method="POST" ref="productForm">
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
        <div class="field">
            <label>Category</label>
            <select class="ui search dropdown" name="category_id" v-model="product.category_id">
                <option value="">Select Product Category</option>
                <option v-for="c of _categories" :value="c.id">
                    <strong>{{ c.title }}</strong> <span v-if="c.description">(<em>{{ c.description }}</em>)</span>
                </option>
            </select>
        </div>

        <div class="fields">
            <div class="three wide field" required>
                <label>Suitable for Ages From</label>
                <select class="ui search dropdown" name="min_age" v-model="product.min_age">
                    <option value="">Select Minimum Age</option>
                    <option v-for="a of _ages" :value="a.value">{{ a.name }}</option>
                </select>
            </div>
            <div class="three wide field" required>
                <label>To</label>
                <select class="ui search dropdown" name="max_age" v-model="product.max_age">
                    <option value="">Select Maximum Age</option>
                    <option v-for="a of _ages" :value="a.value">{{ a.name }}</option>
                </select>
            </div>
            <div class="ui ten wide field">
                <div class="ui info message field" style="padding: 10px 8px; margin-top: 23px;">
                    <i class="info icon"></i>
                    Tentative ages. You can change the age group later, if needed.
                </div>
            </div>
        </div>

        <div v-if="editLegal">
            <h4 class="ui dividing header">Legal</h4>
            <div class="inline fields">
                <label for="owner">Who will be the legal owner of this product?</label>
                <div class="field">
                    <div class="ui radio checkbox">
                        <input type="radio" name="owner" id="me" value="me" v-model="owner" />
                        <label for="me">Myself</label>
                    </div>
                </div>
                <div class="field" :class="{ disabled: _organizations.length === 0 }">
                    <div class="ui radio checkbox">
                        <input type="radio" name="owner" id="org" value="org" v-model="owner" />
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
                        <input type="radio" name="is_public" id="false" value="0" v-model="product.is_public" />
                        <label for="false">No</label>
                    </div>
                </div>
                <div class="field">
                    <div class="ui radio checkbox">
                        <input type="radio" name="is_public" id="true" value="1" v-model="product.is_public" />
                        <label for="true">Yes</label>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="ui dividing header">
            Images
            <div class="sub header">For informational images only. If your product is public, these are made public as well</div>
        </h3>

        <div class="ui relaxed horizontal divided list" v-if="images" style="margin-bottom: 10px;">
            <div class="item" v-for="image of images">
                <img :src="`/file/${image.id}`" class="ui mini image"  />
                <div class="content">
                    <span class="header">
                        {{ image.name }}
                        <a href="javascript:void(0)" @click="deleteMedia(image.id)"><i class="grey delete icon"></i></a>
                    </span>
                </div>
            </div>
        </div>

        <dropzone id="images"
            url="/file/upload"
            :useFontAwesome="true"
            :showRemoveLink="true"
            paramName="image"
            acceptedFileTypes="image/*"
            v-on:vdropzone-success="imageAdded"
            v-on:vdropzone-removed-file="imageRemoved"
        >
            <input type="hidden" name="_token" :value="$parent.crsf" />
        </dropzone>

        <h3 class="ui dividing header">
            Files
            <div class="sub header">Use this field to upload designs and documents for collaboration with your partners. These remain private, even if the design is made public</div>
        </h3>

        <div class="ui relaxed horizontal divided list" v-if="files" style="margin-bottom: 10px;">
            <div class="item" v-for="file of files">
                <div class="content">
                    <span class="header">
                        {{ file.name }}
                        <a href="javascript:void(0)" @click="deleteMedia(file.id)"><i class="grey delete icon"></i></a>
                    </span>
                </div>
            </div>
        </div>

        <dropzone id="files"
            url="/file/upload"
            :useFontAwesome="true"
            :showRemoveLink="true"
            :maxFileSizeInMB="64"
            paramName="file"
            v-on:vdropzone-success="fileAdded"
            v-on:vdropzone-removed-file="fileRemoved"
        >
            <input type="hidden" name="_token" :value="$parent.crsf" />
        </dropzone>

        <div class="ui divider"></div>

        <input type="hidden" name="owner_id" :value="owner_id" />
        <input type="hidden" name="owner_type" :value="owner_type" />
        <input type="hidden" name="status" ref="status" />
        <input type="hidden" name="images" :value="imagesArray" />
        <input type="hidden" name="files" :value="filesArray" />


        <button type="button" class="ui blue left floated button" v-if="product.status == 'concept' && _product.id" @click="nextStep">Continue to Market Analysis</button>
        <button type="submit" class="ui orange submit right floated labeled icon button" ref="submitButton">
            <i class="save icon"></i> {{ submitText }}
        </button>

        <confirm-dialog
            id="imageDelete"
            icon="trash"
            title="Delete file?"
            body="Are you sure you want to delete this file? This action cannot be undone!"
        ></confirm-dialog>
    </form>
</template>

<script>
import ConfirmDialog from './ConfirmDialog.vue';
import Dropzone from 'vue2-dropzone';

export default {
    components: { Dropzone, ConfirmDialog },
    props: ['_product', '_user', '_organizations', '_categories', '_ages'],
    data() {
        return {
            submitText: this._product.id ? 'Update' : 'Create',
            product: this._product,
            organization: this._organizations.length > 0 ? this._organizations[0] : null,
            owner: this._product.owner_type === 'App\\User' ? 'me' : 'org',
            editLegal: this._product.id ? false : true,
            uploadedImages: [],
            uploadedFiles: [],
            status: null,
            editable: false,
        };
    },
    mounted() {
        if (this.owner === 'me') {
            this.editLegal = true;
        } else if (this.owner === 'org' && this.organization.owner_id === this._user.id) {
            this.editLegal = true;
        } else {
            this.editLegal = false;
        }
    },
    computed: {
        owner_id() {
            return this.owner === 'me' ? this._user.id : this.organization.id;
        },
        owner_type() {
            return this.owner === 'me' ? 'App\\User' : 'App\\Organization';
        },
        images() {
            return this.product.media ? this.product.media.filter(obj => obj.collection_name === 'images') : [];
        },
        files() {
            return this.product.media ? this.product.media.filter(obj => obj.collection_name === 'files') : [];
        },
        imagesArray() {
            return JSON.stringify(this.uploadedImages);
        },
        filesArray() {
            return JSON.stringify(this.uploadedFiles);
        },
    },
    methods: {
        fileAdded(file) {
            this.uploadedFiles.push({
                name: file.name,
                path: JSON.parse(file.xhr.response).path,
            });
        },
        fileRemoved(file, error, xhr) {
            const path = JSON.parse(file.xhr.response).path;
            const f = this.uploadedFiles.find((o) => o.path === path);
            const idx = this.uploadedFiles.indexOf(f);
            if (~idx) {
                axios.delete('/file/delete', {
                    data: { path },
                }).then((response) => {
                    this.uploadedFiles.splice(idx, 1);
                });
            }
        },
        imageAdded(file) {
            this.uploadedImages.push({
                name: file.name,
                path: JSON.parse(file.xhr.response).path,
            });
        },
        imageRemoved(file, error, xhr) {
            const path = JSON.parse(file.xhr.response).path;
            const f = this.uploadedImages.find((o) => o.path === path);
            const idx = this.uploadedImages.indexOf(f);
            if (~idx) {
                axios.delete('/file/delete', {
                    data: { path },
                }).then((response) => {
                    this.uploadedImages.splice(idx, 1);
                });
            }
        },
        deleteMedia(id) {
            $('#imageDelete').modal({
                closable: false,
                onApprove: () => {
                    const media = this.product.media.find((o) => o.id == id);
                    const idx = this.product.media.indexOf(media);
                    if (~idx) {
                        axios.delete('/attachment/remove', {
                            data: { id },
                        }).then((response) => {
                            if (response.status === 200) {
                                this.product.media.splice(idx, 1);
                            }
                        });
                    }
                },
            }).modal('show');
        },
        nextStep() {
            this.$refs.status.value = 'design';
            this.$refs.productForm.submit();
        },
    }
}
</script>

<style>
    .vue-dropzone {
        border: 1px dashed rgba(34,36,38,.15)!important;
        font-family: inherit!important;
    }

    .dropzone {
        padding: 10px!important;
    }
</style>