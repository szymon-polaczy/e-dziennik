const add_form_permission = document.getElementById('add-form-permission');
const student_hide_box = document.getElementById('add-form-student-hide-box');
const teacher_hide_box = document.getElementById('add-form-teacher-hide-box');

const add_form_class = document.getElementById("add-form-class");
const add_form_birthday = document.getElementById("add-form-birthdate");
const add_form_room = document.getElementById("add-form-room");

if (add_form_permission != null) {
  add_form_permission.addEventListener("change", () => {
    let value = add_form_permission.options[add_form_permission.selectedIndex].value;

    student_hide_box.style.display = "none";
    teacher_hide_box.style.display = "none";

    add_form_class.required = false;
    add_form_birthday.required = false;
    add_form_room.required = false;

    if (value == "t") {
      teacher_hide_box.style.display = "flex";
      add_form_room.required = true;
    }
    else if (value == "s") {
      student_hide_box.style.display = "flex";
      add_form_class.required = true;
      add_form_birthday.required = true;
    }
  });
}