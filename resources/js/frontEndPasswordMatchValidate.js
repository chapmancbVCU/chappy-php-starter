/**
 * Helper JavaScript file for enforcing matching passwords on the front end.
 * @author Chad Chapman
 */

/******************************************************************************
 * GLOBAL VARIABLES
 *****************************************************************************/
let password = document.getElementById("password");
let confirmPassword = document.getElementById("confirm");

/**
 * This function enforces requirement that new passwords must match.  If 
 * passwords do not match then a message is displayed to the user.
 */
function validatePassword() {
    if(password.value != confirmPassword.value) {
        confirmPassword.setCustomValidity("Passwords Don't Match")
    } else {
        confirmPassword.setCustomValidity("");
    }
}

password.onchange = validatePassword;
confirmPassword.onkeyup = validatePassword;