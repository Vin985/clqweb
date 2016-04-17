function add_price_column(element, position) {
    element.find('.row').each(function() {
        $(this).find('div').eq(position).after('<div class="value">Prix</div>');
    });
}


function add_category(element) {
    var tmp = element.attr('id').split('_');
    var catId = parseInt(tmp[1]);

    var first = (catId == 0);
    var last = !element.next('.category').length;

    var newTable = element.clone();
    newTable.find('.rate:gt(0)').remove();
    newTable.find('input[type=text]').val('');
    newTable.find('.rate').find('button.down').css('visibility', 'hidden');

    element.after(newTable);

    element.nextAll('.category').each(function() {
        update_ids($(this), 1, 0, 'category');
    });

    return true;
}

function delete_category(element) {
    var tmp = element.attr('id').split('_');
    var catId = parseInt(tmp[1]);

    var first = (catId == 0);
    var last = !element.next('.category').length;

    if (first && last) {
        // Only one category, just empty it
        element.find('.rate:gt(0)').remove();
        element.find('input[type=text]').val('');
        element.find('button.down').css('visibility', 'hidden');
    } else {
        element.nextAll('.category').each(update_cat_down);
        element.remove();
    }
    return true;
}

function add_rate(element) {
    var tmp = element.attr('id').split('_');
    var catId = parseInt(tmp[1]);
    var rateId = parseInt(tmp[2]);

    var first = (rateId == 0);
    var last = !element.next('.rate').length;

    var newRow = element.clone();
    newRow.find('input[type=text]').val('');

    if (first) {
        newRow.find('button.up').css('visibility', 'visible');
    }
    if (last) {
        element.find('button.down').css('visibility', 'visible');
    }
    element.after(newRow);
    element.nextAll('div[id^=rate_' + catId + ']').each(update_rate_up);

    return true;
}

function delete_rate(element) {
    var tmp = element.attr('id').split('_');
    var catId = parseInt(tmp[1]);
    var rateId = parseInt(tmp[2]);

    var first = (rateId == 0);
    var last = !element.next('.rate').length;

    if (first && last) {
        // Only one rate, just empty it
        element.find('input[type=text]').val('');
    } else {
        if (first) {
            element.next().find('button.up').css('visibility', 'hidden');
        }
        if (last) {
            element.prev().find('button.down').css('visibility', 'hidden');
        }
        element.nextAll('div[id^=rate_' + catId + ']').each(update_rate_down);
        element.remove();
    }
    return true;
}

function move_rate(element, up) {

    var destRow;
    if (up) {
        destRow = element.prev('.rate');
    } else {
        destRow = element.next('.rate');
    }

    if (destRow.length > 0) {
        var source = element.find('input[type=text]');
        var dest = destRow.find('input[type=text]');

        for (i = 0; i < source.length; i++) {
            var src = source.get(i);
            var dst = dest.get(i);
            var tmp = dst.value;

            dst.value = src.value;
            src.value = tmp;
        }
    }
}

function update_cat_down() {
    update_ids($(this), -1, 0, 'category');
}

function update_cat_up() {

}

function update_rate_cat_down() {
    update_ids($(this), -1, 0, 'rate');
}

function update_rate_cat_up() {
    update_ids($(this), 1, 0, 'rate');
}

function update_rate_down() {
    update_ids($(this), 0, -1, 'rate');
}

function update_rate_up() {
    update_ids($(this), 0, 1, 'rate');
}

function update_ids(element, coff, roff, type) {
    var tmp = element.attr('id').split('_');
    var catId = parseInt(tmp[1]) + coff;
    var rateId = parseInt(tmp[2]) + roff;
    var id = catId;

    if (type == "rate") {
        id += '_' + rateId;
        update_input(element, 'name', type + '_value_', type, catId, rateId);
    } else {
        element.find('.rate').each(coff > 0 ?
            update_rate_cat_up : update_rate_cat_down);
    }
    element.attr('id', type + '_' + id);
    update_input(element, 'name', type + '_name_', type, catId, rateId);
}

function update_input(element, attr, id, type, catId, rateId) {
    update_field(element, 'input', attr, id, type, catId, rateId);
}

function update_field(element, field, attr, id, type, catId, rateId) {
    var target = element.find(field + '[' + attr + '^=' + id);

    if (type == 'category') {
        var tmp = target.attr(attr).split('_');
        var rateId = parseInt(tmp[2]);
        if (isNaN(rateId)) {
            target.attr(attr, id + catId);
        } else {
            target.attr(attr, id + catId + '_' + rateId);
        }
    } else {
        target.attr(attr, id + catId + '_' + rateId);
    }
}
