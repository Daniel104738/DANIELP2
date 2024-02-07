function submitForm() {
    const name = document.getElementById("contact-form").name.value;
    const email = document.getElementById("contact-form").email.value;
    const message = document.getElementById("contact-form").message.value;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "enviar.php");
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Show modal
            document.getElementById("modal-container").classList.remove("hidden");
        } else {
            // Show error message
            alert("Error al enviar el mensaje.");
        }
    };
    xhr.send(`name=${name}&email=${email}&message=${message}`);
}

function closeModal() {
    document.getElementById("modal-container").classList.add("hidden");
}
