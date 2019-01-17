<template>
    <div class="form-group">
        <label class="form-control-label">Елементи меню</label>
        <div>
            <div v-if="menuItems.length < 1" class="alert alert-warning">Empty</div>
            <draggable v-model="menuItems">
                <menu-item v-for="item in menuItems" :key="item.item_id" :value="item" :item="item" :id="id"></menu-item>
            </draggable>
            <textarea v-bind:name="fieldName" style="display: none;">{{ menuItems }}</textarea>
        </div>
    </div>
</template>

<script>
    import {eventsBus} from "../main";
    import MenuItem from "./MenuItem.vue";
    import draggable from 'vuedraggable';

    export default {
        name: "MenuField",
        components: {
            MenuItem,
            draggable
        },
        props: {
            id: {
                type: String,
                default: null
            },
            fieldName: {
                type: String,
                default: null
            },
            items: {
                type: Array,
                default: () => []
            }
        },
        data() {
            return {
                menuItems: [  ]
            }
        },
        watch: {
            items: function (_items) {
                this.menuItems = _items;
            }
        },
        methods: {
            addMenuItem(payload) {
                this.menuItems.push(payload);
            },
            remove(id) {
                const item = this.menuItems
                    .filter(item => item.item_id === id)[0];
                const index = this.menuItems.indexOf(item);

                this.menuItems.splice(index, 1);
            }
        },
        mounted() {
            this.menuItems = this.items;

            eventsBus.$on(this.id + '-selected', (payload) => {
                this.addMenuItem(payload);
            });

            eventsBus.$on(this.id + '-remove', (payload) => {
                this.remove(payload.id);
            });
        }
    }
</script>

<style scoped>

</style>