import _ from "lodash";
import axios from "axios";
export default class widgetTimer{
    constructor(interval){
        this.globalWidgetTimer;
        this.globalWidgetTimerPool= {};
    
        this.globalWidgetTimer = setInterval(()=>{
            Object.entries(this.globalWidgetTimerPool).forEach(([key, widget])=>{
                this.sendWidgetAjax(widget);
            });
        }, interval);
    }

    checkAjaxStatus(widget) {
        widget.AjaxInterval = (undefined == widget.AjaxInterval) ? false : widget.AjaxInterval;
        document.getElementById('wg_'+widget.key).querySelector('.control-btn .panel-reload').classList.remove('d-none');
        this.sendWidgetAjax(widget);
        if(widget.AjaxInterval) this.globalWidgetTimerPool[widget.key] = (widget);
    }

    sendWidgetAjax(widget) {
        widget.loading=true;
        let dom = document.getElementById('wg_'+widget.key);
        let request = (
                widget.AjaxType.toLowerCase()=='get'?
                axios({
                    method: widget.AjaxType,
                    url: widget.AjaxURL,
                    data: (widget.data||{})
                }):
                axios({
                    method: widget.AjaxType,
                    url: widget.AjaxURL,
                    params: (widget.data||{})
                })
            );
        request.then((resp)=>{
            dom.dispatchEvent(new CustomEvent('AjaxUpdated',{detail:{data:resp.data}}));
        }).catch((err)=>{
            console.log(err);
        }).then(()=>{
            widget.loading=false;
        });
        return request;
    }

    addWidgetGlobalTimer(widget){
        this.globalWidgetTimerPool[widget.key]=widget;
    }

    removeWidgetGlobalTimer(key) {
        if (this.globalWidgetTimerPool.hasOwnProperty(key)) {
            delete this.globalWidgetTimerPool[key];
        }
    }
}