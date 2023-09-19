<template>
    <tr>
        <td><input v-model="new_record.key" placeholder="Ключ" type="text" class="form-control"></td>
        <td><input v-model="new_record.value" placeholder="Значение" type="text" class="form-control"></td>
        <td colspan="2">
            <div class="d-grid gap-2">
                <button v-bind:disabled="this.new_record.key === '' || this.new_record.value === ''" type="button" @click="addRecord()" class="btn btn-primary">Добавить</button>
            </div>
        </td>
    </tr>
</template>


<script>
export default {
    props: {
        new_record: {
            type: Object,
            required: true
        }
    },
    methods: {
        addRecord(){
            this.$emit('addRecord', this.new_record);
        }
    }

}
</script>