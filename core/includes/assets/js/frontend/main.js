// Language: Native javascript ES6
// Front end main js file

pixelTooltipFollow = (el) => {

    // Get location of element
    let elLocation = el.getBoundingClientRect();

    // Get el attribute for data-tooltip-id
    let tooltipId = el.getAttribute('data-tooltip-id');

    // Get element with attribute tooltip-content-id 
    let tooltipContent = document.querySelector('#tooltip-id-' + tooltipId);

    // On hover of element toggle class tooltip-active and remove when mouse leaves
    el.addEventListener('mouseenter', () => {
        tooltipContent.classList.add('tooltip-active');
    });

    el.addEventListener('mouseleave', () => {
        tooltipContent.classList.remove('tooltip-active');
    });

    tooltipContent.addEventListener('mouseenter', () => {
        tooltipContent.classList.add('tooltip-active');
    });

    tooltipContent.addEventListener('mouseleave', () => {
        tooltipContent.classList.remove('tooltip-active');
    });


    // Align this pixel-tooltip-content to the center of the element
    tooltipContent.style.left = `${elLocation.left + (elLocation.width / 2) - (tooltipContent.offsetWidth / 2)}px`;

    // If tooltip is below the screen, move it up to above the element
    if (elLocation.top < (window.innerHeight / 2)) {
        tooltipContent.style.top = `${elLocation.top + elLocation.height + 10}px`;
    } else {
        tooltipContent.style.top = `${elLocation.top - tooltipContent.offsetHeight - 10}px`;
    }

    // If tooltip content window is off the screen, compensate for that
    if (tooltipContent.offsetLeft < 0) {
        tooltipContent.style.left = `${0}px`;
    } else if (tooltipContent.offsetLeft + tooltipContent.offsetWidth > window.innerWidth) {
        tooltipContent.style.left = `${window.innerWidth - tooltipContent.offsetWidth}px`;
    }

    // If tooltip content window is wider that the screen width, compensate for that
    if (tooltipContent.offsetWidth > window.innerWidth) {
        tooltipContent.style.width = `${window.innerWidth * 0.9}px`;
    }

};