let jData = {};
$(function() {
    $.getJSON("phpnodb/data/public/data.php", function(data) {
        console.log(data);
        jData = data;
        jStart();
    });
});

function jStart() {
    $('*[x-data]').each(function(e) {
        var key = $(this).attr('x-data');
        jPopulate($(this), key);
    });
    $('*[x-list]').each(function() {
        var key = $(this).attr('x-list');
        jList($(this), key);
    });
};

function jPopulate($this, key, i = -1) {
    if (i > -1) var val = jData[key][i];
    else var val = jData[key];
    // html() or attr()
    var attr = $this.attr('x-data-attr');
    if (!attr) $this.html(val);
    else $this.attr(attr, val);
};

function jList($this, key) {
    var list = jData[key];
    var rand = (Math.random() + 1).toString(36).substring(7);
    console.log(`start clone: ${key}`);
    if (typeof list === "undefined" || list.length === 0) return false;
    for (var i = 0; i < list.length; i++) {
        console.log(`cloning ${i}...`);
        var newId = `clone-${rand}-${i}`;
        $this.clone().removeAttr('x-list').attr('id', newId).insertBefore($this);
        $(`#${newId} *[x-data]`).each(function() {
            var key = $(this).attr('x-data');
            jPopulate($(this), key, i);
        });
        // Find and adjust ids & attr
        var id_list = {};
        $(`#${newId} *`).each(function() {
            var id_old = $(this).attr('id');
            // New id found. Append `i`.
            if (id_old && typeof id_list[id_old] === 'undefined') {
                var id_new = id_old + '-' + i;
                id_list[id_old] = id_new;
                $(this).attr('id', id_new);
                console.log(`changed id=${id_old} to ${id_new}`);
            }
        });
        $(`#${newId} *`).each(function() {
            var $el = $(this);
            $.each(this.attributes, function() {
                var val = this.value;
                var hash = '';
                if (val.split('#')[1]) {
                    hash = '#';
                    val = val.split('#')[1];
                }
                if (typeof id_list[val] !== 'undefined') {
                    $el.attr(this.name, `${hash}${id_list[val]}`);
                    console.log(`changed ${this.name}=${this.value} to ${id_list[val]}`);
                }
            });
        });
        //console.log(id_list);
    }
    $this.remove();

};