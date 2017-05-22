<!doctype html>
<html>
<head>
    <title>Tutorial 7 - Making A 3D Cube Out Of Vertices (Float32Array) Semi-Programmatically</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <script src = 'http://www.tigrisgames.com/js/jquery.js' type = 'text/javascript'></script>
    <script src = 'http://www.tigrisgames.com/js/ui.js' type = 'text/javascript'></script>
    <script src = 'http://www.tigrisgames.com/fx/gl.js'></script>
    <script src = 'http://www.tigrisgames.com/fx/shaders.js'></script>
    <script src = 'http://www.tigrisgames.com/fx/primitives.js'></script>

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

                // Enable depth test
                gl.enable(gl.DEPTH_TEST);
                gl.depthFunc(gl.LESS);

                CreateShadersFromFile( gl );
            }
        });

        // An event that fires when all shader resources finish loading in CreateShadersFromFile
        window.webGLResourcesLoaded = function() {

            console.log("webGLResourcesLoaded(): All webGL shaders have finished loading!");

            // Specify triangle vertex data:
            var vertices = makeCube();

            var colors = makeCubeColors();

            var indices = [0, 1, 2, 3, 4, 5];

            // Create buffer objects for storing triangle vertex and index data
            var vertexbuffer = gl.createBuffer();
            var colorbuffer = gl.createBuffer();
            var indexbuffer = gl.createBuffer();

            var BYTESIZE = vertices.BYTES_PER_ELEMENT;

            // Bind and create enough room for our data on respective buffers

            // Bind it to ARRAY_BUFFER
            gl.bindBuffer(gl.ARRAY_BUFFER, vertexbuffer);
            // Send our vertex data to the buffer using floating point array
            gl.bufferData(gl.ARRAY_BUFFER, vertices, gl.STATIC_DRAW);
            var coords = gl.getAttribLocation(Shader.vertexColorProgram, "a_Position");
            gl.vertexAttribPointer(coords, 3, gl.FLOAT, false, BYTESIZE*3, 0);
            gl.enableVertexAttribArray(coords); // Enable it
            // We're done; now we have to unbind the buffer
            gl.bindBuffer(gl.ARRAY_BUFFER, null);

            // Bind it to ARRAY_BUFFER
            gl.bindBuffer(gl.ARRAY_BUFFER, colorbuffer);
            // Send our vertex data to the buffer using floating point array
            gl.bufferData(gl.ARRAY_BUFFER, colors, gl.STATIC_DRAW);
            var colors = gl.getAttribLocation(Shader.vertexColorProgram, "a_Color");
            gl.vertexAttribPointer(colors, 3, gl.FLOAT, false, BYTESIZE*3, 0);
            gl.enableVertexAttribArray(colors); // Enable it
            // We're done; now we have to unbind the buffer
            gl.bindBuffer(gl.ARRAY_BUFFER, null);

            // Bind it to ELEMENT_ARRAY_BUFFER
            gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, indexbuffer);
            // Send index (indices) data to this buffer
            gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(indices), gl.STATIC_DRAW);

            // Use our standard shader program for rendering this triangle
            gl.useProgram( Shader.vertexColorProgram );

            // Start main drawing loop
            var T = setInterval(function() {

                if (!gl)
                    return;

                // Create WebGL canvas
                gl.clearColor(0.0, 0.0, 0.0, 1.0);

                gl.clear(gl.COLOR_BUFFER_BIT);

                // Draw triangle
                gl.drawElements(gl.TRIANGLES, indices.length, gl.UNSIGNED_SHORT, 0);
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