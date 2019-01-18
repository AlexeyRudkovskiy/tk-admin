<template>
    <div class="file-manager" @dragover.prevent @drop="drop">
        <div class="file-manager-files">
            <div class="uploading" v-if="uploading.isActive">
                <div class="uploading-label">Завантаження файлів: {{ uploading.count }}</div>
                <div class="uploading-progress">
                    <div class="progress">
                        <div class="progress-bar" v-bind:style="{width: uploading.progress + '%'}"></div>
                    </div>
                </div>
            </div>
            <div class="file-manager-header">
                <span class="current-folder">{{ formattedCurrentFolder }}</span>
                <span><a href="javascript:" @click="createAFolder()">Створити нову директорію</a></span>
            </div>
            <div class="file-container" v-for="folder in folders" :value="folder">
                <div class="file-name" @click="goToFolder(folder)">{{ folder.name }}</div>
                <div class="file-actions">
                    <ul class="file-actions-list">
                        <li class="file-action action-delete" @click="deleteFolder(folder)"><i class="fa fa-trash"></i></li>
                    </ul>
                </div>
            </div>
            <File
                v-for="(file, index) in files"
                :value="file"
                :key="index"
                :file="file"
                @deleted="removeFile(index)"
            ></File>
        </div>
        <div class="file-manager-file-info" v-if="isShowingDetails">{{ selectedFile }}</div>
    </div>
</template>

<script>
    import File from "./File";
    export default {
        name: "FileManager",
        components: {File},
        data() {
            return {
                files: [ ],
                folders: [ ],
                isShowingDetails: false,
                selectedFile: null,
                currentFolder: '/',
                uploading: {
                    isActive: false,
                    count: -1,
                    progress: 0
                }
            };
        },
        methods: {
            showDetails(file) {
                this.selectedFile = file;
                this.isShowingDetails = true;
            },
            async reloadFileManager() {
                const data = await axios('/admin/files/directory?folder=' + this.currentFolder)
                    .then(response => response.data);
                this.files = data.files;
                this.folders = data.folders;
            },
            goToFolder(folder) {
                this.currentFolder = folder.path;
                this.reloadFileManager();
            },
            removeFile(index) {
                this.files.splice(index, 1);
            },
            deleteFolder(folder) {
                const message = 'Ви дійсно хочете видалити цю папку?' + "\n" + 'Відновити вміст папки буде неможливо';
                if (confirm(message)) {
                    axios.post(`/admin/files/delete-folder`, {
                        path: folder.path
                    })
                        .then(response => response.data)
                        .then(response => response.deleted)
                        .then(isDeleted => {
                            if (isDeleted) {
                                this.folders.splice(this.folders.indexOf(folder), 1);
                            }
                        });
                }
            },
            createAFolder() {
                const message = 'Введіть назву нової директорії';
                const folderName = prompt(message);
                if (folderName.length > 0) {
                    axios.post(`/admin/files/create-folder`, {
                        name: folderName,
                        path: this.formattedCurrentFolder
                    })
                        .then(response => response.data)
                        .then(response => {
                            let newPath = this.formattedCurrentFolder + '/' + folderName;
                            if (newPath.startsWith('/')) {
                                newPath = newPath.substr(1);
                            }
                            this.folders.push({
                                name: folderName,
                                path: newPath
                            });
                        });
                }
            },
            drop(e) {
                e.preventDefault();
                const files = [...e.dataTransfer.files];
                if (files.length < 1) {
                    return;
                }
                this.uploading.isActive = true;
                this.uploading.count = files.length;

                this.uploadFile(files);
            },
            uploadFile(files) {
                let file = null;
                if (files.length >= 1) {
                    file = files[0];
                    files.splice(0, 1);
                } else {
                    this.uploading.isActive = false;
                    return;
                }

                let xhr = new XMLHttpRequest();
                let formData = new FormData();
                let csrf = (document.querySelector('meta[name="csrf_token"]')).content;

                formData.append('file', file);
                formData.append('folder', this.currentFolder);

                xhr.open('POST', '/admin/files/upload', true);
                xhr.setRequestHeader('X-CSRF-TOKEN', csrf);

                xhr.addEventListener('progress', (e) => {
                    const loaded = e.total / e.loaded * 100;
                    this.uploading.progress = loaded;
                });

                xhr.addEventListener('readystatechange', () => {
                    if (xhr.status === 200 && xhr.readyState === 4) {
                        const response = JSON.parse(xhr.responseText);
                        this.files = response.files;
                        this.folders = response.folders;
                        this.uploading.count--;
                        this.uploadFile(files);
                    }
                });

                xhr.send(formData);
            }
        },
        computed: {
            formattedCurrentFolder() {
                if (!this.currentFolder.startsWith('/')) {
                    return '/' + this.currentFolder;
                }

                return this.currentFolder;
            }
        },
        mounted() {
            this.reloadFileManager()
        }
    }
</script>

<style scoped>

</style>