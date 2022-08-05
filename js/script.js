/**
 * Create a button tag
 * @param {string} title Title
 * @param {string} cls Class name
 * @param {string} css 
 * @param {*} func Callback function
 * @returns 
 */
function createButton(title, cls, css, func) {
    let t = document.createElement("button");
    t.innerHTML = title;
    t.className = cls;
    t.style.cssText = css;
    t.type = "button";
    t.onclick = func;
    return t;
}

jQuery(document).ready(function ($) {
    $('#bulk-action-selector-top,#bulk-action-selector-bottom').on('change', function () {
        if (this.value == 'change-datetime') {
            $('#change_datetime').show();
        } else {
            $('#change_datetime').hide();
        }
    });
});