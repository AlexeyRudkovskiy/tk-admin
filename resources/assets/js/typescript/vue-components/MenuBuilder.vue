<template>
    <div>
        <add-menu-item :id="id" :entities="entities"></add-menu-item>
        <menu-field :id="id" :field-name="fieldName" :items="items"></menu-field>
    </div>
</template>

<script>
    import AddMenuItem from "./AddMenuItem";
    import MenuField from "./MenuField";
    export default {
        name: "MenuBuilder",
        components: {MenuField, AddMenuItem},
        props: {
            id: {
                type: String,
                default: null
            },
            fieldName: {
                type: String,
                default: null
            }
        },
        data() {
            return {
                entities: [],
                items: []
            }
        },
        mounted() {
            this.entities = window.menus[this.id].entities;
            this.items = window.menus[this.id].items;
        }
    }
</script>

<style scoped>

</style>