<template>
    <div class="file-container">
        <div class="file-icon"><img v-bind:src="getIconPath()" /></div>
        <div class="file-name" v-if="!rename.isActive" :ref="'filename'">{{ fileName }}</div>
        <div class="file-input-field" v-if="rename.isActive"><input type="text" v-model="fileName" v-bind:style="{ width: (rename.inputWidth + 8) + 'px' }" @blue="renameFile" /></div>
        <div class="file-rename-apply" v-if="rename.isActive" @click="renameFile"><i class="fa fa-check"></i></div>
        <div class="file-size">{{ size }}</div>
        <div class="file-actions">
            <ul class="file-actions-list">
               <li class="file-action" @click="showLink"><i class="fa fa-link"></i></li>
               <li class="file-action" @click="showRenameForm"><i class="fa fa-pencil"></i></li>
               <li class="file-action action-delete" @click="deleteFile"><i class="fa fa-trash"></i></li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        name: "File",
        data() {
            return {
                fileName: null,
                size: null,
                type: null,
                url: null,
                fileObject: null,

                rename: {
                    isActive: false,
                    inputWidth: 0
                }
            }
        },
        props: {
            file: {
                type: Object,
                default: () => { return {}; }
            }
        },
        methods: {
            getIconPath() {
                const basePath = '/arudkovskiy/admin/icons/files-types/';
                return basePath + this.type + '.svg';
            },
            updateFile(_file) {
                this.fileName = _file.filename;
                this.size = _file.size;
                this.type = _file.type;
                this.url = _file.url;
                this.fileObject = _file;
            },
            showLink() {
                const path = this.fileObject.url;
                alert(path);
            },
            deleteFile() {
                const message = 'Ви дійсно хочете видалити цей файл?' + "\n" + 'Відновити видалений файл буде неможливо';
                if (confirm(message)) {
                    axios.post(`/admin/files/delete`, {
                        file: this.file.path
                    })
                        .then(response => response.data)
                        .then(response => response.deleted)
                        .then(isDeleted => {
                            if (isDeleted) {
                                this.$emit('deleted', this.file);
                            }
                        });
                }
            },
            showRenameForm() {
                const filenameRef = this.$refs.filename;
                if (typeof filenameRef !== "undefined") {
                    const boundingRect = filenameRef.getBoundingClientRect();
                    this.rename.inputWidth = boundingRect.width;
                }
                this.rename.isActive = !this.rename.isActive;
            },
            renameFile() {
                const newFileName = this.fileName;
                axios.post(`/admin/files/rename`, {
                    file: this.file.path,
                    name: newFileName
                })
                    .then(response => response.data)
                    .then(response => {
                        this.fileName = newFileName;
                        this.fileObject.url = response.url;
                        this.file.path = response.file;

                        return response;
                    })
                    .then(response => this.rename.isActive = false)
                    .catch(e => alert(e.response.data.message));
            }
        },
        watch: {
            file: function (_file) {
                this.updateFile(_file);
            }
        },
        mounted() {
            this.updateFile(this.file);
        }
    }
</script>

<style scoped>

</style>