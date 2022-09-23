/**
 * Convert first letter to uppercase.
 *
 * @param {String} input
 * @returns {String}
 */
function firstCharToUpper(input) {
    return input.charAt(0).toUpperCase() + input.slice(1);
}

export {firstCharToUpper};
