
var email = document.forms["vform"]["email"];
var email_error = document.getElementById("email_error");

var password = document.forms["vform2"]["password"];
var password_confirmation = document.forms["vform2"]["password_confirmation"];

var password_error = document.getElementById("password_error");
var password_confirmation_error = document.getElementById("password_confirmation_error");

email.addEventListener("blur", emailVerify, true);
password.addEventListener("blur", passwordVerify, true);
password_confirmation.addEventListener("blur", matchVerify, true);


function Validate(){

    if(email.value ==""){
        email.style.border = "1px solid red";
        email_error.textContent = "Campo de email vacío";
        email.focus();
        return false;
    }

    if(!validateEmail(email.value)){
        email.style.border = "1px solid red";
        email_error.textContent = "El formato de email es incorrecto";
        email.focus();
        return false;
    }

}

function ValidatePass(){

    if(password.value ==""){
        password.style.border = "1px solid red";
        password_error.textContent = "Campo de contraseña vacío";
        password.focus();
        return false;
    }

    if(!validatePassword(password.value)){
        password.style.border = "1px solid red";
        password_error.textContent = "La contraseña debe contener 6-12 carácteres, 1 mayuscula y 1 número";
        password.focus();
        return false;
    }

    if(password.value != password_confirmation.value){
        password.style.border = "1px solid red";
        password_confirmation.style.border = "1px solid red";
        //password_error.textContent= "Las contraseñas no coinciden";
        return false;
    }
}

function passwordVerify(){
    if(password.value!="" && validatePassword(password.value)){
        password.style.border="1px solid #5E6E66";
        password_error.innerHTML = "";
        return true;
    }
    else{
        if(password.value==""){
            password_error.innerHTML= "Este campo está vacío";
        }
        else{
            password_error.textContent = "La contraseña debe contener 6-12 carácteres, 1 mayuscula y 1 número";
        }
        return false;
    }
}

function matchVerify(){
    if(password_confirmation.value!="" && password_confirmation.value==password.value){
        password.style.border="1px solid #5E6E66";
        password_confirmation_error.innerHTML = "";
        return true;
    }
    else{
        if(password_confirmation.value=""){
            password_confirmation_error.innerHTML = "Este campo está vacío";
        }
        else{
            password_confirmation_error.textContent = "Las contraseñas no coinciden";
        }
        return false;
    }

}

function validatePassword(password){
    var letter = /[a-zA-Z]/;
    var number = /[0-9]/;
    if (password.length < 6 || password.length > 12 || !letter.test(password) || !number.test(password)) {
        return false;
    }
    return true;
}

function emailVerify(){
    if(email.value!="" && validateEmail(email.value)){
        email.style.border="1px solid #5E6E66";
        email_error.innerHTML = "";
        return true;
    }
    else{
        if(email.value==""){
            email_error.innerHTML="Este campo está vacío";
        }
        else{
            email_error.textContent = "El formato de email es incorrecto";
        }
        return false;
    }
}


function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}


