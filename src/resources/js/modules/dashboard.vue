<template>
    <div id="widget-container" class="h-100">
        <div class="container-fluid widgetArea h-100" id="fe_widgetArea">
            <div class="list-group row" id="fe_widgetCtrls">
                <slot name="initialwidgets"/>
                <component v-for="widget in widgets" :key="widget.key" :is="widget.type" />
            </div>
            <slot name="widgetcontrols"/>
        </div>
    </div>
</template>

<script>
import Sortable from 'sortablejs';
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
            ],
            sortableCtr:null
        }
    },
    methods:{
        addWidget(widget){
            this.widgets.push(widget);
        }
    },
    mounted(){
        this.widgets=this.loadedWidgets;
        this.sortableCtr=Sortable.create(document.getElementById('fe_widgetCtrls'), {//.fe_widget
        animation: 150,
        sort: true,
        filter: ".feWidgetFixed,.panel-content",  // Selectors that do not lead to dragging (String or Function)
        draggable: ".fe_widget",  // Specifies which items inside the element should be draggable
        handle: ".my-handle, .panel-controls",  // Drag handle selector within list items
        easing: "cubic-bezier(0.075, 0.82, 0.165, 1)",
        dragClass: "sortable_drag",  // Class name for the dragging item
        ghostClass: 'sortGhosting',
        onSort: function (e) {
            document.getElementById('fe_widgetCtrls').dispatchEvent('WidgetLayoutChanged');
        }
    });
    }
}
</script>