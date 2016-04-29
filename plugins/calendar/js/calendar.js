function change_form_action(form, value){
  form.find("input[name=action]").val(value);
  form.submit();
}
