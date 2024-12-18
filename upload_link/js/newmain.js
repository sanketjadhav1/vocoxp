$(function(){

    $("#wizard").steps({

        headerTag: "h2",

        bodyTag: "section",

        transitionEffect: "fade",

        enableAllSteps: false,

        autoFocus: false,

        transitionEffectSpeed: 500,

        titleTemplate : '<div class="title">#title#</div>',

        labels: {

            previous : 'Back',

            next : 'Next',

            finish : 'Submit',

            current : ''

        },

    });

    $("#date").datepicker({

        dateFormat: "MM - DD - yy",

        showOn: "both",

        buttonText : '<i class="zmdi zmdi-chevron-down"></i>',

    });

});
