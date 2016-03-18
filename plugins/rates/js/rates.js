function add_rate(element) {
  var tmp = element.attr('id').split('_');
  var catId = parseInt(tmp[1]);
  var rateId = parseInt(tmp[2]);

  var newRow = element.clone();
  newRow.find('input[type=text]').val('');
  element.after(newRow);

  element.nextAll('tr[id^=rate_' + catId + ']').each(update_positions_up);

  return true;
}


function delete_rate(element) {
  var tmp = element.attr('id').split('_');
  var catId = parseInt(tmp[1]);
  var rateId = parseInt(tmp[2]);

  var test = element.find('input[id^=name_');

  if (rateId == 0 && !$('#rate_' + catId + '_' + (rateId + 1)).length) {
    // Only one rate, just empty it
    element.find('input[id^=name_').val('');
    element.find('input[id^=value_').val('');
  } else {
    element.nextAll('tr[id^=rate_' + catId + ']').each(update_positions_down);
    $('#rate_' + catId + '_' + rateId).remove();
  }
  return true;
}


function update_positions_down() {
  update_positions($(this), false);
}

function update_positions_up() {
  update_positions($(this), true);
}

function update_positions(element, up) {
  var tmp = element.attr('id').split('_');
  var catId = parseInt(tmp[1]);
  var rateId = parseInt(tmp[2]);
  if (up) {
    rateId += 1;
  } else {
    rateId -= 1;
  }
  element.attr('id', 'rate_' + catId + '_' + rateId);
  update_child(element, 'name_', 'id', catId, rateId);
  update_child(element, 'name_', 'name', catId, rateId);
  update_child(element, 'value_', 'id', catId, rateId);
  update_child(element, 'value_', 'name', catId, rateId);
}

function update_child(element, id, attr, catId, rateId) {
  element.find('input[id^=' + id).attr(attr, id + catId + '_' + rateId);
}
