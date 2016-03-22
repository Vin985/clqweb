function add_category(element) {
  var tmp = element.attr('id').split('_');
  var catId = parseInt(tmp[1]);

  var first = (catId == 0);
  var last = !element.next('table[id^=category_').length;

  var newTable = element.clone();
  newTable.find('tr[id^=rate_]:gt(0)').remove();
  newTable.find('input[type=text]').val('');

  element.after(newTable);

  element.nextAll('table').each(update_cat_up);

  return true;
}

function add_rate(element) {
  var tmp = element.attr('id').split('_');
  var catId = parseInt(tmp[1]);
  var rateId = parseInt(tmp[2]);

  var first = (rateId == 0);
  var last = !element.next('tr[id^=rate_').length;

  var newRow = element.clone();
  newRow.find('input[type=text]').val('');

  if (first) {
    newRow.find('button.up').css('visibility', 'visible');
  }
  if (last) {
    element.find('button.down').css('visibility', 'visible');
  }
  element.after(newRow);
  element.nextAll('tr[id^=rate_' + catId + ']').each(update_rate_up);

  return true;
}

function delete_rate(element) {
  var tmp = element.attr('id').split('_');
  var catId = parseInt(tmp[1]);
  var rateId = parseInt(tmp[2]);

  var first = (rateId == 0);
  var last = !element.next('tr[id^=rate_').length;

  if (first && last) {
    // Only one rate, just empty it
    element.find('input[id^=name_').val('');
    element.find('input[id^=value_').val('');
  } else {
    if (first) {
      element.next().find('button.up').css('visibility', 'hidden');
    }
    if (last) {
      element.prev().find('button.down').css('visibility', 'hidden');
    }
    element.nextAll('tr[id^=rate_' + catId + ']').each(update_rate_down);
    element.remove();
  }
  return true;
}

function move_rate(element, up) {

  var destRow;
  if (up) {
    destRow = element.prev('tr[id^=rate_]');
  } else {
    destRow = element.next('tr[id^=rate_]');
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
  update_ids($(this), -1, 0, 'c');
}

function update_cat_up() {
  update_ids($(this), 1, 0, 'c');
}

function update_rate_cat_down() {
  update_ids($(this), -1, 0, 'r');
}

function update_rate_cat_up() {
  update_ids($(this), 1, 0, 'r');
}

function update_rate_down() {
  update_ids($(this), 0, -1, 'r');
}

function update_rate_up() {
  update_ids($(this), 0, 1, 'r');
}

function update_ids(element, coff, roff, type) {
  var tmp = element.attr('id').split('_');
  var catId = parseInt(tmp[1]) + coff;
  var rateId = parseInt(tmp[2]) + roff;

  if (type == "r") {
    element.attr('id', 'rate_' + catId + '_' + rateId);
    update_input(element, 'name', 'name_', type, catId, rateId);
    update_input(element, 'name', 'value_', type, catId, rateId);
  } else {
    element.attr('id', 'category_' + catId);
    update_input(element, 'name', 'catname_', type, catId, rateId);
    element.find('tr[id^=rate_').each(coff > 0 ?
      update_rate_cat_up : update_rate_cat_down);
  }
}

function update_input(element, attr, id, type, catId, rateId) {
  update_field(element, 'input', attr, id, type, catId, rateId);
}

function update_tr(element, attr, id, type, catId, rateId) {
  update_field(element, 'tr', attr, id, type, catId, rateId);
}

function update_field(element, field, attr, id, type, catId, rateId) {
  var target = element.find(field + '[' + attr + '^=' + id);

  if (type == 'c') {
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
