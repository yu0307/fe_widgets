let wg_c_seconds = new Date().getSeconds(),
wg_c_hours = new Date().getHours(),
wg_c_mins = new Date().getMinutes(),
wg_c_sdegree = wg_c_seconds * 6,
wg_c_hdegree = wg_c_hours * 30 + (wg_c_mins / 2),
wg_c_mdegree = wg_c_mins * 6,
wg_c_srotate = "rotate(" + wg_c_sdegree + "deg)",
wg_c_hrotate = "rotate(" + wg_c_hdegree + "deg)",
wg_c_mrotate = "rotate(" + wg_c_mdegree + "deg)";

document.getElementById('fe_widgetCtrls').addEventListener('widgetReady',(e)=>{
    if(e.detail.w_config.Type=='wg_clock') initClock(e.detail.elm);
});


function wg_c_addZero(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}

function wg_c_updateWatch(t){
    if(t.querySelector('.flip-card-front')){
        t.querySelector(".jquery-clock-sec").style.cssText=`-moz-transform:${wg_c_srotate};-webkit-transform:${wg_c_srotate};-ms-transform:${wg_c_srotate}`;
        t.querySelector(".jquery-clock-hour").style.cssText=`-moz-transform:${wg_c_hrotate};-webkit-transform:${wg_c_hrotate};-ms-transform:${wg_c_hrotate}`;
        t.querySelector(".jquery-clock-min").style.cssText=`-moz-transform:${wg_c_mrotate};-webkit-transform:${wg_c_mrotate};-ms-transform:${wg_c_mrotate}`;
        if (null !== t.parentElement.querySelector('.wg_clock_digital')) {
            var date = new Date();
            t.parentElement.querySelector('.wg_clock_digital .wg_hour').innerText=wg_c_addZero(date.getHours());
            t.parentElement.querySelector('.wg_clock_digital .wg_min').innerText=wg_c_addZero(date.getMinutes());
            t.parentElement.querySelector('.wg_clock_digital .wg_sec').innerText=wg_c_addZero(date.getSeconds());
        }
    }
}

function initClock(elm){
    elm.querySelectorAll(".jquery-clock-sec, .jquery-clock-hour, .jquery-clock-min").forEach((em)=>{
        em.classList.remove('jquery-clock-transitions');
        em.classList.add('jquery-clock-transitions');
    })
    setInterval(()=>{
        wg_c_sdegree += 6;
        if (wg_c_sdegree % 360 == 0) {
            wg_c_mdegree += 6;
        }
        wg_c_hdegree += (0.1 / 12);
        wg_c_srotate = "rotate(" + wg_c_sdegree + "deg)";
        wg_c_hrotate = "rotate(" + wg_c_hdegree + "deg)";
        wg_c_mrotate = "rotate(" + wg_c_mdegree + "deg)";
        wg_c_updateWatch(elm);
    }, 1000);
}