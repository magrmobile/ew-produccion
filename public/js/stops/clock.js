var d, h, m, s, animate, sec, min, hr;

function init() {
    d = new Date();
    h = d.getHours();
    m = d.getMinutes();
    s = d.getSeconds();
    clock();
};
 
function clock() {
    sec = $('#sec');
    min = $('#min');
    hr = $('#hr');

    s++;
    if(s == 60) {
        s = 0;
        m++;
        if(m == 60) {
            m = 0;
            h++;
            if(h == 24) {
                h = 0;
            }
        }
    }

    s < 10 ? sec.html('0' + s) : sec.html(s);
    m < 10 ? min.html('0' + m) : min.html(m);
    h < 10 ? hr.html('0' + h) : hr.html(h);

    animate = setTimeout(clock, 1000);
};

window.onload = init;