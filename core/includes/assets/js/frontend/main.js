// Language: Native javascript ES6
// Front end main js file

pixelTooltipOpen = (el) => {

    // Get location of element
    let elLocation = el.getBoundingClientRect();

    // Get el attribute for data-tooltip-id
    let tooltipId = el.getAttribute('data-tooltip-id');

    // Get element with attribute tooltip-content-id 
    let tooltipContent = document.querySelector('#tooltip-id-' + tooltipId);

    // Add class tooltip-active to tooltip-content-id
    tooltipContent.classList.add('tooltip-active');



    // Align this pixel-tooltip-content to the center of the element
    tooltipContent.style.left = `${elLocation.left + (elLocation.width / 2) - (tooltipContent.offsetWidth / 2)}px`;

    // If tooltip is below the screen, move it up to above the element
    if (elLocation.top < (window.innerHeight / 2)) {
        tooltipContent.style.top = `${elLocation.top + elLocation.height + 10}px`;
    } else {
        tooltipContent.style.top = `${elLocation.top - tooltipContent.offsetHeight - 10}px`;
    }


    // If tooltip content window is wider that the screen width, compensate for that
    if (tooltipContent.offsetWidth > window.innerWidth) {
        tooltipContent.style.width = `${window.innerWidth * 0.9}px`;
    } else if (tooltipContent.offsetLeft < 0) {
        tooltipContent.style.left = `${0}px`;
    } else if (tooltipContent.offsetLeft + tooltipContent.offsetWidth > window.innerWidth) {
        tooltipContent.style.left = `${window.innerWidth - tooltipContent.offsetWidth}px`;
    }


};



pixelTooltipKeepOpen = (tooltipContent) => {
    tooltipContent.classList.add('tooltip-active');
};

// Pixel Tooltip close
pixelTooltipClose = (tooltipContent) => {
    tooltipContent.classList.remove('tooltip-active');
};


pixelTooltipResetPosition = (tooltipContent, tooltipclearInterval = false) => {
    if (tooltipclearInterval == true) {
        clearInterval(pixelTooltipResetPosition);
    } else {
        // Remove inline styles after 1 second
        let pixelTooltipResetPosition = setTimeout(() => {
            tooltipContent.removeAttribute('style');
        }, 1000);
    }
};




// On document ready
document.addEventListener('DOMContentLoaded', () => {

    // onMouseOver of any element with attribute data-tooltip-id
    document.querySelectorAll('.pixel-tooltip-term').forEach(pixelTooltipTerm => {

        pixelTooltipTerm.addEventListener('mouseover', () => {
            pixelTooltipOpen(pixelTooltipTerm);
        });

        pixelTooltipTerm.addEventListener('mouseleave', () => {
            let tooltipId = pixelTooltipTerm.getAttribute('data-tooltip-id');
            let tooltipContent = document.querySelector('#tooltip-id-' + tooltipId);
            pixelTooltipClose(tooltipContent);
        });

    })

    // onMouseOver of any element with class pixel-tooltip-content
    document.querySelectorAll('.pixel-tooltip-content').forEach(pixelTooltipContent => {

        pixelTooltipContent.addEventListener('mouseover', () => {
            pixelTooltipKeepOpen(pixelTooltipContent);
        });

        pixelTooltipContent.addEventListener('mouseleave', () => {
            pixelTooltipClose(pixelTooltipContent);
            // pixelTooltipResetPosition(pixelTooltipContent);
        });

    });


});