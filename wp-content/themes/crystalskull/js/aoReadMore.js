
function initReadMore(elementId, togglerId, collapsedHeight)
{
    var el  = document.getElementById(elementId);
    var btn = document.getElementById(togglerId);

    var collapsedHeightStyle = collapsedHeight + "px";

    if (el.scrollHeight > collapsedHeight) {
        btn.style.display = "block";
        el.style.height = collapsedHeightStyle;

        btn.onclick = function() {
            if (el.style.height==collapsedHeightStyle) {
                el.style.height = "100%";
                btn.text = "Show less";
            } else {
                el.style.height = collapsedHeightStyle;
                btn.text = "Show more";
            }
        }
    }
}
