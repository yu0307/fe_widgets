<template>
    <div class="wg-settings my-1">
        <select v-if="(config.type=='select')" class="form-control form-select" :name="config.key" v-model="value" >
            <option v-for="(option,idx) in (config.options||[])" :key="idx" :value="option">{{option}}</option>
        </select>

        <div v-else-if="(config.type=='switch')" class="form-check-inline form-switch me-2">
            <input class="form-check-input form-control" type="checkbox" toggle v-model="value" :name="config.key" >
        </div>

        <div v-else-if="(config.type=='radio')" class="form-check-inline me-2">
            <div v-for="(option,idx) in (config.options||[])" :key="idx">
                <input :value="option" class="form-check-input form-control" v-model="value" type="radio" :name="config.key">
                <label class="form-check-label">
                    {{option}}
                </label>
            </div>
        </div>

        <div v-else-if="(config.type=='checkbox')" class="form-check-inline me-2">
            <div v-for="(option,idx) in (config.options||[])" :key="idx">
                <input class="form-check-input form-control" v-model="value" :name="config.key" type="checkbox" :value="option">
                <label class="form-check-label">{{option}}</label>
            </div>
        </div>

        <div v-else class="prepend-icon">
            <input class="form-control form-white" type="text" :name="config.key" v-model="value" :placeholder="(config.placeholder||'')" >
            <i class="fa fa-indent"></i>
        </div>
    </div>
</template>

<script>
export default {
    name:'settings',
    emits: ['update:modelValue'],
    props:{
        config:{
            required:true
        },
        modelValue:{
            default:null
        }
    },
    computed: {
        value: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value);
            }
        }
    }
    
}
</script>