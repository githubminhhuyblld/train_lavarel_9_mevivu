
/**Show toast */
function showToast(message) {
    var toast = document.querySelector(".toast");
    var toastMessage = toast.querySelector("#toastMessage");
    toastMessage.textContent = message;
    $('#successToast').toast('show');
}