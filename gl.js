/*
    CC3.0 Distribution License

    NOTE: This software is released under CC3.0 creative commons
    If using in own projects please give credit to the original author,

    * By linking to my tutorial site:

    * http://www.webgltutorials.org

    * Thanks :-)

*/

// Get WebGL context, if standard is not available; fall back on alternatives
function GetWebGLContext( canvas )
{
    // Standard
    return canvas.getContext("webgl") ||

            // Alternative; Safari, others
        canvas.getContext("experimental-webgl") ||

            // Firefox; mozilla
        canvas.getContext("moz-webgl") ||

            // Last resort; Safari, and maybe others
        canvas.getContext("webkit-3d");

    // Note that "webgl" is not available as of Safari version <= 7.0.3
    // So we have to fall back to ambiguous alternatives for it and some other browsers
}

function InitializeWebGL()
{
    // Get a handle to canvas tag
    var canvas = document.getElementById("gl");

    // WebGL rendering context
    var gl = null;

    // Array that will store a list of supported extensions
    var extensions = null;

    // ! used twice in a row to cast object state to a Boolean value
    if (!!window.WebGLRenderingContext == true)
    {
        // Initialize WebGL rendering context, if available
        if ( gl = GetWebGLContext( canvas ) )
        {
            console.log("WebGL is initialized.");

            // Ensure OpenGL viewport is resized to match canvas dimensions
            gl.viewportWidth = 800;//canvas.width;
            gl.viewportHeight = 600;//canvas.height;

            // Output the WebGL rendering context object to console for reference
            console.log( gl );

            // List available extensions
            console.log( extensions = gl.getSupportedExtensions() );

            // Set screen clear color to R, G, B, alpha; where 0.0 is 0% and 1.0 is 100%
            gl.clearColor(0.0, 0.0, 0.0, 1.0);

            // Enable color; required for clearing the screen
            gl.clear(gl.COLOR_BUFFER_BIT);

            // Clear out the viewport with solid black color
            gl.viewport(0, 0, gl.viewportWidth, gl.viewportHeight);

        }
        else
            console.log("Your browser doesn't support WebGL.");
    }
    else
        console.log("WebGL is supported, but disabled :-(");
}

function DrawPoint(gl) {

    console.log("DrawPoint(gl)");

    if (!gl)
        return;

    gl.useProgram( Shader.standardProgram );
    gl.drawArrays(gl.POINTS, 0, 1);
}

function DrawPointUsingGlobalParameters(gl, x, y, z) {

    if (!gl)
        return;

    gl.useProgram( Shader.globalDrawingProgram ); // Use the program

    var a_Position = gl.getAttribLocation(Shader.globalDrawingProgram, 'a_Position');
    //var alpha = gl.getUniformLocation(Shader.globalDrawingProgram, 'u_Alpha');

    if (a_Position < 0)
        console.log("DrawPointUsingGlobalParameters: Failed to get attribute pointer a_Position.");
    else {
        // Pass point coordinates using global attribute a_Position from our JavaScript program
        gl.vertexAttrib3f(a_Position, x, y, z);
        //gl.uniform3f(alpha, 0.2, 0.2, 0.2);
        gl.drawArrays(gl.POINTS, 0, 1);
    }
}

var a = undefined;
function DrawPointAtMousePosition(canvas, gl, e) {

    gl.useProgram( Shader.globalDrawingProgram ); // Use the program

    var x = e.clientX; // Get mouse position coordinates from click event object
    var y = e.clientY;

    console.log("Mouse x=" + x + ", y=" + y); // Output the coordinates to console

    // Get a pointer to a_Position attribute within the vertex shader
    // Note, variable doesn't have to be called 'a_Position'
    
    if (a == undefined)
        a = gl.getAttribLocation(Shader.globalDrawingProgram,  'a_Position');

    if (a < 0)
        console.log("Failed to get attribute pointer a_Position.");
    else {
        // translate mouse coordinates to WebGL coordinate system
        var r = e.target.getBoundingClientRect();
        x = ((x - r.left) - canvas.width / 2) / (canvas.width / 2);
        y = (canvas.height / 2 - (y - r.top)) / (canvas.height / 2);

        // Pass point coordinates using global attribute a_Position from our JavaScript program
        gl.vertexAttrib3f(a, x, y, 0);

        // Erase background
        //gl.clearColor(0.0, 0.0, 0.0, 1.0);
        //gl.clear(gl.COLOR_BUFFER_BIT);

        gl.drawArrays(gl.POINTS, 0, 1);
    }
}