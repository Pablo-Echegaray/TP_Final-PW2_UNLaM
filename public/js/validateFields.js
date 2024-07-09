
function validateLetters(input) {
    input.value = input.value.replace(/[^a-zA-ZñÑ\s]/g, '');
}
