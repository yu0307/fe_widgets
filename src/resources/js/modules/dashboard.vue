<template>
    <div id="widget-container" class="h-100">
        <div class="container-fluid widgetArea h-100" id="fe_widgetArea">
            <div class="list-group" id="fe_widgetCtrls">
                <slot name="initialwidgets"/>
                <component v-for="widget in widgets" :key="widget.key" :is="widget.type" />
            </div>
            <slot name="widgetcontrols"/>
        </div>
    </div>
</template>

<script>
// import Sortable from 'sortablejs';
export default {
    name:'dashboard',
    props:{
        loadedWidgets:{
            default:[]
        }
    },
    data(){
        return {
            widgets:[
            ]
        }
    },
    methods:{
        addWidget(widget){
            this.widgets.push(widget);
        }
    },
    mounted(){
        this.widgets=this.loadedWidgets;
    }
}
</script>