// Language: Native javascript ES6
// Path: core\includes\assets\js\frontend-scripts.js

pixelTooltipFollow = (el) => {

    // Get location of cursor
    let cursorX = event.clientX;
    let cursorY = event.clientY;

    // Get child element of tooltip
    let tooltipContent = el.querySelector('.pixel-tooltip-content');

    // Change location of tooltip
    tooltipContent.style.left = cursorX + 'px';
    tooltipContent.style.top = cursorY + 'px';

    // update tooltip position when mouse moves
    document.addEventListener('mousemove', () => {
        tooltipContent.style.left = event.clientX + 'px';
        tooltipContent.style.top = event.clientY + 'px';
    });

};