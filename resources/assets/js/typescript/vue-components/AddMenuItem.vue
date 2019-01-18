<template>
    <div class="add-menu-item">
        <div class="form-group">
            <label class="form-control-label">Тип посилання</label>
            <div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="link" v-model="type">
                    <label class="form-check-label" for="gridRadios1">
                        Просте посилання
                    </label>
                </div>
                <div class="form-check" v-for="entity in definedEntities" :key="entity.id" :value="entity">
                    <input class="form-check-input" type="radio" name="entity" v-bind:id="entity.id" v-bind:value="entity.id" v-model="type">
                    <label class="form-check-label" v-bind:for="entity.id">
                        {{ entity.title }}
                    </label>
                </div>
            </div>
        </div>
        <div v-if="type === 'link'">
            <div class="form-group">
                <label class="form-control-label">Текст</label>
                <input type="text" class="form-control" v-model="text" />
            </div>
            <div class="form-group">
                <label class="form-control-label">Посилання</label>
                <input type="text" class="form-control" v-model="url" />
            </div>
        </div>
        <div class="form-group" v-if="type !== 'link'">
            <label class="form-control-label">Назва</label>
            <input class="form-control" v-model="record.name" />
            <div class="search-result" style="margin-top: 15px;">
                <ul>
                    <li v-for="row in search" :key="row.id" :value="row"><a href="javascript:" @click="select(row.id, row.title)">{{ row.title }}</a></li>
                </ul>
            </div>
        </div>
        <div class="form-group">
            <input type="button" value="Додати" @click="add()" class="btn btn-info" />
        </div>
    </div>
</template>

<script>
    import {eventsBus} from "../main";

    export default {
        name: "AddMenuItem",
        data() {
            return {
                text: null,
                url: null,
                type: 'link',
                record: {
                    name: null
                },
                search: [],
                definedEntities: {}
            }
        },
        props: {
            id: {
                type: String,
                default: null
            },
            entities: {
                type: Array,
                default: () => []
            }
        },
        methods: {
            select(id, title) {
                const _type = this.type.replace('');

                const payload = {
                    id, title,
                    type: this.definedEntities[this.type.replace('entity:')].name,
                    item_id: Math.random().toString(32).substr(2, 6)
                };

                eventsBus.$emit(this.id + '-selected', payload);

                this.clear();
            },
            add() {
                const text = this.text;
                const url = this.url;
                const type = this.type !== 'link' ?
                    this.definedEntities[this.type].name :
                    this.type;
                const item_id = Math.random().toString(32).substr(2, 6);

                eventsBus.$emit(this.id + '-selected', { text, url, type, item_id });

                this.clear();
            },
            clear() {
                this.record.name = null;
                this.search = [];
                this.text = null;
                this.url = null;
            }
        },
        computed: {
            menuItem() {
                return {
                    text: this.text,
                    url: this.url,
                    type: this.type,
                    record: this.record
                }
            }
        },
        watch: {
            type: function () {
                this.record.name = null;
                this.search = [];
            },
            'record.name': _.debounce(function () {
                if (this.record.name === null || this.record.name.length < 3) {
                    return;
                }
                const entity = this.type;
                axios.post('/admin/api/search/', {
                    query: this.record.name,
                    entity: this.definedEntities[entity].name
                })
                    .then(response => response.data)
                    .then(data => this.search = data);
            }, 500)
        },
        mounted() {
            this.definedEntities = {};

            if (this.entities.length > 0) {
                this.entities
                    .forEach(entity => {
                        this.definedEntities[entity.id] = entity;
                    });
            } else {
                window.menus[this.id].entities
                    .forEach(entity => {
                        this.definedEntities[entity.id] = entity;
                    });
            }

            eventsBus.$on(this.id + '-edit', (item) => {
                this.type = item.type;
                if (this.type === 'link') {
                    this.text = item.name;
                    this.url = item.url;
                } else if (this.type === 'post' || this.type === 'page') {
                    this.record.name = item.title;
                }
            });
        }
    }
</script>

<style scoped>
</style>