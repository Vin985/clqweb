var TYPE_CATEGORY = 'category';
var TYPE_RATE = 'rate';


function add_price_column(element) {
    // get index of element (-1 because of name)
    var n = element.index() - 1;
    //get enclosing category
    var category = element.closest('.category');
    // iterate on all rows of the category
    category.find('.row').each(function() {
        // find divs on the idx position
        var prev = $(this).find('div.price').eq(n);
        // clone the div
        var newPrice = prev.clone();
        // empty the input
        newPrice.find('input[type=text]').val('');
        // add it afte
        prev.after(newPrice);
        // find the type of the div, category by default
        var type = 'category';
        if ($(this).hasClass('rate')) {
            type = 'rate';
        }
        // update the input ids
        update_input($(this), 'name', type + '_price_', "");
    });
}

function delete_price_column(element) {
    // get index of element (-1 because of name)
    var n = element.index() - 1;
    //get enclosing category
    var category = element.closest('.category');
    // iterate on all rows of the category
    category.find('.row').each(function() {
        // find divs on the idx position
        var divs = $(this).find('div.price');
        var todel = divs.eq(n);
        if (divs.length > 1) {
            // remove it
            todel.remove();
            var type = 'category';
            if ($(this).hasClass('rate')) {
                type = 'rate';
            }
            // update the input ids
            update_input($(this), 'name', type + '_price_', "");
        } else {
            // empty the inputs
            todel.find('input[type=text]').val('');
        }
    });
}

function add_category(element) {
    var tmp = element.attr('id').split('_');
    var catId = parseInt(tmp[1]);

    var first = (catId == 0);
    var last = !element.next('.category').length;

    // clone current category
    var newCategory = element.clone();
    // empty it
    empty_category(newCategory);
    // add it after the current one
    element.after(newCategory);

    // update all next category ids by +1
    element.nextAll('.category').each(function() {
        update_ids($(this), 1, 0, TYPE_CATEGORY);
    });
    update_category_count(1);
    update_nrate_category(element, catId, 1);
    return true;
}

function delete_category(element) {
    var tmp = element.attr('id').split('_');
    var catId = parseInt(tmp[1]);

    var first = (catId == 0);
    var last = !element.next('.category').length;

    if (first && last) {
        // This is the only category, just empty it
        empty_category(element);
    } else {
        // update all next category ids by -1
        element.nextAll('.category').each(function() {
            update_ids($(this), -1, 0, TYPE_CATEGORY);
        });

        // remove the category
        element.remove();
        update_category_count(-1);
    }
    return true;
}

function update_category_count(n) {
    var ncat = $('#ncat');
    ncat.val(parseInt(ncat.val()) + n);
}

function empty_category(category) {
    // keep only one row
    category.find('.rate:gt(0)').remove();
    category.find('input[id^=nrate_]').val(1);
    // empty all inputs
    category.find('input[type=text]').val('');
    // hide all rate move buttons
    category.find('.rate').find('button.up').css('visibility', 'hidden');
    category.find('.rate').find('button.down').css('visibility', 'hidden');
}

function add_rate(element) {
    var tmp = element.attr('id').split('_');
    var catId = parseInt(tmp[1]);
    var rateId = parseInt(tmp[2]);

    var first = (rateId == 0);
    var last = !element.next('.rate').length;

    // clone current row
    var newRow = element.clone();
    // empty all inputs
    newRow.find('input[type=text]').val('');

    if (first) {
        // current row was the first, show UP button on the new one
        newRow.find('button.up').css('visibility', 'visible');
    }
    if (last) {
        // current row was the last, show DOWN button on it
        element.find('button.down').css('visibility', 'visible');
    }
    // add the new rate after the current one
    element.after(newRow);
    // update all next rate ids by +1
    element.nextAll('div[id^=rate_' + catId + ']').each(function() {
        update_ids($(this), 0, 1, TYPE_RATE);
    });
    // update rate count
    update_rate_count(catId, 1)

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
            // element was the first, hide next element up button
            element.next().find('button.up').css('visibility', 'hidden');
        }
        if (last) {
            // element was the last, hide previous element down button
            element.prev().find('button.down').css('visibility', 'hidden');
        }
        // update all next rate ids by -1
        element.nextAll('div[id^=rate_' + catId + ']').each(function() {
            update_ids($(this), 0, -1, TYPE_RATE)
        });
        element.remove();
        // update rate count
        update_rate_count(catId, -1)
    }
    return true;
}

function update_rate_count(catId, n) {
    var nrate = $('input[id^=nrate_' + catId + ']');
    nrate.val(parseInt(nrate.val()) + n);
}

function move_rate(element, up) {
    // select destination
    var destRow;
    if (up) {
        destRow = element.prev('.rate');
    } else {
        destRow = element.next('.rate');
    }

    if (destRow.length > 0) {
        // find all inputs in source and destination
        var source = element.find('input[type=text]');
        var dest = destRow.find('input[type=text]');

        // copy information from source to destination and vice-versa
        for (i = 0; i < source.length; i++) {
            var src = source.get(i);
            var dst = dest.get(i);
            var tmp = dst.value;

            dst.value = src.value;
            src.value = tmp;
        }
    }
}

function update_ids(element, coff, roff, type) {
    // gat category and rate ids
    var tmp = element.attr('id').split('_');
    var catId = parseInt(tmp[1]) + coff;
    var rateId = parseInt(tmp[2]) + roff;
    // by default, id is only category id
    var id = catId;
    if (!isNaN(rateId)) {
        id += '_' + rateId;
    }

    // update the element id
    element.attr('id', type + '_' + id);
    // update name input
    update_input(element, 'name', type + '_name_', id);
    // update price inputs
    update_input(element, 'name', type + '_price_', id);

    if (type == TYPE_CATEGORY) {
        // update rate count
        update_nrate_category(element, catId);
        // update all rates inside the category
        element.find('.rate').each(function() {
            update_ids($(this), coff, 0, TYPE_RATE)
        });
    }

}

function update_nrate_category(element, catId) {
    var nrate = element.find('input[id^=nrate_');
    nrate.attr('id', 'nrate_' + catId);
    nrate.attr('name', 'nrate_' + catId);
}

function update_input(element, attr, name, id) {
    update_tag(element, 'input', attr, name, id);
}

function update_tag(element, tag, attr, name, id) {
    // iterate on all tags that match
    element.find(tag + '[' + attr + '^=' + name).each(function(index) {
        // check if it is a price input
        var tmp = id;
        if (name.indexOf('price') > -1) {
            // if id is empty, go and recreate it
            if (tmp === "") {
                var info = $(this).attr('name').split('_');
                tmp += info[2];
                if (info[0] == 'rate') {
                    tmp += '_' + info[3];
                }
            }
            // add position at the end
            tmp += '_' + index;
        }
        // update attribute
        $(this).attr(attr, name + tmp);
    });
}
