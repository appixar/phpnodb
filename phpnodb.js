let jData = {};
$(function() {
    $.getJSON("phpnodb/data/public/data.php", function(data) {
        console.log(data);
        jData = data;
        jStart();
    });
    $("*[x-data]").click(function() {
        $("*[x-data]").each(function() {
            $(this).removeClass('active');
        });
        $(this).addClass('active');
    });
});

function jStart() {
    $('*[x-data]').each(function() {
        var key = $(this).attr('x-data');
        jPopulate($(this), key);
    });
};

function jPopulate($this, key) {
    var val = jData[key];
    var tag = ['input'];
    $this.html(val);
};