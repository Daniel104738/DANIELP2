function submitForm() {
    const name = document.getElementById("contact-form").name.value;
    const email = document.getElementById("contact-form").email.value;
    const message = document.getElementById("contact-form").message.value;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "enviar.php");
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Show success message with SweetAlert2
            Swal.fire({
                title: "¡Mensaje enviado!",
                text: "Gracias por ponerte en contacto. Nos pondremos en contacto contigo pronto.",
                icon: "success",
                confirmButtonText: "Cerrar",
            });

            // Clear form fields
            document.getElementById("contact-form").reset();
        } else {
            // Show error message with SweetAlert2 (optional)
            // You can implement an error message here
        }
    };
    xhr.send(`name=${name}&email=${email}&message=${message}`);
}
