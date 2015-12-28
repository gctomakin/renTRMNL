function successMessage(message) {
    new PNotify({
        title: 'Success!!',
        text: message,
        type: 'success'
    });
}

function errorMessage(message) {
    new PNotify({
        title: 'Failed!!',
        text: message,
        type: 'error'
    });
}