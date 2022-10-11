import UnknownException from '../../exceptions/generic/unknown';


/**
 * Sends a new customer support request
 *
 * @return  {null}
 */
window.submitRequest = function() {

    /* Get user input from the form */
    const typeSel = document.getElementById("type");
    const type = typeSel.options[typeSel.selectedIndex].value;
    const text = window.supportApp.requestContent;
    const contact = document.getElementById("email").value;


    fetch('/api/support_request', {
        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        },

        body: JSON.stringify({
            type: String(type),
            contents: text,
            contact: contact
        })
    })
    .then(response => {
        if (response.status === 200) {
            window.alert("Your request has been submitted!");
            document.getElementById("email").value = new String();
            $("#summernote").summernote("reset");
        } else {
            throw new UnknownException();
        }
    })
    .catch(err => {
        window.alert(`${err.name}: ${err.message}`);
    })
}
