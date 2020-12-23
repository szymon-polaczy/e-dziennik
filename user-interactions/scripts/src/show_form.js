const add_form = document.getElementById('add-form');
const btn_show_add_form = document.getElementById('btn-show-add-form');
const btn_hide_add_form = document.getElementById('btn-hide-add-form');

btn_show_add_form.addEventListener('click', () => {
  console.log('Toggle show-add-form');
  add_form.classList.toggle('show-add-form');
});

btn_hide_add_form.addEventListener('click', () => {
  console.log('Remove show-add-form');
  add_form.classList.remove('show-add-form');
});