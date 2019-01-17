<template>
    <div class="menu-item">
        <div class="d-flex align-items-center menu-item-header">
            <span>{{ this.title }}</span>
            <span class="arrow" @click="remove()"><i class="fa fa-remove"></i></span>
        </div>
        <div v-if="isEditing" class="editing">
            <a href="javascript:" @click="edit()">Редагувати</a>
        </div>
    </div>
</template>

<script>
    import {eventsBus} from "../main";

    export default {
        name: "MenuItem",
        props: {
            item: {
                type: Object,
                default: {}
            },
            id: {
                type: String,
                default: null
            }
        },
        data() {
            return {
                isEditing: false
            };
        },
        computed: {
            title() {
                if (this.item.type !== 'link') {
                    return this.item.title;
                } else if (this.item.type === 'link') {
                    return this.item.text;
                }
            },
            url() {
                if (this.item.type !== 'link') {
                    return '==URL=='
                } else if (this.item.type === 'link') {
                    return this.item.url;
                }
            }
        },
        methods: {
            showDetails() {
                this.isEditing = !this.isEditing;
            },
            edit() {
                eventsBus.$emit(this.id + '-edit', this.item);
            },
            remove() {
                eventsBus.$emit(this.id + '-remove', {
                    id: this.item.item_id
                });
            }
        }
    }
</script>

<style scoped>

    .menu-item {
        box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
        background-color: white;
        max-width: 100%;

        margin-top: 20px;
        margin-bottom: 20px;
    }

    .menu-item .menu-item-header {
        background-color: rgba(0, 0, 0, .04);
    }

    .menu-item .menu-item-header span {
        display: block;
        box-sizing: border-box;
        padding: 10px;
        padding-right: 20px;
        padding-left: 20px;
    }

    .menu-item .menu-item-header span.arrow {
        margin-left: auto;
    }

    .menu-item .menu-item-header span.arrow.editing i.fa {
        transform: rotateX(180deg);
    }

    .menu-item .menu-item-header span.arrow:hover {
        background-color: rgba(0, 0, 0, .05);
        cursor: pointer;
    }

    .editing {
        box-sizing: border-box;
        padding: 10px 20px;
    }

</style>