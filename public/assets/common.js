/**Show toast */
function showToast(message) {
    $.toast({
        heading: "success",
        text: message,
        position: "top-right",
        loaderBg: "#4CAF50",
        icon: "success",
        hideAfter: 3500,
    });
}
function closeToast() {
    $("#successToast").toast("hide");
}
