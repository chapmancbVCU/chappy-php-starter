/**
 * Helper JavaScript file for formatting phone numbers.
 * @author Chad Chapman
 */

/**
 * Function that receives number as an input and formats it based on the 
 * length.  For strings less than three it does nothing.  For values less 
 * than 7 you get a formatted string that looks like xxx-xxx.  If the input 
 * string is more than 7 then the formatted string looks like xxx-xxx-xx.  
 * 
 * @param {string} value 
 * @returns The properly formatted string based on length.
 */
function formatPhoneNumber(value) {
    // If input is falsy eg if the user deletes the input, then just return.
    if(!value) return value;

    // Clean the input for any non-digit values.
    const phoneNumber = value.replace(/[^\d]/g, '');

    /* phoneNumberLength is used to know when to apply or formatting for the
       phone number. */
    const phoneNumberLength = phoneNumber.length;

    /* We need to return the value with no formatting if its less than four
       digits.  This is to avoid weird behavior that occurs if you format
       the area code too early. */
    if(phoneNumberLength < 4) {
        return phoneNumber;
    }

    /* If phoneNumberLength is greater than 4 and less than 7 we start to
       return the formatted number. */
    if(phoneNumberLength < 7) {
        return `${phoneNumber.slice(0,3)}-${phoneNumber.slice(3)}`;
    }

    /* Finally, if the phoneNumberLength is greater than seven, we add the 
       last bit of formatting and return it. */
    return `${phoneNumber.slice(0,3)}-${phoneNumber.slice(
        3,
        6,
    )}-${phoneNumber.slice(6,9)}`;
}

/**
 * Uses getElementbyId('cell_phone') to get phone number input from webpage.  
 * The way this function works is we grab the value of what the user is typing 
 * into the input.  Next we format the value and set the value of the input 
 * field in the html document.
 */
function cellPhoneNumberFormatter() {
    const inputField = document.getElementById('cell_phone');
    const formattedInputValue = formatPhoneNumber(inputField.value);
    inputField.value = formattedInputValue;
}

/**
 * Uses getElementbyId('home_phone') to get phone number input from webpage.  
 * The way this function works is we grab the value of what the user is typing 
 * into the input.  Next we format the value and set the value of the input 
 * field in the html document.
 */
function homePhoneNumberFormatter() {
    const inputField = document.getElementById('home_phone');
    const formattedInputValue = formatPhoneNumber(inputField.value);
    inputField.value = formattedInputValue;
}

/**
 * Uses getElementbyId('home_phone') to get phone number input from webpage.  
 * The way this function works is we grab the value of what the user is typing 
 * into the input.  Next we format the value and set the value of the input 
 * field in the html document.
 */
function workPhoneNumberFormatter() {
    const inputField = document.getElementById('work_phone');
    const formattedInputValue = formatPhoneNumber(inputField.value);
    inputField.value = formattedInputValue;
}