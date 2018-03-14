<template>
    <form class="ui form" method="POST">
        <input type="hidden" name="_token" :value="$parent.crsf" />

        <div class="field">
            <label>Title</label>
            <input type="text" name="title" v-model="model.title" placeholder="Enter a descriptive title for your AR model" required />
        </div>
        <div class="field">
            <label>Description</label>
            <textarea v-model="model.description" name="description"></textarea>
            <div class="ui info message">
                Use this field to explain to users:
                <ul>
                    <li>what to expect,</li>
                    <li>how to use the model,</li>
                    <li>possible shortcomings,</li>
                    <li>what to test,</li>
                    <li>whatever you think is necessary to know before they use the model.</li>
                </ul>
            </div>
        </div>

        <h3 class="ui dividing header">
            Files
            <div class="sub header">
                For the model to work correctly, all the nodes of the 3D model should be under a parent node and that parent node should have the exact same name as the filename of the model. Keep in mind that, when opened in the ToyLabs app, the front of the model will be displayed, so make sure it is designed accordingly. <strong>Supported file types:</strong> fbx, obj, 3ds, stl, dae, scn (and image files for use as textures).
            </div>
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
            acceptedFileTypes="image/*,.fbx,.obj,.3ds,.stl,.scn,.dae"
            v-on:vdropzone-success="fileAdded"
            v-on:vdropzone-removed-file="fileRemoved"
        >
            <input type="hidden" name="_token" :value="$parent.crsf" />
        </dropzone>
        <div class="ui attached negative message" v-if="error">
            <p><strong>Error!</strong> {{ error }}</p>
        </div>

        <h3 class="ui dividing header">
            Questions
            <div class="sub header">
                Users that download your AR model will be asked to rate it based on a number of criteria (change to suit your specific needs). Rating will be done using a scale of 1-5.
            </div>
        </h3>

        <div class="ui labeled fluid input" style="padding-bottom: 8px;" v-for="(question, idx) in model.questions" :key="idx">
            <div class="ui label">Question {{ idx + 1 }}</div>
            <input type="text" v-model="question.text" required />
        </div>

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
        <input type="hidden" name="questions" :value="questionsArray" />
    </form>
</template>

<script type="text/javascript">
import ConfirmDialog from './ConfirmDialog.vue';
import Dropzone from 'vue2-dropzone';

export default {
    name: 'ARModelCreate',
    props: ['_model', '_parent_id', '_parent_type'],
    components: { ConfirmDialog, Dropzone },
    data() {
        return {
            submitText: this._model.id ? 'Update' : 'Create',
            model: this._model,
            uploadedFiles: [],
            error: '',
        };
    },
    computed: {
        files() {
            return this.model.media ? this.model.media.filter(obj => obj.collection_name === 'files') : [];
        },
        filesArray() {
            return JSON.stringify(this.uploadedFiles);
        },
        questionsArray() {
            return JSON.stringify(this.model.questions);
        },
        canSubmit() {
            return this.uploadedFiles.length > 0;
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
        deleteMedia(id) {
            $('#imageDelete').modal({
                closable: false,
                onApprove: () => {
                    const media = this.model.media.find((o) => o.id == id);
                    const idx = this.model.media.indexOf(media);
                    if (~idx) {
                        axios.delete('/attachment/remove', {
                            data: { id },
                        }).then((response) => {
                            if (response.status === 200) {
                                this.model.media.splice(idx, 1);
                            }
                        });
                    }
                },
            }).modal('show');
        },
    },
};
</script>