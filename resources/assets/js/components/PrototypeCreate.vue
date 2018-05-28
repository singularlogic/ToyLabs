<template>
    <form class="ui form" method="POST">
        <input type="hidden" name="_token" :value="$parent.crsf" />

        <h4 class="ui dividing header">General</h4>
        <div class="field">
            <label>Title</label>
            <input type="text" name="title" v-model="prototype.title" placeholder="Enter a descriptive title for your prototype" required />
        </div>
        <div class="field">
            <label>Description</label>
            <textarea v-model="prototype.description" name="description" required></textarea>
        </div>

        <div class="inline fields">
            <label for="public">
                <i class="red warning icon"></i>
                Do you want to make your prototype public (can be seen by everyone)?
            </label>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="is_public" id="false" value="0" v-model="prototype.is_public" />
                    <label for="false">No</label>
                </div>
            </div>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="is_public" id="true" value="1" v-model="prototype.is_public" />
                    <label for="true">Yes</label>
                </div>
            </div>
        </div>

        <h3 class="ui dividing header">
            Images
            <div class="sub header">For informational images only. If your prototype is public, these are made public as well</div>
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
            <div class="sub header">Use this field to upload designs and documents for collaboration with your partners. These remain private, even if the prototype is made public</div>
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

        <input type="hidden" name="design_id" :value="_design_id" />

        <button type="submit" class="ui orange submit right floated labeled icon button" ref="submitButton">
            <i class="edit icon"></i> {{ submitText }}
        </button>

        <confirm-dialog
            id="imageDelete"
            icon="trash"
            title="Delete file?"
            body="Are you sure you want to delete this file? This action cannot be undone!"
        ></confirm-dialog>

        <input type="hidden" name="files" :value="filesArray" />
        <input type="hidden" name="images" :value="imagesArray" />
    </form>
</template>

<script>
import ConfirmDialog from './ConfirmDialog.vue';
import Dropzone from 'vue2-dropzone';

export default {
    components: { Dropzone, ConfirmDialog },
    props: ['_prototype', '_product_id', '_design_id'],
    data() {
        return {
            submitText: this._prototype.id ? 'Update' : 'Create',
            prototype: this._prototype,
            uploadedImages: [],
            uploadedFiles: [],
        };
    },
    computed: {
        images() {
            return this.prototype.media ? this.prototype.media.filter(obj => obj.collection_name === 'images') : [];
        },
        files() {
            return this.prototype.media ? this.prototype.media.filter(obj => obj.collection_name === 'files') : [];
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
                    const media = this.prototype.media.find((o) => o.id == id);
                    const idx = this.prototype.media.indexOf(media);
                    if (~idx) {
                        axios.delete('/attachment/remove', {
                            data: { id },
                        }).then((response) => {
                            if (response.status === 200) {
                                this.prototype.media.splice(idx, 1);
                            }
                        });
                    }
                },
            }).modal('show');
        },
    },
};
</script>