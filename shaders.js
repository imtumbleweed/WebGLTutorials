/*
 CC3.0 Distribution License

 NOTE: This software is released under CC3.0 creative commons
 If using in own projects please give credit to the original author,

 * By linking to my tutorial site:

 * http://www.webgltutorials.org

 * Thanks :-)

 */

// Set to true once all shaders finished loading asyncronously
window.ShadersFinishedLoading = false;

class ShaderProgramManager { // Globally available shader programs
    constructor() {
        this.standardProgram = null;            // Draw static point in the middle
        this.globalDrawingProgram = null;       // Draw point defined by global parameters
        this.vertexColorProgram = null;            // Draw static point in the middle
        this.textureProgram = null;
        this.lightProgram = null;
        this.moveProgram = null;
    }
}

var Shader = new ShaderProgramManager();        // Create shader program manager

var a_Position = null;

function CreateShaderPrograms(gl) {

    // Draw point at x = 0, y = 0, z = 0
    var v = 'void main() { gl_Position = vec4(0.0, 0.0, 0.0, 1); gl_PointSize = 10.0; }';
    var f = 'void main() { gl_FragColor = vec4(1.0, 0.0, 0.0, 1.0); }'; // Red
    Shader.standardProgram = InitializeShader(gl, v, f);

    // Draw a point at an arbitrary location, determined globally by the JavaScript application
    v = 'attribute vec4 a_Position; void main() { gl_Position = a_Position; gl_PointSize = 10.0; }';
    f = 'void main() { gl_FragColor = vec4(0.0, 1.0, 0.0, 1.0); }'; // Green
    Shader.globalDrawingProgram = InitializeShader(gl, v, f);
}

var shaders = [ // Enumerate shader filenames
    "standard", // this assumes "standard.vs" & "standard.frag" are available in "shaders" directory
    "global",   // this assumes "global.vs" & "global.frag" are available in "shaders" directory
    "vertex",
    "texture",
    "move",
    "light",
    "directional",
    "point",
    "sprite",
    "spritesheet"
];

var shader_name = [ // Enumerate shader program names
    "standardProgram",
    "globalDrawingProgram",
    "vertexColorProgram",
    "textureMapProgram",
    "moveProgram",
    "lightProgram",
    "directionalProgram", // directional light
    "pointProgram",       // point light
    "spriteProgram",
    "spritesheetProgram"
];

// Scroll through the list, loading shader pairs
function CreateShadersFromFile( gl ) {

    var cache_Bust = new Date().getTime()/1000|0;

    for (i in shaders)
        LoadShader(gl, shader_name[i], shaders[i] + ".vs?v=" + cache_Bust, shaders[i] + ".frag?v=" + cache_Bust,
            i // pass in the index of the currently loading shader,
              // this way we can determine when last shader has finished loading
        );
}

function LoadShader(gl, shaderName, filenameVertexShader, filenameFragmentShader, index)
{
    // Folder where your shaders are located
    var ShaderDirectory = "shaders";

    var filename_vs = ShaderDirectory + "/" + filenameVertexShader;
    var filename_fs = ShaderDirectory + "/" + filenameFragmentShader;

    var v = ""; // Placeholders for the shader pair
    var f = "";

    // Now execute two Ajax calls in a row to grab the vertex and
    // fragment shaders from the file location

    var xmlhttp = new XMLHttpRequest();

    // Execute first Ajax call to load the vertex shader
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == XMLHttpRequest.DONE) {
            if (xmlhttp.status == 200) {

                v = xmlhttp.responseText;

                // Execute second Ajax call to load the fragment shader
                var xmlhttp2 = new XMLHttpRequest();
                xmlhttp2.onreadystatechange = function () {
                    if (xmlhttp2.readyState == XMLHttpRequest.DONE)
                        if (xmlhttp2.status == 200) {

                            f = xmlhttp2.responseText;

                            Shader[ shaderName ] = InitializeShader(gl, v, f, filenameVertexShader, filenameFragmentShader);
                            // Is this the last shader in the queue?
                            // If so, execute "all shaders loaded" event
                            if (index == shaders.length - 1) {

                                setTimeout(function () {

                                    window.ShadersFinishedLoading = true;

                                }, 500); // .5 sec delay
                            }
                        }
                };
                xmlhttp2.open("GET", filename_fs, true);
                xmlhttp2.send();
            }
        }
    };
    xmlhttp.open("GET", filename_vs, true);
    xmlhttp.send();
}

function InitializeShader(gl, source_vs, source_frag, fv, ff)
{
    ErrorMessage = "Initializing Shader Program: <" + fv + ">, <" + ff + ">";

    var shader_vs = gl.createShader(gl.VERTEX_SHADER);
    var shader_frag = gl.createShader(gl.FRAGMENT_SHADER);

    gl.shaderSource(shader_vs, source_vs);
    gl.shaderSource(shader_frag, source_frag);

    gl.compileShader(shader_vs);
    gl.compileShader(shader_frag);

    var error = false;

    // Compile vertex shader
    if (!gl.getShaderParameter(shader_vs, gl.COMPILE_STATUS)) {
        ErrorMessage += gl.getShaderInfoLog(shader_vs);
        error = true;
    }

    // Compile fragment shader
    if (!gl.getShaderParameter(shader_frag, gl.COMPILE_STATUS)) {
        ErrorMessage += gl.getShaderInfoLog(shader_frag);
        error = true;
    }

    // Create shader program consisting of shader pair
    program = gl.createProgram();

    var ret = gl.getProgramInfoLog(program);

    if (ret != "")
        ErrorMessage += ret;

    // Attach shaders to the program; these methods do not have a return value
    gl.attachShader(program, shader_vs);
    gl.attachShader(program, shader_frag);

    // Link the program - returns 0 if an error occurs
    if (gl.linkProgram(program) == 0) {
        ErrorMessage += "\r\ngl.linkProgram(program) failed with error code 0.";
        error = true;
    }

    if (error)  {
        console.log(ErrorMessage + ' ...failed to initialize shader.');
        return false;
    } else {
        console.log(ErrorMessage + ' ...shader successfully created.');
        return program; // Return created program
    }
}