/**
 * Author: Mostafa
 * Student Number: 400599915
 * Date Created: 2025/04/24
 * Description: Javascript for login
 */

document.addEventListener("DOMContentLoaded", () => {
    const passwordInput = document.getElementById("password");
    const eyeOpen = document.querySelector(".eye-open");
    const eyeClosed = document.querySelector(".eye-closed");

    function togglePasswordVisibility() {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeOpen.style.display = "none";
            eyeClosed.style.display = "block";
        } else {
            passwordInput.type = "password";
            eyeOpen.style.display = "block";
            eyeClosed.style.display = "none";
        }
    }

    // Click handlers
    eyeOpen.addEventListener("click", togglePasswordVisibility);
    eyeClosed.addEventListener("click", togglePasswordVisibility);

    const inputForm = document.querySelector('.inputForm');
    passwordInput.addEventListener('focus', () => {
        inputForm.style.borderColor = '#4CAF50';
    });
    passwordInput.addEventListener('blur', () => {
        inputForm.style.borderColor = '#ecedec';
    });
    const messages = document.querySelectorAll('.message-container');
    messages.forEach(msg => {
        setTimeout(() => {
            msg.style.transition = 'opacity 0.5s ease-out';
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 500);
        }, 5000);
    });

    // Clear error states on input
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('focus', function () {
            this.closest('.inputForm').classList.remove('error', 'success');
        });
    });
});