<template>

    <div v-if="this.error !== ''" class="alert alert-danger" role="alert">{{ error }}</div>
    <div v-if="this.success !== ''" class="alert alert-success" role="alert">{{ success }}</div>

    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th scope="col">Ключ</th>
                <th scope="col">Значение</th>
                <th width="5%" scope="col">Действие</th>
                <th width="5%" scope="col">Удалить</th>
            </tr>
        </thead>
        <tbody>

            <Form :new_record="this.new_record" @addRecord="addRecord" /> 

            <List :records="records" @updateRecord="updateRecord" @deleteRecord="deleteRecord" />

        </tbody>
    </table>
</template>

<script>

import Form from "./Form.vue"
import List from "./List.vue"

export default {
    components: {
        Form, List
    },
    data() {
        return {
            new_record: {
                key: "",
                value: ""
            },
            error: "",
            success: "",
            records: []
        };
    },
    mounted(){
        this.getAll()
    },
    methods: {

        setMessage(alert, msg){
            let self = this;
            self[alert] = msg;
            setTimeout(function(){
                self[alert] = "";
            }, 3000);
        },

        checkExistKey(key, index){
            let self = this;
            this.error = "";
            let record_key = key.toString().trim();

            this.records.forEach(function(exists_record, exists_index){
                if (exists_record.key.toString() === record_key && exists_index !== index){
                    self.setMessage("error", "Ключ \"" + key + "\" уже есть");
                }
            });

            return this.error === "";
        },

        async checkResponse(response){
            this.error = "";

            if (response.ok) {
                
                let result = await response.text();

                try {
                    result = JSON.parse(result);
                    if (result.result){
                        this.setMessage("success", result.result);
                    }
                    else {
                        this.setMessage("error", result);
                    }
                    
                }
                catch (error) {
                    this.setMessage("error", result);
                }


            } else {
                this.setMessage("error", response.status);
            }

            return this.error === "";
        },

        async getAll(){
            let response = await fetch('/all');
            this.records = await response.json();
        },

        async deleteRecord(index) {
            let key = this.records[index].key;
            let response = await fetch('/delete/' + key);
            if (await this.checkResponse(response)){
                this.records.splice(index, 1);
            }            
        },

        async updateRecord(record, index){
            if (!this.checkExistKey(record.key, index)){
                return;
            }

            await this.postRecord(record.key, record.value, record.store_key);

            if (this.error === ""){
                this.records[index].store_key = record.key;
            }
        },

        async addRecord(new_record){
            if (!this.checkExistKey(new_record.key, -1)){
                return;
            }

            let key = new_record.key.trim();
            let value = new_record.value.trim();

            await this.postRecord(key, value, "");

            if (this.error === ""){

                this.records.push({
                    key: key,
                    value: value,
                    store_key: key
                });

                this.new_record.key = this.new_record.value = "";
            }
        },

        async postRecord(key, value, store_key){
            let response = await fetch('/set', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify({
                    key: key,
                    value: value,
                    store_key: store_key
                })
            });

            await this.checkResponse(response);
        }
    }
}
</script>
