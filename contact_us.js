// Initialize EmailJS with your Public API Key
emailjs.init('vliZziFLYEJhOpR7M');

document.getElementById('contact-form').addEventListener('submit', function (event) {
    event.preventDefault();

    const templateParams = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        subject: document.getElementById('subject').value,
        message: document.getElementById('message').value,
    };

    emailjs.send('service_hdgj2u4', 'template_hl57vf9', templateParams)
        .then(function (response) {
            document.getElementById('status').innerHTML = 'Message sent successfully!';
        }, function (error) {
            document.getElementById('status').innerHTML = 'Failed to send message. Please try again.';
        });

    document.getElementById('contact-form').reset();
});

// Logout function
function logout() {
    // Redirect to logout page or perform logout action
    window.location.href = 'logout.php';
}
