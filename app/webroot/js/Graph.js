function Graph(container){

    this.data = [];
    this.container = $('<div>');
    this.graphicHeight = 400; // Alto del gráfico
    this.percent100 = 2000; // cantidad de dinero que corresponde al 100%
    this.dayWidth = null; // ancho en píxeles de el bloque de un día
    this.monthNames = ['ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC'];

    this.drawDayBlock = function(day)
    {

        day.day = this.parseDateStr2Int(day.day);

        var container = $('<div>')
            .addClass('day')
            .css({
                width: this.dayWidth+'%'
            })
            .append(this.genAmountBlock(day.real))
            .append(this.genDayText(day.day))
            .append(this.genMovementsBlock(day.movements));

        if(this.checkIsToday(day.day))
            container.addClass('today');

        return container;

    };

    this.checkIsToday = function(day)
    {
        var date = new Date();

        return (
            date.getFullYear()==day.date.year
                && date.getMonth()+1==day.date.month
                && date.getDate()==day.date.day
            )
    }

    this.parseDateStr2Int = function(day)
    {

        day.date = day.date.split(' ');
        day.date[0] = day.date[0].split('-');
        day.date[1] = day.date[1].split(':');
        day.date = {
            year: day.date[0][0],
            month: day.date[0][1],
            day: day.date[0][2],
            hour: day.date[1][0],
            minute: day.date[1][1],
            second: day.date[1][2]
        };

        return day;

    };

    this.genAmountBlock = function(amount)
    {
        return $('<div>')
            .addClass('block')
            .css({
                height: amount*this.graphicHeight/this.percent100
            })
            .append(
            $('<div>')
                .addClass('amount')
                .html(amount+'?')
            );
    };

    this.genDayText = function(day)
    {
        return $('<div>').addClass('date')
            .append($('<span>').addClass('day').html(day.date.day))
            .append($('<span>').addClass('month').html(this.monthNames[parseInt(day.date.month)-1]));
    };

    this.genMovementsBlock = function(movements)
    {
        var container = $('<div>').addClass('movementsInADay').hide();
        var movementsContainer = $('<ul>');
        $(movements).each(function(){
            movementsContainer
                .append($('<li>')
                    .append($('<a>')
                        .attr('href','#')
                        .html(movments.Movement.name)
                    )
                )
        });
        container.append(movementsContainer);
        return container;
    }

    this.initEvents = function()
    {
        $('.day',this.container);
    }

    return this;

};

Graph.prototype = {

    draw: function(i)
    {

        var controller = this;


        this.container.html(false);

        $(this.data).each(function(i,value){

            var dayDom = controller.drawDayBlock(value);

            dayDom.css({left: (i*controller.dayWidth)+'%'});

            controller.container.append(dayDom);

        }).bind(this);

        this.initEvents();

        return this.container;

    },

    setData: function(days)
    {
        this.data = days;
        this.dayWidth = 100/$(days).size();
        return this;
    }

};