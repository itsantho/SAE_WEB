document.addEventListener('DOMContentLoaded', function () {
    // Vérification des champs obligatoires et gestion de la soumission
    document.getElementById("valider_button").addEventListener("click", function (e) {
        e.preventDefault(); // Empêche la soumission automatique du formulaire

        const form = document.getElementById("form_adherent");

        // Vérifier si la case à cocher des mentions est cochée
        const mentionCheckbox = document.getElementById("mention");
        if (!mentionCheckbox.checked) {
            alert("Vous devez accepter les conditions avant de valider !");
            return;
        }

        // Vérification des champs obligatoires
        const requiredFields = form.querySelectorAll("input[required], textarea[required]");
        let isValid = true;
        let errorMessage = "";

        requiredFields.forEach((field) => {
            if (!field.value.trim()) {
                isValid = false;
                errorMessage += `Le champ "${field.placeholder || field.name}" est obligatoire.\n`;
            }
        });

        if (!isValid) {
            alert(errorMessage);
            return;
        }

        // Soumission avec fetch
        const formData = new FormData(form);
        fetch(form.action, {
            method: form.method,
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.status === "success") {
                    // Redirection vers identification.html
                    window.location.href = "identification.html";
                } else {
                    // Afficher les erreurs retournées par le serveur
                    alert(data.errors.join("\n"));
                }
            })
            .catch((error) => {
                console.error("Erreur lors de la soumission :", error);
                alert("Une erreur s'est produite. Veuillez réessayer.");
            });
    });
});

const showMenu = (toggleId, navId) =>{
    const toggle = document.getElementById(toggleId),
        nav = document.getElementById(navId)

    toggle.addEventListener('click', () =>{
        // Add show-menu class to nav menu
        nav.classList.toggle('show-menu')

        // Add show-icon to show and hide the menu icon
        toggle.classList.toggle('show-icon')
    })
}

showMenu('nav-toggle','nav-menu')