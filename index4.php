<!doctype html>
<html>
<head>
    <title>Tutorial 3 - Load shaders from script tags</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <script src = 'http://www.tigrisgames.com/js/jquery.js' type = 'text/javascript'></script>
    <script src = 'http://www.tigrisgames.com/js/ui.js' type = 'text/javascript'></script>
    <script src = 'http://www.tigrisgames.com/fx/gl.js'></script>
    <script src = 'http://www.tigrisgames.com/fx/shaders.js'></script>

    <!-- Standard vertex shader //-->
    <script type = "glsl" id = "standard-vs">void main() {
            gl_Position = vec4(0.0, 0.0, 0.0, 1);
            gl_PointSize = 10.0;
        }
    </script>
    <!-- Fragment vertex shader //-->
    <script type = "glsl" id = "standard-frag">void main() {
            gl_FragColor = vec4(1.0, 0.0, 0.0, 1.0);
        }
    </script>
    <script type = "text/javascript">

        /* -- Gl functions -- */

        var canvas = null;
        var gl = null;

        $(document).ready(function() {

            var canvas = document.getElementById('gl');

            gl = GetWebGLContext(canvas);

            if (!gl)
                console.log('Failed to set up WebGL.');

            else { // Load a shader from "shaders" folder

                CreateShadersFromFile( gl );
            }
        });

        // An event that fires when all shader resources finish loading in CreateShadersFromFile
        window.webGLResourcesLoaded = function() {

            console.log("webGLResourcesLoaded(): All webGL shaders have finished loading!");

            // Specify triangle vertex data:
            var vertices = [
                -0.0,  0.5, 0.0, // Vertex A (x,y,z)
                -0.5, -0.5, 0.0, // Vertex B (x,y,z)
                 0.5, -0.5, 0.0  // Vertex C (x,y,z)
            ];

            var indices = [0, 1, 2];

            // Create buffer objects for storing triangle vertex and index data
            var vertexbuffer = gl.createBuffer();
            var indexbuffer = gl.createBuffer();

            // Bind and create enough room for our data on respective buffers

            // Bind it to ARRAY_BUFFER
            gl.bindBuffer(gl.ARRAY_BUFFER, vertexbuffer);
            // Send our vertex data to the buffer using floating point array
            gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertices), gl.STATIC_DRAW);
            // We're done; now we have to unbind the buffer
            gl.bindBuffer(gl.ARRAY_BUFFER, null);

            // Bind it to ELEMENT_ARRAY_BUFFER
            gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, indexbuffer);
            // Send index (indices) data to this buffer
            gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(indices), gl.STATIC_DRAW);
            // We're done; unbind, we no longer need the buffer object
            gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, null);

            // Associate shaders with the buffer objects we just created

            // Bind our vertex and index buffers to their respective buffer types
            gl.bindBuffer(gl.ARRAY_BUFFER, vertexbuffer);
            gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, indexbuffer);

            // Use our standard shader program for rendering this triangle
            gl.useProgram( Shader.standardProgram );

            // Get attribute location
            var coords = gl.getAttribLocation(Shader.standardProgram, "a_Position");

            // Pointer to the currently bound VBO (vertex buffer object)
            gl.vertexAttribPointer(coords, 3, gl.FLOAT, false, 0, 0);

            // Enable it
            gl.enableVertexAttribArray(coords);

            //Shader.standardProgram.use();

            // Start main drawing loop
            var T = setInterval(function() {

                if (!gl)
                    return;

                // Create WebGL canvas
                gl.clearColor(0.0, 0.0, 0.0, 1.0);

                gl.clear(gl.COLOR_BUFFER_BIT);

                // Draw triangle
                gl.drawElements(gl.TRIANGLES, indices.length, gl.UNSIGNED_SHORT,0);

            });

        }

    </script>
</head>
<style>
    #fx { position: relative; margin: 0 auto; width: 1000px; height: 500px; border: 1px solid gray; }
    #gl { width: 800px; height: 600px; }
</style>
<body style = "background: #202020; padding: 32px;">
<canvas id = "gl" width = "800" height = "600"></canvas>
</body>
</html>