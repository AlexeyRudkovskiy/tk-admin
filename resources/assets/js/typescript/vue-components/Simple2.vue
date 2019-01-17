<template>
    <div>
        <input type="text" @model="foo" />{{ foo }}
    </div>
</template>

<script>
    export default {
        name: "Simple2",
        data() {
            return {
                foo: 123
            };
        },
        mounted() {
            window.setInterval(() => this.foo++, 500);
        }
    }
</script>

<style scoped>

</style>