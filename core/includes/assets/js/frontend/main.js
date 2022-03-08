// Language: Native javascript ES6
// Front end main js file

pixelTooltipFollow = (el) => {

    // Get location of element
    let elLocation = el.getBoundingClientRect();


    // Align this pixel-tooltip-content to the center of the element
    let tooltipContent = el.querySelector('.pixel-tooltip-content');
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