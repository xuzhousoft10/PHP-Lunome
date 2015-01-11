$(document).ready(function() {
    $('textarea')
    .focus()
    .typetype("哎呀！ 不好了， 不好了， 藏在这里都被你找到了～～～～")
    .delay(1500) 
    .typetype("\n\n天哪，这是为什么？ 究竟是为什么？") 
    .delay(1500) 
    .typetype("\n\n让我想想")
    .delay(1000) 
    .typetype("～")
    .delay(1000) 
    .typetype("～")
    .delay(1000) 
    .typetype("～")
    .delay(4000)
    .delay(1000) 
    .typetype("\n\n算了，算了， 伤害大脑的事情还是找别人吧～～～～") 
    .delay(1000)
    .backspace(100)
    .typetype("嘘～～～ 你什么都不知道， 什么都没看见!")
    .delay(1000)
    .backspace(80)
    .typetype("这就送你回去吧～～～", {
        callback:function() {
            window.location='/';
        }
    });
});