<template>
    <tr v-for="(record, index) in this.records" :key="index">
        <td><input placeholder="Ключ" type="text" v-model="record.key" class="form-control"></td>
        <td><input placeholder="Значение" type="text" v-model="record.value" class="form-control"></td>
        <td>
            <div class="d-grid gap-2">
                <button v-bind:disabled="record.key === '' || record.value === ''" type="button" @click="updateRecord(record, index)" class="btn btn-success">Обновить</button>
            </div>
        </td>
        <td>
            <div class="d-grid gap-2">
                <button type="button" @click="deleteRecord(index)" class="btn btn-danger">Удалить</button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {
        emits: ["updateRecord", "deleteRecord"],
        props: {
            records: {
                type: Array,
                required: true
            }
        },
        methods: {
            updateRecord(record, index){
                this.$emit('updateRecord', record, index);
            },
            deleteRecord(index){
                this.$emit('deleteRecord', index);
            }
        }
    }
</script>