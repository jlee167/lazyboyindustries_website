/**
 * Returns Cookie specified by key
 *
 * @param {String} key
 */
export function getCookie (key) {
    cookies = decodeURIComponent(document.cookie).split(';');

    for (let i = 0; i < cookies.length; i++) {
        cookie_item = cookies[i].trim();
        if (cookie_item.includes(String(key))) {
            result = cookie_item.split(String(key)).trim();
        }
    }
    return result;
}
