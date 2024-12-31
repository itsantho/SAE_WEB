/*=============== SHOW MENU ===============*/


const showMenu = (toggleId, navId) =>{
    const toggle = document.getElementById(toggleId),
        nav = document.getElementById(navId),
        iconImage = document.querySelector('.icon'),
        signInElement = document.querySelector('.signIn');



    toggle.addEventListener('click', () =>{
        // Add show-menu class to nav menu
        nav.classList.toggle('show-menu')

        // Add show-icon to show and hide the menu icon
        toggle.classList.toggle('show-icon')
        // Toggle the visibility of the icon image
        if (iconImage.style.display === 'none') {
            iconImage.style.display = 'block';
        } else {
            iconImage.style.display = 'none';
        }

        // Toggle the visibility of the signIn element
        if (signInElement.style.display === 'none') {
            signInElement.style.display = 'block';
        } else {
            signInElement.style.display = 'none';
        }

    })
}

showMenu('nav-toggle','nav-menu')
