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
        document.getElementById(widget.key).querySelector('.control-btn .panel-reload').classList.remove('d-none');
        this.sendWidgetAjax(widget);
        if(widget.AjaxInterval) this.globalWidgetTimerPool[widget.key] = (widget);
    }

    sendWidgetAjax(widget) {
        let dom = document.getElementById(widget.key);
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
            dom.dispatchEvent('AjaxUpdated',resp.data);
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