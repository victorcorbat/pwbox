var username = document.forms["vform"]["username"];
var password = document.forms["vform"]["password"];

var username_error = document.getElementById("username_error");
var password_error = document.getElementById("password_error");


username.addEventListener("blur", usernameVerify, true);
password.addEventListener("blur", passwordVerify, true);

function Validate(){

    if(username.value.includes('@')){
        if(username.value ==""){
            email.style.border = "1px solid red";
            email_error.textContent = "Campo de email vacío";
            email.focus();
            return false;
        }

        if(!validateEmail(username.value)){
            email.style.border = "1px solid red";
            email_error.textContent = "El formato de email es incorrecto";
            email.focus();
            return false;
        }
    }

    else{
        if(username.value ==""){
            username.style.border = "1px solid red";
            username_error.textContent = "Nombre de usuario vacío";
            username.focus();
            return false;
        }

        if(username.value.length>20){
            username.style.border = "1px solid red";
            username_error.textContent = "El usuario puede contener 20 carácteres como máximo";
            username.focus();
            return false;
        }
    }

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

}

function usernameVerify(){

    if(username.value.includes('@')){
        if(username.value!="" && validateEmail(username.value)){
            username.style.border="1px solid #5E6E66";
            username_error.innerHTML = "";
            return true;
        }
        else{
            if(username.value==""){
                username_error.innerHTML = "Este campo está vacío";
            }
            else{
                username_error.textContent = "El formato de email es incorrecto";
            }
            return false;
        }
    }

    if(username.value!="" && username.value.length<20){
        username.style.border="1px solid #5E6E66";
        username_error.innerHTML = "";
        return true;
    }
    else{
        if(username.value==""){
            username_error.innerHTML = "Este campo está vacío";
        }
        else{
            username_error.textContent = "El usuario puede contener 20 carácteres como máximo";
        }
        return false;
    }
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

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function validatePassword(password){
    var letter = /[a-zA-Z]/;
    var number = /[0-9]/;
    if (password.length < 6 || password.length > 12 || !letter.test(password) || !number.test(password)) {
        return false;
    }
    return true;
}



