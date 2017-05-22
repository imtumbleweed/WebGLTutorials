window.clicked = false;
var MouseControls = function()
{
    this.x = 0;
    this.y = 0;
    var that = this;
    this.Initialize = function(element)
    {
        $(element).on("mousemove", function(event) {
            that.x = event.pageX - $(element).offset().left;
            that.y = event.pageY - $(element).offset().top;
        });
        $(element).on("click", function(e) {
            if (!e) var e = event;
            e.preventDefault();
            that.x = e.clientX - $(element).offset().left;
            that.y = e.clientY - $(element).offset().top;
            window.clicked = true;
        });
    }
}

window.Mouse = null;

function InitializeMouse()
{
    window.Mouse = new MouseControls();
}