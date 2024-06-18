
function validateLetters(input) {
    //input.value = input.value.replace(/[^a-zA-Z]/g, '');
    //input.value = input.value.replace(/[^a-zA-Z\s]/g, '');
    input.value = input.value.replace(/[^a-zA-ZñÑ\s]/g, '');
}
