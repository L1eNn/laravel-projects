if (document.querySelectorAll('.good').length) {
    let images = document.querySelectorAll('.images');

    let moreButton = document.querySelector('#moreButton');
    let actions = document.querySelector('.actions');
    let actionsActive = false

    let deleteButton = document.querySelector('#deleteButton');

    images.forEach((image) => {
        image.addEventListener('click', () => {
             image.parentElement.parentElement.querySelector('.coverImage').src = image.src
        });
    })


    moreButton.addEventListener('click', () => {
        actionsActive = !actionsActive
        if (actionsActive) {
            actions.style.display = 'flex'
        } else {
            actions.style.display = 'none'
        }
    })
}
