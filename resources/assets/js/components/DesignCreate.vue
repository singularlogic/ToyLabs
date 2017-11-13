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

        <h3 class="ui dividing header">
            Images
            <div class="sub header">For informational images only. If your design is public, these are made public as well</div>
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
            paramName="file"
            v-on:vdropzone-success="fileAdded"
            v-on:vdropzone-removed-file="fileRemoved"
        >
            <input type="hidden" name="_token" :value="$parent.crsf" />
        </dropzone>

        <div class="ui divider"></div>

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
    props: ['_design', '_product_id'],
    data() {
        return {
            submitText: this._design.id ? 'Update' : 'Create',
            design: this._design,
            uploadedImages: [],
            uploadedFiles: [],
        };
    },
    computed: {
        images() {
            return this.design.media ? this.design.media.filter(obj => obj.collection_name === 'images') : [];
        },
        files() {
            return this.design.media ? this.design.media.filter(obj => obj.collection_name === 'files') : [];
        },
        imagesArray() {
            return JSON.stringify(this.uploadedImages);
        },
        filesArray() {
            return JSON.stringify(this.uploadedFiles);
        }
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
                    const media = this.design.media.find((o) => o.id == id);
                    const idx = this.design.media.indexOf(media);
                    if (~idx) {
                        axios.delete('/attachment/remove', {
                            data: { id },
                        }).then((response) => {
                            if (response.status === 200) {
                                this.design.media.splice(idx, 1);
                            }
                        });
                    }
                },
            }).modal('show');
        },
    }
}
</script>