var AitWin = function(content,options){
    this.options = $.extend(this.options,options);
    this.dom = this.construct();
    this.draw(this.dom);
    this.load(this.dom,content);
    return this;
};

AitWin.prototype = {
    options: {
        success: function(){},
        classes: {
            mainContainer: 'aitwin',
            header: 'header',
            footer: 'footer',
            content: 'content',
            loading: 'loading'
        }
    },
    dom: null, // Ventana del ojeto

    construct: function(){
        var dom = $('<div>').addClass(this.options.classes.mainContainer)
            .append($('<div>').addClass(this.options.classes.header))
            .append($('<div>').addClass(this.options.classes.content))
            .append($('<div>').addClass(this.options.classes.footer));
        dom.data('AitWin',this);
        return dom;
    },

    kill: function(){
        this.dom.detach();
    },

    load: function(win,content){
        this.dom.addClass(this.options.loading);
        $.ajax(content,{
            success: function(r){
                $('.'+this.options.classes.content,this.dom).html(r);
                //this.dom.removeClass(this.options.loading);
                this.options.success.bind(this)();
            }.bind(this)
        });
    },

    draw: function(win){

        $('body').append(this.dom);
        /*
        $('body').click(function(){
            this.kill();
        }.bind(this));

        this.dom.click(function(){
            return false;
        });
        */

    }

};

$.fn.aitWin = function(list){
    this.each(function(i){
        $(this).click(function(){
            new AitWin($(this).attr('href')).caller = this;
            return false;
        });
    });
    return list;
}