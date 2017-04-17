function InitializeShader(gl, source_vs, source_frag)
{
    var shader_vs = gl.createShader(gl.VERTEX_SHADER);
    var shader_frag = gl.createShader(gl.FRAGMENT_SHADER);

    gl.shaderSource(shader_vs, source_vs);
    gl.shaderSource(shader_frag, source_frag);

    gl.compileShader(shader_vs);
    gl.compileShader(shader_frag);

    var error = false;

    if (!gl.getShaderParameter(shader_vs, gl.COMPILE_STATUS)) {
        alert("An error occurred compiling the shaders: " + gl.getShaderInfoLog(shader_vs));
        error = true;
    }

    if (!gl.getShaderParameter(shader_frag, gl.COMPILE_STATUS)) {
        alert("An error occurred compiling the shaders: " + gl.getShaderInfoLog(shader_frag));
        error = true;
    }

    // Create shader program consisting of shader pair
    program = gl.createProgram();

    // Attach shaders to the program;
    gl.attachShader(program, shader_vs);
    gl.attachShader(program, shader_frag);

    // Link the program
    if (gl.linkProgram(program) == 0) {
        console.log("gl.linkProgram(program) failed with error code 0");
        error = true;
    }

    if (error) {
        console.log("Failed to initialize shader.");
        return false;
    }

    console.log("Shader successfully created.");

    return program;
}